<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\ReservationRequest as StoreRequest;
use App\Http\Requests\ReservationRequest as UpdateRequest;
use Illuminate\Http\Request;
use App\Models\Roomtype;
use App\Models\Rate;
use App\Models\Customer;
use App\Models\Reservation;
use App\Models\Room;
use App\Billing;
use App\Models\Roomrate;
use App\Transaction;
use App\Log;
use DateTime;
use DateInterval;
use DatePeriod;
use Carbon\Carbon;

/**
 * Class ReservationCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class ReservationCrudController extends CrudController
{
    var $availableRoomTypes = [];
    var $availableRooms = [];
    var $unavailableRooms = [];  
    

    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Reservation');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/reservation');
        $this->crud->setEntityNameStrings('reservation', 'reservations');

        $this->crud->setCreateView('vendor.backpack.base.reservation_create');
        $this->crud->setEditView('vendor.backpack.base.reservation_edit');
        $this->crud->setListView('vendor.backpack.base.reservation_list');


        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */

        $this->crud->setFromDb();

        // ------ CRUD COLUMNS
        $this->crud->removeColumns(['customer_id', 'roomtype_id', 'room_id', 'rate_id', 'notes', 'early_checkin', 'late_checkout']);
        $this->crud->addColumn('customer_name')->afterColumn('reservation_code');
        $this->crud->addColumn('room_type')->afterColumn('customer_name');
        $this->crud->addColumn('room')->afterColumn('room_type');
        $this->crud->addColumn('rate')->afterColumn('room');
        $this->crud->addColumn('night')->afterColumn('departure');/*
        $this->crud->addColumn('early_in')->afterColumn('adults');
        $this->crud->addColumn('late_out')->afterColumn('early_in');*/
        $this->crud->addColumn('note');
        
        // $this->crud->addColumn(); // add a single column, at the end of the stack
        // $this->crud->addColumns(); // add multiple columns, at the end of the stack
        // $this->crud->removeColumn('column_name'); // remove a column from the stack
        // $this->crud->removeColumns(['column_name_1', 'column_name_2']); // remove an array of columns from the stack
        // $this->crud->setColumnDetails('column_name', ['attribute' => 'value']); // adjusts the properties of the passed in column (by name)
        // $this->crud->setColumnsDetails(['column_1', 'column_2'], ['attribute' => 'value']);

        // ------ CRUD FIELDS

        // $this->crud->addField($options, 'update/create/both');
        // $this->crud->addFields($array_of_arrays, 'update/create/both');
        // $this->crud->removeField('name', 'update/create/both');
        // $this->crud->removeFields($array_of_names, 'update/create/both');
        $this->crud->removeFields(['room_id'], 'both');

        // add asterisk for fields that are required in ReservationRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');

        // ------ CRUD BUTTONS
        
        // $this->crud->addButtonFromView('line', 'btnCheckIn', 'checkIn', 'beginning');
        $this->crud->addButtonFromModelFunction('top', 'expected_guests', 'expectedGuests', 'last');
        $this->crud->addButtonFromModelFunction('top', 'open_google', 'openGoogle', 'last');
        // $this->crud->addButtonFromModelFunction('top', 'open_google', 'openGoogle', 'last'); // add a button whose HTML is returned by a method in the CRUD model
        // possible positions: 'beginning' and 'end'; defaults to 'beginning' for the 'line' stack, 'end' for the others;
        // $this->crud->addButton($stack, $name, $type, $content, $position); // add a button; possible types are: view, model_function
        // $this->crud->addButtonFromModelFunction($stack, $name, $model_function_name, $position); // add a button whose HTML is returned by a method in the CRUD model
        // $this->crud->addButtonFromView($stack, $name, $view, $position); // add a button whose HTML is in a view placed at resources\views\vendor\backpack\crud\buttons
        // $this->crud->removeButton($name);
        // $this->crud->removeButtonFromStack($name, $stack);
        // $this->crud->removeAllButtons();
        // $this->crud->removeAllButtonsFromStack('line');

        // ------ CRUD ACCESS
        $this->crud->allowAccess(['list', 'create', 'update', 'reorder', 'delete', 'expected_guests']);
        // $this->crud->allowAccess(['list', 'create', 'update', 'reorder', 'delete']);
        // $this->crud->denyAccess(['list', 'create', 'update', 'reorder', 'delete']);

        // ------ CRUD REORDER
        // $this->crud->enableReorder('label_name', MAX_TREE_LEVEL);
        // NOTE: you also need to do allow access to the right users: $this->crud->allowAccess('reorder');

        // ------ CRUD DETAILS ROW
        // $this->crud->enableDetailsRow();
        // NOTE: you also need to do allow access to the right users: $this->crud->allowAccess('details_row');
        // NOTE: you also need to do overwrite the showDetailsRow($id) method in your EntityCrudController to show whatever you'd like in the details row OR overwrite the views/backpack/crud/details_row.blade.php

        // ------ REVISIONS
        // You also need to use \Venturecraft\Revisionable\RevisionableTrait;
        // Please check out: https://laravel-backpack.readme.io/docs/crud#revisions
        // $this->crud->allowAccess('revisions');

        // ------ AJAX TABLE VIEW
        // Please note the drawbacks of this though:
        // - 1-n and n-n columns are not searchable
        // - date and datetime columns won't be sortable anymore
        // $this->crud->enableAjaxTable();

        // ------ DATATABLE EXPORT BUTTONS
        // Show export to PDF, CSV, XLS and Print buttons on the table view.
        // Does not work well with AJAX datatables.
        // $this->crud->enableExportButtons();

        // ------ ADVANCED QUERIES
        // $this->crud->addClause('active');
        // $this->crud->addClause('type', 'car');
        // $this->crud->addClause('where', 'name', '=', 'car');
        // $this->crud->addClause('whereName', 'car');
        // $this->crud->addClause('whereHas', 'posts', function($query) {
        //     $query->activePosts();
        // });
        // $this->crud->addClause('withoutGlobalScopes');
        // $this->crud->addClause('withoutGlobalScope', VisibleScope::class);
        // $this->crud->with(); // eager load relationships
        // $this->crud->orderBy();
        $this->crud->orderBy('created_at', 'desc');
        // $this->crud->groupBy();
        // $this->crud->limit();
    }

    public function create()
    {
        $customers = Customer::all();
        $rates = Rate::all();
        $roomtypes = Roomtype::all();
        $roomtypeArr = [];
        foreach ($roomtypes as $row) {
            $roomtypeArr[$row->id] = $row->typecode;
        }
        $this->crud->hasAccessOrFail('create');

        // prepare the fields you need to show
        $this->data['crud'] = $this->crud;
        $this->data['saveAction'] = $this->getSaveAction();
        $this->data['fields'] = $this->crud->getCreateFields();
        $this->data['title'] = trans('backpack::crud.add').' '.$this->crud->entity_name;

        // load the view from /resources/views/vendor/backpack/crud/ if it exists, otherwise load the one in the package
        return view($this->crud->getCreateView(), $this->data)
            ->with('roomtypes', $roomtypeArr)
            ->with('rates', $rates)
            ->with('customers', $customers);
    }

    public function edit($id)
    {
        $reservation = Reservation::find($id);
        $customers = Customer::all();
        $rates = Rate::all();
        $roomtypes = Roomtype::all();
        $roomtypeArr = [];
        foreach ($roomtypes as $row) {
            $roomtypeArr[$row->id] = $row->typecode;
        }
        $this->crud->hasAccessOrFail('update');

        // get entry ID from Request (makes sure its the last ID for nested resources)
        $id = $this->crud->getCurrentEntryId() ?? $id;

        // get the info for that entry
        $this->data['entry'] = $this->crud->getEntry($id);
        $this->data['crud'] = $this->crud;
        $this->data['saveAction'] = $this->getSaveAction();
        $this->data['fields'] = $this->crud->getUpdateFields($id);
        $this->data['title'] = trans('backpack::crud.edit').' '.$this->crud->entity_name;

        $this->data['id'] = $id;

        // load the view from /resources/views/vendor/backpack/crud/ if it exists, otherwise load the one in the package
        return view($this->crud->getEditView(), $this->data)
            ->with('roomtypes', $roomtypeArr)
            ->with('rates', $rates)
            ->with('customers', $customers)
            ->with('reservation', $reservation);
    }

    public function store(StoreRequest $request)
    {   
        $reservation = Reservation::where('room_id', $request->room_id)->get();

        foreach ($reservation as $row) {
            $start_date = new DateTime($row->arrival);
            $end_date = new DateTime($row->departure);

            $interval = DateInterval::createFromDateString('1 day');
            $period = new DatePeriod($start_date, $interval, $end_date);

            foreach ($period as $dt) {
              // echo $dt->format("Y-m-d"). "<br>";
                if(date('Y-m-d', strtotime($request->arrival)) == $dt->format("Y-m-d") || date('Y-m-d', strtotime($request->departure)) == $dt->format("Y-m-d")){

                    \Alert::warning('This room is not available in these dates.')->flash();

                    return redirect()->back();
                }
            }
        }

        $reservation_code = '';
        $reservation = Reservation::orderBy('created_at', 'desc')->first();
        $reservations = Reservation::all();
        if ($reservations->count() > 0) {
            $reservation_code = '900'. $reservation->id+1;
        } else {
            $reservation_code = '9001';
        }

        $en_arrival = date('Y-m-d H:i:s', strtotime($request->arrival));
        $en_departure = date('Y-m-d H:i:s', strtotime($request->departure));
        $request->offsetSet('arrival', $en_arrival);
        $request->offsetSet('departure', $en_departure);
        $request->offsetSet('reservation_code', $reservation_code);
        // your additional operations before save here
        $redirect_location = parent::storeCrud($request);
        // your additional operations after save here

        // TRANSACTION

        $arrival_date = strtotime($request->arrival);
        $departure_date = strtotime($request->departure);
        $datediff = $departure_date - $arrival_date;
        $night = round($datediff / (60 * 60 * 24));

        $room = Room::find($request->room_id);
        $roomtype = Roomtype::find($request->roomtype_id);
        $roomrate = Roomrate::where([['rate_id', $request->rate_id], ['roomtype_id', $request->roomtype_id]])->first();
        $roomprice = $roomrate->price;
        if ($night > 1) {
            $roomprice = $roomprice * $night;
        }
        $total = ($roomprice * $request->discount) / 100;
        $total = $roomprice - $total;

        if (!$request->payment == '') {
            if ($request->payment == $total) {

                $transaction = New Transaction;
        
                $description = 'Room ' .$room->roomcode. ' ' .$roomtype->typename;
                $transaction->customer_id = $request->customer_id;
                $transaction->room_id = $request->room_id;
                $transaction->roomtype_id = $request->roomtype_id;
                $transaction->arrival = $en_arrival;
                $transaction->departure = $en_departure;
                $transaction->description = $description;
                $transaction->amount = $request->payment;
                // $transaction->discount = $request->discount;
                $transaction->status = 1;
                $transaction->save();

            }
            else {
                $balance = $total - $request->payment;
                $description = 'Room ' .$room->roomcode. ' ' .$roomtype->typename. ' (Paid: ' .$request->payment. ', Balance: '. $balance. ')';
                $transaction = New Transaction;

                $transaction->customer_id = $request->customer_id;
                $transaction->room_id = $request->room_id;
                $transaction->roomtype_id = $request->roomtype_id;
                $transaction->description = $description;
                $transaction->amount = $request->payment;
                // $transaction->discount = $request->discount;
                $transaction->status = 1;
                $transaction->save();

                $transaction = New Transaction;
        
                $description = 'Room ' .$room->roomcode. ' ' .$roomtype->typename;
                $transaction->customer_id = $request->customer_id;
                $transaction->room_id = $request->room_id;
                $transaction->roomtype_id = $request->roomtype_id;
                $transaction->arrival = $en_arrival;
                $transaction->departure = $en_departure;
                $transaction->description = $description;
                $transaction->amount = $balance;
                // $transaction->discount = $request->discount;
                $transaction->status = 0;
                $transaction->save();

            }
        }

        // TRANSACTION END

        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    public function update(UpdateRequest $request)
    {
        $en_arrival = date('Y-m-d H:i:s', strtotime($request->arrival));
        $en_departure = date('Y-m-d H:i:s', strtotime($request->departure));
        /*$request->offsetSet('arrival', $en_arrival);
        $request->offsetSet('departure', $en_departure);
        $request->offsetSet('reservation_code', $request->reservation_code);*/
        
        $this->validate($request, [
            'customer_id'          =>             ["required", "not_regex:(---)"],  
            'roomtype_id'          =>             ["required", "not_regex:(---)"],
            'room_id'              =>             ["required", "not_regex:(---)"],
            'rate_id'              =>             ["required", "not_regex:(---)"],
            'arrival'              =>             'required',
            'departure'            =>             'required',
            'payment'              =>             'required'
        ]);

        $reservation = Reservation::find($request->reservation_id);

        $old_arrival = $reservation->arrival;
        $old_departure = $reservation->departure;
        $old_customerID = $reservation->customer_id;
        $old_roomtypeID = $reservation->roomtype_id;
        $old_roomID = $reservation->room_id;

        $reservation->customer_id = $request->customer_id;
        $reservation->roomtype_id = $request->roomtype_id;
        $reservation->room_id = $request->room_id;
        $reservation->rate_id = $request->rate_id;
        $reservation->arrival = $en_arrival;
        $reservation->departure = $en_departure;
        $reservation->payment = $request->payment;
        $reservation->discount = $request->discount;

        if ($request->has('notes')) {
            $reservation->notes = $request->notes;
        }

        if ($request->has('additional_information')) {
            $reservation->additional_information = $request->additional_information;
        }

        $reservation->save();

        // UPDATE TRANSACTION
        $arrival_date = strtotime($request->arrival);
        $departure_date = strtotime($request->departure);
        $datediff = $departure_date - $arrival_date;
        $night = round($datediff / (60 * 60 * 24));

        $room = Room::find($request->room_id);
        $roomtype = Roomtype::find($request->roomtype_id);
        
        $roomrate = Roomrate::where([['rate_id', $request->rate_id], ['roomtype_id', $request->roomtype_id]])->first();
        $roomprice = $roomrate->price;
        if ($night > 1) {
            $roomprice = $roomprice * $night;
        }
        $total = ($roomprice * $request->discount) / 100;
        $total = $roomprice - $total;

        if (!$request->payment == '') {
            if ($request->payment == $total) {
                $transaction = Transaction::where([['customer_id', $old_customerID], ['room_id', $old_roomID], ['roomtype_id', $old_roomtypeID], ['arrival', $old_arrival], ['departure', $old_departure]])->first();

                $description = 'Room ' .$room->roomcode. ' ' .$roomtype->typename;
                $transaction->customer_id = $request->customer_id;
                $transaction->room_id = $request->room_id;
                $transaction->roomtype_id = $request->roomtype_id;
                $transaction->arrival = $en_arrival;
                $transaction->departure = $en_departure;
                $transaction->description = $description;
                $transaction->amount = $roomrate->price;
                $transaction->status = 1;
                $transaction->save();
            }
            else {
                $balance = $total - $request->payment;
                $description = 'Room ' .$room->roomcode. ' ' .$roomtype->typename. ' (Paid: ' .$request->payment. ', Balance: '. $balance. ')';

                $transaction = New Transaction;
                $transaction->customer_id = $request->customer_id;
                $transaction->room_id = $request->room_id;
                $transaction->roomtype_id = $request->roomtype_id;
                $transaction->description = $description;
                $transaction->amount = $request->payment;
                $transaction->status = 1;
                $transaction->save();



                $transaction = Transaction::where([['customer_id', $old_customerID], ['room_id', $old_roomID], ['roomtype_id', $old_roomtypeID], ['arrival', $old_arrival], ['departure', $old_departure]])->first();

                $description = 'Room ' .$room->roomcode. ' ' .$roomtype->typename;
                $transaction->customer_id = $request->customer_id;
                $transaction->room_id = $request->room_id;
                $transaction->roomtype_id = $request->roomtype_id;
                $transaction->arrival = $en_arrival;
                $transaction->departure = $en_departure;
                $transaction->description = $description;
                $transaction->amount = $balance;
                $transaction->status = 0;
                $transaction->save();

            }
        }

        // show a success message
        \Alert::success('The item has been modified successfully.')->flash();

        return redirect()->route('crud.reservation.index');

    }

    public function getrate($roomtype_id) {
        $roomrates = Roomrate::where('roomtype_id', $roomtype_id)->get();
        $rateArr = [];
        $x = 0;

        foreach ($roomrates as $row) {
            $rate = Rate::find($row->rate_id);
            $rateArr[$x++] = [
                'id' => $rate->id,
                'ratecode' => $rate->ratecode,
                'ratename' => $rate->ratename
            ];
        }

        $rateArr = json_decode(json_encode($rateArr));

        $html = '';

        $html .= '<option> --- </option>';
        foreach ($rateArr as $row) {
            $html .= '<option value="'.$row->id.'"> ' .$row->ratecode. ' </option>';
        }

        return $html;
    }

    public function getprice($rate_id, $roomtype_id, $dm, $dd, $dy, $am, $ad, $ay) {
        $roomrate = Roomrate::where([['rate_id', $rate_id], ['roomtype_id', $roomtype_id]])->first();
        $roomprice = $roomrate->price;

        $arrival_date = strtotime($am.'/'.$ad.'/'.$ay);
        $departure_date = strtotime($dm.'/'.$dd.'/'.$dy);


        $datediff = $departure_date - $arrival_date;

        $night = round($datediff / (60 * 60 * 24));

        if ($night > 1) {
            $roomprice = $roomprice * $night;
        }


        $html = '<label id="price_label">Room Price</label><input type="text" id="price_input" class="form-control" value="'.$roomprice.'" disabled >';

        return $html;
    }


    public function getroom($id, $dm, $dd, $dy, $am, $ad, $ay)
    {

        $roomtype = Roomtype::findOrFail($id);
        $packages = $roomtype->room;
        $arrival_date = date("Y-m-d", strtotime($am.'/'.$ad.'/'.$ay));
        $departure_date = date("Y-m-d", strtotime($dm.'/'.$dd.'/'.$dy));
        $rooms = Room::all();
        $roomtypes = Roomtype::all();
        $reservations = Reservation::all();
        $unavailRooms = [];
        $availRoomTypes = [];
        $x = 0;
        $c = 0;
        $z = 0;

        foreach ($reservations as $row) {

            $start_date = new DateTime($arrival_date);
            $end_date = new DateTime($departure_date);
            $interval = DateInterval::createFromDateString('1 day');
            $period = new DatePeriod($start_date, $interval, $end_date);

            $r_start = new DateTime($row->arrival);
            $r_end = new DateTime($row->departure);
            $r_interval = DateInterval::createFromDateString('1 day');
            $r_period = new DatePeriod($r_start, $r_interval, $r_end);

            foreach ($period as $dt) {

                foreach ($r_period as $r_dt) {
                    if ($dt->format("Y-m-d") == $r_dt->format("Y-m-d")) {
                        if (!in_array($row->room_id, $unavailRooms)) {
                            $unavailRooms[$c++] = $row->room_id;
                            break;
                        }
                    }
                }
            }

        }

        $html = '';

        $html .= '<option> --- </option>';
        foreach ($packages as $package)
        {
            if (!in_array($package->id, $unavailRooms)) {
                $html .= '<option value="'.$package->id.'"> '.$package->roomcode.' </option>';
            }
        }

        return $html;

    }

    public function editgetroom($id, $room_id, $dm, $dd, $dy, $am, $ad, $ay)
    {

        $roomtype = Roomtype::findOrFail($id);
        $packages = $roomtype->room;

        $arrival_date = date("Y-m-d", strtotime($am.'/'.$ad.'/'.$ay));
        $departure_date = date("Y-m-d", strtotime($dm.'/'.$dd.'/'.$dy));
        $rooms = Room::all();
        $roomtypes = Roomtype::all();
        $reservations = Reservation::all();
        $unavailRooms = [];
        $availRoomTypes = [];
        $x = 0;
        $c = 0;
        $z = 0;

        foreach ($reservations as $row) {

            $start_date = new DateTime($arrival_date);
            $end_date = new DateTime($departure_date);
            $interval = DateInterval::createFromDateString('1 day');
            $period = new DatePeriod($start_date, $interval, $end_date);

            $r_start = new DateTime($row->arrival);
            $r_end = new DateTime($row->departure);
            $r_interval = DateInterval::createFromDateString('1 day');
            $r_period = new DatePeriod($r_start, $r_interval, $r_end);

            foreach ($period as $dt) {

                foreach ($r_period as $r_dt) {
                    if ($dt->format("Y-m-d") == $r_dt->format("Y-m-d")) {
                        if (!in_array($row->room_id, $unavailRooms)) {
                            if ($row->room_id == $room_id) {
                                break;
                            } else {
                                $unavailRooms[$c++] = $row->room_id;
                                break;
                            }
                        }
                    }
                }
            }

        }

        // return response()->json($packages);

        // Send as HTML

        $html = '';

        // $html .= '<label id="remove_label">Room</label><select id="remove_select" name="room_id" class="form-control select2_from_array">';
         $html .= '<option> --- </option>';
         foreach ($packages as $package)
         {
            if (!in_array($package->id, $unavailRooms)) {
                $html .= '<option '.($room_id == $package->id ? 'selected':'').' value="'.$package->id.'"> '.$package->roomcode.' </option>';
            }
         }
         // $html .= '</select>';

        return $html;

    }

    public function getnight($dm, $dd, $dy, $am, $ad, $ay)
    {
        $arrival_date = strtotime($am.'/'.$ad.'/'.$ay);
        $departure_date = strtotime($dm.'/'.$dd.'/'.$dy);


        $datediff = $departure_date - $arrival_date;

        $night = round($datediff / (60 * 60 * 24));

        $html = '<label id="night_label">Nights</label><input type="text" name="night" id="night" class="form-control" value="'.$night.'" disabled>';
        return $html;

    }

    public function getroomtype($dm, $dd, $dy, $am, $ad, $ay)
    {
        array_splice($this->availableRoomTypes, 0);
        array_splice($this->availableRooms, 0);
        array_splice($this->unavailableRooms, 0);

        $arrival_date = date("Y-m-d", strtotime($am.'/'.$ad.'/'.$ay));
        $departure_date = date("Y-m-d", strtotime($dm.'/'.$dd.'/'.$dy));
        $rooms = Room::all();
        $roomtypes = Roomtype::all();
        $reservations = Reservation::all();
        $x = 0;
        $c = 0;
        $z = 0;


        foreach ($reservations as $row) {

            $start_date = new DateTime($arrival_date);
            $end_date = new DateTime($departure_date);
            $interval = DateInterval::createFromDateString('1 day');
            $period = new DatePeriod($start_date, $interval, $end_date);

            $r_start = new DateTime($row->arrival);
            $r_end = new DateTime($row->departure);
            $r_interval = DateInterval::createFromDateString('1 day');
            $r_period = new DatePeriod($r_start, $r_interval, $r_end);

            foreach ($period as $dt) {

                foreach ($r_period as $r_dt) {
                    if ($dt->format("Y-m-d") == $r_dt->format("Y-m-d")) {
                        if (!in_array($row->room_id, $this->unavailableRooms)) {
                            $this->unavailableRooms[$c++] = $row->room_id;
                            break;
                        }
                    }
                }
            }

        }

        foreach ($rooms as $row) {
            if(!in_array($row->id, $this->unavailableRooms)) {
                $this->availableRooms[$x++] = $row->id;
                if (!in_array($row->roomtype_id, $this->availableRoomTypes)) {
                    $this->availableRoomTypes[$z++] = $row->roomtype_id;
                }
            }
        }

        $html = '';

        // $html .= '<label id="roomtype_label">Roomtype</label><select id="roomtype_select" name="roomtype_id" class="form-control select2_from_array">';
        $html .= '<option> --- </option>';
        foreach ($roomtypes as $row)
        {
            if (in_array($row->id, $this->availableRoomTypes)) {
                $html .= '<option value="'.$row->id.'"> '.$row->typecode.' </option>';
            }
        }
        // $html .= '</select>';

        return $html;

    }

    public function expectedGuests() {
        $reservations = Reservation::whereDate('arrival', Carbon::today())->whereNull('check_in')->get();
        $reservationArr = [];
        $x = 0;

        foreach ($reservations as $row) {
            $customer = Customer::where('customer_id', $row->customer_id)->first();
            $roomtype = Roomtype::find($row->roomtype_id);
            $room = Room::find($row->room_id);
            $rate = Rate::find($row->rate_id);
            $reservationArr[$x++] = [
                'id' => $row->id,
                'reservation_code' => $row->reservation_code,
                'customer_id' => $row->customer_id,
                'customer_name' => $customer->firstname. ' ' .$customer->lastname,
                'roomtype_id' => $row->roomtype_id,
                'roomtypecode' => $roomtype->typecode,
                'room_id' => $row->room_id,
                'roomcode' => $room->roomcode,
                'rate_id' => $row->rate_id,
                'ratecode' => $rate->ratecode,
                'arrival' => date('d F Y', strtotime($row->arrival)),
                'departure' => date('d F Y', strtotime($row->departure)),
                'check_in' => $row->check_in,
                'check_out' => $row->check_out,
                'payment' => $row->payment,
                'notes' => $row->notes,
                'additional_information' => $row->additional_information,
                'created_at' => $row->created_at,
                'updated_at' => $row->updated_at
            ];
        }

        $reservationArr = json_decode(json_encode($reservationArr));

        return view('reservations.expected-guests')
            ->with('reservations', $reservationArr);
    }

    public function checkIn($id) {
        $reservation = Reservation::find($id);

        $dateToday = date('Y-m-d H:i:s');

        $reservation->check_in = $dateToday;
        $reservation->save();

        \Alert::success('Checked in successfully.')->flash();
        return redirect()->back();
    }

    public function checkOut(Request $request) {
        $reservation = Reservation::find($request->reservation_id);
        $dateToday = date('Y-m-d H:i:s');

        $reservation->check_out = $dateToday;
        $reservation->save();

        foreach ($_POST as $key => $value) {
            if (strpos($key, 'transaction') !== false) {
                $transaction = Transaction::find($value);
                $transaction->status = 1;
                $transaction->save();
            }
        }

        \Alert::success('Checked out successfully.')->flash();
        return redirect()->route('customer.paid', $reservation->customer_id);

        // print_r($_POST);
    }

    public function guestsToday() {
        $reservations = Reservation::all();
        $reservationArr = [];
        $x = 0;

        foreach ($reservations as $row) {
            if (!($row->check_in == '') && ($row->check_out == '')) {
                $customer = Customer::where('customer_id', $row->customer_id)->first();
                $roomtype = Roomtype::find($row->roomtype_id);
                $room = Room::find($row->room_id);
                $rate = Rate::find($row->rate_id);
                $reservationArr[$x++] = [
                    'id' => $row->id,
                    'reservation_code' => $row->reservation_code,
                    'customer_id' => $row->customer_id,
                    'customer_name' => $customer->firstname. ' ' .$customer->lastname,
                    'roomtype_id' => $row->roomtype_id,
                    'roomtypecode' => $roomtype->typecode,
                    'room_id' => $row->room_id,
                    'roomcode' => $room->roomcode,
                    'rate_id' => $row->rate_id,
                    'ratecode' => $rate->ratecode,
                    'arrival' => date('d F Y', strtotime($row->arrival)),
                    'departure' => date('d F Y', strtotime($row->departure)),
                    'check_in' => $row->check_in,
                    'check_out' => $row->check_out,
                    'payment' => $row->payment,
                    'notes' => $row->notes,
                    'additional_information' => $row->additional_information,
                    'created_at' => $row->created_at,
                    'updated_at' => $row->updated_at
                ];
            }
        }

        $reservationArr = json_decode(json_encode($reservationArr));

        return view('reservations.guests-today')
            ->with('reservations', $reservationArr);

    }

    public function customerCheckOut($id) {
        // $customer = Transaction::where('customer_id', $id)->get();
        $reservation = Reservation::find($id);
        $customer = Transaction::where([['customer_id', $reservation->customer_id], ['status', 0]])->get();

        return $customer;
    }
}
