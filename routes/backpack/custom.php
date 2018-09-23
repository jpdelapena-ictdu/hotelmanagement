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

	Route::get('/customer/{id}/transactions', 'CustomerCrudController@unpaidTransaction')->name('customer.unpaid');
	Route::get('/customer/{id}/paid-transactions', 'CustomerCrudController@paidTransaction')->name('customer.paid');
	Route::get('/room/calendar/{id}', 'RoomCrudController@roomCalendar')->name('room.calendar');

	// Route::get('/room/{roomid}/calendar', 'RoomCrudController@roomCalendar')->name('room.calendar');
	Route::get('getrate/{roomtype_id}', 'ReservationCrudController@getrate');
	Route::get('getroomtype/{dm}/{dd}/{dy}/{am}/{ad}/{ay}', 'ReservationCrudController@getroomtype');
	Route::get('getroom/{val}/{dm}/{dd}/{dy}/{am}/{ad}/{ay}', 'ReservationCrudController@getroom');
	Route::get('edit/getroom/{val}/{room_id}/{dm}/{dd}/{dy}/{am}/{ad}/{ay}', 'ReservationCrudController@editgetroom');
	Route::get('getnight/{dm}/{dd}/{dy}/{am}/{ad}/{ay}', 'ReservationCrudController@getnight');

}); // this should be the absolute last line of this file
