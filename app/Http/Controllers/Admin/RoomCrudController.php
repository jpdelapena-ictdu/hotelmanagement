<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\RoomRequest as StoreRequest;
use App\Http\Requests\RoomRequest as UpdateRequest;
use App\Models\Room;
use App\Models\Reservation;
use App\Models\Customer;
use App\Models\Roomtype;

/**
 * Class RoomCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class RoomCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Room');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/room');
        $this->crud->setEntityNameStrings('room', 'rooms');
        $this->crud->setListView('vendor.backpack.base.room_list');


        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */

        $this->crud->setFromDb();

        // ------ CRUD COLUMNS
        // $this->crud->addColumn(); // add a single column, at the end of the stack
        // $this->crud->addColumns(); // add multiple columns, at the end of the stack
        // $this->crud->removeColumn('column_name'); // remove a column from the stack
        // $this->crud->removeColumns(['column_name_1', 'column_name_2']); // remove an array of columns from the stack
        // $this->crud->setColumnDetails('column_name', ['attribute' => 'value']); // adjusts the properties of the passed in column (by name)
        // $this->crud->setColumnsDetails(['column_1', 'column_2'], ['attribute' => 'value']);
        $this->crud->addColumn('type');
        $this->crud->removeColumn('roomtype_id');
        $this->crud->addColumn('bldg');
        $this->crud->removeColumn('building_id');
        $this->crud->addColumn('stats');
        $this->crud->removeColumn('status');
        
        // ------ CRUD FIELDS
        // $this->crud->addField($options, 'update/create/both');
        // $this->crud->addFields($array_of_arrays, 'update/create/both');
        // $this->crud->removeField('name', 'update/create/both');
        // $this->crud->removeFields($array_of_names, 'update/create/both');
        $this->crud->addField(
        [
        // 1-n relationship
        'label' => "Building", // Table column heading
        'type' => "select",
        'name' => 'building_id', // the column that contains the ID of that connected entity;
        'entity' => 'building', // the method that defines the relationship in your Model
        'attribute' => "bldgname", // foreign key attribute that is shown to user
        'model' => "App\Models\building", // foreign key model
        ]);

        $this->crud->addField(
        [
        // 1-n relationship
        'label' => "Room Type", // Table column heading
        'type' => "select",
        'name' => 'roomtype_id', // the column that contains the ID of that connected entity;
        'entity' => 'roomtype', // the method that defines the relationship in your Model
        'attribute' => "typename", // foreign key attribute that is shown to user
        'model' => "App\Models\Roomtype", // foreign key model
        ]);

        $this->crud->addField(
        [ // select_from_array
        'name' => 'status',
        'label' => "Status",
        'type' => 'select_from_array',
        'options' => ['0' => 'Available', '1' => 'Occupied', '2' => 'Under Maintenance'],
        'allows_null' => false,
        'default' => 'one',
    // 'allows_multiple' => true, // OPTIONAL; needs you to cast this to array in your model;
        ]);
        // add asterisk for fields that are required in RoomRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');

        // ------ CRUD BUTTONS
        // $this->crud->addButtonFromModelFunction('line', 'room_availabity', 'roomAvailabilty', 'beginning');
        // $this->crud->addButtonFromModelFunction('line', 'room_calendar', 'roomCalendar', 'beginning'); // add a button whose HTML is returned by a method in the CRUD model
        $this->crud->addButtonFromView('line', 'calendarBtn', 'roomCal', 'beginning');
        // possible positions: 'beginning' and 'end'; defaults to 'beginning' for the 'line' stack, 'end' for the others;
        // $this->crud->addButton($stack, $name, $type, $content, $position); // add a button; possible types are: view, model_function
        // $this->crud->addButtonFromModelFunction($stack, $name, $model_function_name, $position); // add a button whose HTML is returned by a method in the CRUD model
        // $this->crud->addButtonFromView($stack, $name, $view, $position); // add a button whose HTML is in a view placed at resources\views\vendor\backpack\crud\buttons
        // $this->crud->removeButton($name);
        // $this->crud->removeButtonFromStack($name, $stack);
        // $this->crud->removeAllButtons();
        // $this->crud->removeAllButtonsFromStack('line');

        // ------ CRUD ACCESS
        $this->crud->allowAccess(['list', 'create', 'update', 'delete', 'room_cal']);
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
        // $this->crud->groupBy();
        // $this->crud->limit();
    }

    public function store(StoreRequest $request)
    {
        $rooms = Room::all();

        // print_r($_POST);

        foreach ($rooms as $row) {
            if ($row->roomcode == $request->roomcode && $row->building_id == $request->building_id) {
                // show a error message
                \Alert::warning('This room already exists in this building')->flash();
                return redirect()->back();
            }
        }
        // your additional operations before save here
        $redirect_location = parent::storeCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    public function update(UpdateRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    public function roomCalendar($id) {
        $reservation = Reservation::where('room_id', $id)->get();
        $room = Room::find($id);
        $x = 0;
        $reservationArr = [];
        $customerName = '';
        $roomtypeName = '';
        
        foreach ($reservation as $row) {
            $customer = Customer::where('customer_id', $row->customer_id)->first();
            $roomtype = Roomtype::find($row->roomtype_id);
            $customerName = $customer->firstname. ' ' .$customer->lastname;
            $roomtypeName = $roomtype->typename;

            $reservationArr[$x++] = [
                'id' => $row->id,
                'reservation_code' => $row->reservation_code,
                'customer_id' => $row->customer_id,
                'customer_name' => $customerName,
                'roomtype_id' => $row->roomtype_id,
                'roomtype_name' => $roomtypeName,
                'rate_id' => $row->rate_id,
                'arrival' => $row->arrival,
                'departure' => date('Y-m-d', strtotime($row->departure . ' +1 day'))
            ];
        }

        $reservationArr = json_decode(json_encode($reservationArr));

        return $reservationArr;
    }

}
