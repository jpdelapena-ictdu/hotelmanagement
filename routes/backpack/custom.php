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
	CRUD::resource('service', 'ServiceCrudController');

	Route::get('expected-guests', 'ReservationCrudController@expectedGuests')->name('expected-guests');
	Route::get('guests-today', 'ReservationCrudController@guestsToday')->name('guests-today');
	Route::post('checkin/{id}', 'ReservationCrudController@checkIn')->name('check-in');
	Route::post('checkout/{id}', 'ReservationCrudController@checkOut')->name('check-out');
	Route::get('/customer/{id}/transactions', 'CustomerCrudController@unpaidTransaction')->name('customer.unpaid');
	Route::get('/transactions', 'TransactionController@list')->name('transaction.list');
	Route::get('/customer/{id}/paid-transactions', 'CustomerCrudController@paidTransaction')->name('customer.paid');
	Route::get('/room/calendar/{id}', 'RoomCrudController@roomCalendar')->name('room.calendar');

	Route::get('getrate/{roomtype_id}', 'ReservationCrudController@getrate');
	Route::get('getprice/{rate_id}/{roomtype_id}', 'ReservationCrudController@getprice');
	Route::get('getroomtype/{dm}/{dd}/{dy}/{am}/{ad}/{ay}', 'ReservationCrudController@getroomtype');
	Route::get('getroom/{val}/{dm}/{dd}/{dy}/{am}/{ad}/{ay}', 'ReservationCrudController@getroom');
	Route::get('edit/getroom/{val}/{room_id}/{dm}/{dd}/{dy}/{am}/{ad}/{ay}', 'ReservationCrudController@editgetroom');
	Route::get('getnight/{dm}/{dd}/{dy}/{am}/{ad}/{ay}', 'ReservationCrudController@getnight');


	Route::post('transaction/service/create', 'TransactionController@storeServices')->name('transaction-service.store');

}); // this should be the absolute last line of this file
