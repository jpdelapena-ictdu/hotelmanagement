<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Customer;
use App\Models\Room;

class LogController extends Controller
{
    public function __construct() {
    	$this->middleware('admin');
    }

    public function list() {
    	$reservations = Reservation::whereNotNull('check_in')->get();
    	$reservationArr = [];
    	$x = 0;

    	foreach ($reservations as $row) {
    		$customer = Customer::where('customer_id', $row->customer_id)->first();
    		$room = Room::find($row->room_id);

    		$reservationArr[$x++] = [
    			'id' => $row->id,
    			'reservation_code' => $row->reservation_code,
    			'customer_id' => $row->customer_id,
    			'customer_name' => $customer->firstname. ' ' .$customer->lastname,
    			'room_name' => $room->roomcode,
    			'check_in' => date('d F Y H:i A', strtotime($row->check_in)),
    			'check_out' => ($row->check_out == '' ?  '' : date('d F Y H:i A', strtotime($row->check_out)))
    		];
    	}

    	$reservationArr = json_decode(json_encode($reservationArr));

    	return view('logs.list')
    		->with('reservations', $reservationArr);
    }
}
