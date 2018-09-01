<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\RoomtypeRequest as StoreRequest;
use App\Http\Requests\RoomtypeRequest as UpdateRequest;
use App\Models\Room;
use App\Models\Roomtype;
use App\Models\Food;
use App\Models\Bathroom;
use App\Models\Amenity;

/**
 * Class RoomtypeCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class RoomtypeCrudController extends CrudController
{
    public function setup()
    {
        $foods = Food::all();
        $bathrooms = Bathroom::all();
        $amenities = Amenity::all();
        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Roomtype');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/roomtype');
        $this->crud->setEntityNameStrings('roomtype', 'roomtypes');

        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */

        $this->crud->setFromDb();

        // ------ CRUD COLUMNS
         // $this->crud->addColumn('available');
         $this->crud->removeColumn('status');

         $this->crud->addColumn([
           'name' => 'rooms', // The db column name
           'label' => "Available", // Table column heading
           'type' => 'availablerooms'
        ]);

        $this->crud->addColumn([
           'name' => 'food', // The db column name
           'label' => "Food & Drink", // Table column heading
           'type' => 'foods'
        ]);

        $this->crud->addColumn([
           'name' => 'bathrooms', // The db column name
           'label' => "Bathroom", // Table column heading
           'type' => 'bathrooms'
        ]);

        $this->crud->addColumn([
           'name' => 'amenities', // The db column name
           'label' => "Room Amenities", // Table column heading
           'type' => 'amenities'
        ]);
        // $this->crud->addColumn(); // add a single column, at the end of the stack
        // $this->crud->addColumns(); // add multiple columns, at the end of the stack
        // $this->crud->removeColumn('column_name'); // remove a column from the stack
        // $this->crud->removeColumns(['column_name_1', 'column_name_2']); // remove an array of columns from the stack
        // $this->crud->setColumnDetails('column_name', ['attribute' => 'value']); // adjusts the properties of the passed in column (by name)
        // $this->crud->setColumnsDetails(['column_1', 'column_2'], ['attribute' => 'value']);
        // $this->crud->setColumnsDetails([ // select_from_array
        // 'name' => 'status',
        // 'label' => "Status",
        // 'type' => 'select_from_array',
        // 'options' => ['0' => 'Available', '1' => 'Occupied' /*'2' => 'Under Maintenance'*/],
        // ]); // adjusts the properties of the passed in column (by name)
        // ------ CRUD FIELDS
         foreach ($foods as $food) {
            $this->crud->addField([ // Checkbox
                'name' => 'food_'. $food->id,
                'label' => $food->name,
                'type' => 'checkbox',
                'tab' => 'Foods'
            ], 'create');
        }

        foreach($bathrooms as $bathroom) {
            $this->crud->addField([ // Checkbox
                'name' => 'bathroom_'. $bathroom->id,
                'label' => $bathroom->name,
                'type' => 'checkbox',
                'tab' => 'Bathrooms'
            ], 'create');
        }

        foreach($amenities as $amenity) {
            $this->crud->addField([ // Checkbox
                'name' => 'amenity_'. $amenity->id,
                'label' => $amenity->name,
                'type' => 'checkbox',
                'tab' => 'Amenities'
            ], 'create');
        }

        foreach ($foods as $food) {
            $this->crud->addField([
              // Custom Field
              'name' => 'food_'. $food->id,
              'label' => $food->name,
              'type' => 'food_checkbox',
              'tab' => 'Foods'
              /// 'view_namespace' => 'yourpackage' // use a custom namespace of your package to load views within a custom view folder.
            ], 'update');
        }

        foreach ($bathrooms as $bathroom) {
            $this->crud->addField([
              // Custom Field
              'name' => 'bathroom_'. $bathroom->id,
              'label' => $bathroom->name,
              'type' => 'bathroom_checkbox',
              'tab' => 'Bathrooms'
              /// 'view_namespace' => 'yourpackage' // use a custom namespace of your package to load views within a custom view folder.
            ], 'update');
        }

        foreach ($amenities as $amenity) {
            $this->crud->addField([
              // Custom Field
              'name' => 'amenity_'. $amenity->id,
              'label' => $amenity->name,
              'type' => 'amenity_checkbox',
              'tab' => 'Amenities'
              /// 'view_namespace' => 'yourpackage' // use a custom namespace of your package to load views within a custom view folder.
            ], 'update');
        }
        // $this->crud->addField($options, 'update/create/both');
        // $this->crud->addFields($array_of_arrays, 'update/create/both');
        // $this->crud->removeField('name', 'update/create/both');
        // $this->crud->removeFields($array_of_names, 'update/create/both');
        

        // add asterisk for fields that are required in RoomtypeRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');

        // ------ CRUD BUTTONS
        // possible positions: 'beginning' and 'end'; defaults to 'beginning' for the 'line' stack, 'end' for the others;
        // $this->crud->addButton($stack, $name, $type, $content, $position); // add a button; possible types are: view, model_function
        // $this->crud->addButtonFromModelFunction($stack, $name, $model_function_name, $position); // add a button whose HTML is returned by a method in the CRUD model
        // $this->crud->addButtonFromView($stack, $name, $view, $position); // add a button whose HTML is in a view placed at resources\views\vendor\backpack\crud\buttons
        // $this->crud->removeButton($name);
        // $this->crud->removeButtonFromStack($name, $stack);
        // $this->crud->removeAllButtons();
        // $this->crud->removeAllButtonsFromStack('line');

        // ------ CRUD ACCESS
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
        $foodArray = array();
        $bathroomArray = array();
        $amenityArray = array();

        print_r($_POST);
        foreach ($_POST as $key => $value) {
            if($value == 1) {
                if (strpos($key, 'food_') !== false) {
                    // echo $key;
                    // echo substr($key, 5) . "<br>";
                    $foodArray[substr($key, 5)] = substr($key, 5);
                }
                if (strpos($key, 'bathroom_') !== false) {
                    // echo $key;
                    // echo substr($key, 8) . "<br>";
                    $bathroomArray[substr($key, 9)] = substr($key, 9);
                }
                if (strpos($key, 'amenity_') !== false) {
                    // echo $key;
                    // echo substr($key, 7) . "<br>";
                    $amenityArray[substr($key, 8)] = substr($key, 8);
                }
            }
        }

        // your additional operations before save here
        $redirect_location = parent::storeCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        $newRecordId = $this->data['entry']['id'];

        $roomtype = Roomtype::find($newRecordId);

        $roomtype->foods()->sync($foodArray, false);
        $roomtype->bathrooms()->sync($bathroomArray, false);
        $roomtype->amenities()->sync($amenityArray, false);

        return $redirect_location;
    }

    public function update(UpdateRequest $request)
    {
        $foodArray = array();
        $bathroomArray = array();
        $amenityArray = array();

        print_r($_POST);
        foreach ($_POST as $key => $value) {
            if($value == 1) {
                if (strpos($key, 'food_') !== false) {
                    // echo $key;
                    // echo substr($key, 5) . "<br>";
                    $foodArray[substr($key, 5)] = substr($key, 5);
                }
                if (strpos($key, 'bathroom_') !== false) {
                    // echo $key;
                    // echo substr($key, 8) . "<br>";
                    $bathroomArray[substr($key, 9)] = substr($key, 9);
                }
                if (strpos($key, 'amenity_') !== false) {
                    // echo $key;
                    // echo substr($key, 7) . "<br>";
                    $amenityArray[substr($key, 8)] = substr($key, 8);
                }
            }
        }

        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        $newRecordId = $this->data['entry']['id'];

        $roomtype = Roomtype::find($newRecordId);

        $roomtype->foods()->sync($foodArray, true);
        $roomtype->bathrooms()->sync($bathroomArray, true);
        $roomtype->amenities()->sync($amenityArray, true);

        return $redirect_location;
    }

    public function destroy($id)
    {
        $roomtype = Roomtype::find($id);

        $roomtype->foods()->detach();
        $roomtype->bathrooms()->detach();
        $roomtype->amenities()->detach();
        $roomtype->delete();
        // $this->crud->hasAccessOrFail('delete');

        // return $this->crud->delete($id);
    }
}
