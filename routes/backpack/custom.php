<?php

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\Base.
// Routes you generate using Backpack\Generators will be placed here.

Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => ['web', config('backpack.base.middleware_key', 'admin')],
    'namespace'  => 'App\Http\Controllers\Admin',
], function () { // custom admin routes
	CRUD::resource('room', 'roomCrudController');
	CRUD::resource('roomtype', 'roomtypeCrudController');
	CRUD::resource('building', 'buildingCrudController');
	CRUD::resource('rate', 'rateCrudController');
	CRUD::resource('board', 'boardCrudController');
	CRUD::resource('roomrate', 'roomrateCrudController');
	CRUD::resource('customer', 'CustomerCrudController');
	CRUD::resource('food', 'FoodCrudController');
	CRUD::resource('bathroom', 'BathroomCrudController');
	CRUD::resource('amenity', 'AmenityCrudController');
	CRUD::resource('reservation', 'ReservationCrudController');

	Route::get('getroom/{val}', 'ReservationCrudController@getroom');
	Route::get('edit/getroom/{val}/{room_id}', 'ReservationCrudController@editgetroom');
	Route::get('getnight/{dm}/{dd}/{dy}/{am}/{ad}/{ay}', 'ReservationCrudController@getnight');

}); // this should be the absolute last line of this file
