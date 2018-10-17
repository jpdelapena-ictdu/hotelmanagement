<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Customer;
use App\Models\Room;
use App\Models\Roomtype;
use Carbon\Carbon;


class AdminDashboardController extends Controller
{
    public function __construct() {
    	$this->middleware('admin');
    }

    public function dashboard() {
    	// CALENDAR
    	$reservation = Reservation::whereNull('check_out')->get();
    	$reservationArr = [];
    	$x = 0;

    	foreach ($reservation as $row) {
    		$customer = Customer::where('customer_id', $row->customer_id)->first();
    		$roomtype = Roomtype::find($row->roomtype_id);
    		$room = Room::find($row->room_id);
    		$customerName = $customer->firstname. ' ' .$customer->lastname;
            $roomtypeName = $roomtype->typename;
            $roomName = $room->roomcode;

    		$reservationArr[$x++] = [
    			'id' => $row->id,
    			'reservation_code' => $row->reservation_code,
                'customer_id' => $row->customer_id,
                'customer_name' => $customerName,
                'roomtype_id' => $row->roomtype_id,
                'roomtype_name' => $roomtypeName,
                'room_name' => $roomName,
                'rate_id' => $row->rate_id,
                'arrival' => $row->arrival,
                'departure' => date('Y-m-d', strtotime($row->departure . ' +1 day'))
    		];
    	} // CALENDAR

    	// EXPECTED GUESTS
    	$reservation2 = Reservation::whereDate('arrival', Carbon::today())->whereNull('check_in')->get();
    	$expectedGuests = [];
    	$c = 0;

    	foreach ($reservation2 as $row) {
    		$customer = Customer::where('customer_id', $row->customer_id)->first();
    		$roomtype = Roomtype::find($row->roomtype_id);
    		$room = Room::find($row->room_id);
    		$customerName = $customer->firstname. ' ' .$customer->lastname;
            $roomtypeName = $roomtype->typename;
            $roomName = $room->roomcode;

            $expectedGuests[$c++] = [
            	'id' => $row->id,
            	'customer_name' => $customerName,
            	'reservation_code' => $row->reservation_code,
            	'roomtype_name' => $roomtypeName,
            	'room_name' => $roomName
            ];
    	}// EXPECTED GUESTS

    	// GUESTS TODAY
    	$reservation3 = Reservation::all();
    	$guestsToday = [];
    	$i = 0;

    	foreach ($reservation3 as $row) {
    		if (!($row->check_in == '') && ($row->check_out == '')) {
    			$customer = Customer::where('customer_id', $row->customer_id)->first();
	    		$roomtype = Roomtype::find($row->roomtype_id);
	    		$room = Room::find($row->room_id);
	    		$customerName = $customer->firstname. ' ' .$customer->lastname;
	            $roomtypeName = $roomtype->typename;
	            $roomName = $room->roomcode;

	            $guestsToday[$i++] = [
	            	'id' => $row->id,
	            	'customer_name' => $customerName,
	            	'reservation_code' => $row->reservation_code,
	            	'roomtype_name' => $roomtypeName,
	            	'room_name' => $roomName
	            ];
    		}
    	}
    	// GUESTS TODAY


    	$expectedGuests = json_decode(json_encode($expectedGuests));
    	$guestsToday = json_decode(json_encode($guestsToday));
    	$reservationArr = json_decode(json_encode($reservationArr));

    	return view('dashboard')
    		->with('reservations', $reservationArr)
    		->with('expectedguests', $expectedGuests)
    		->with('gueststoday', $guestsToday);
    }
}
