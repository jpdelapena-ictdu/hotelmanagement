<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Transaction;
use App\Models\Customer;
use App\Models\Service;
use App\Models\Reservation;
use App\Models\Room;
use DateTime;
use DateInterval;
use DatePeriod;

class TransactionController extends Controller
{

    public function list() {
    	$transactionArr = [];
    	$transactions = Transaction::all();
    	$x = 0;
    	$services = Service::all();
    	$reservations = Reservation::all();
    	$today = date("Y-m-d");
    	$customerArr = [];
    	$x = 0;

        foreach ($reservations as $row) {

            $start_date = new DateTime($row->arrival);
            $end_date = new DateTime($row->departure);
            $interval = DateInterval::createFromDateString('1 day');
            $period = new DatePeriod($start_date, $interval, $end_date);

            foreach ($period as $dt) {
            	if( $dt->format("Y-m-d") == $today ) {
            		$customer = Customer::where('customer_id', $row->customer_id)->first();
            		$room = Room::find($row->room_id);
            		$customerArr[$x++] = [
            			'customer_id' => $customer->customer_id,
            			'name' => $customer->firstname .' '. $customer->lastname,
            			'room_id' => $room->id,
            			'room_name' => $room->roomcode
            		];
            	}
            }

        }

    	foreach ($transactions as $row) {
    		$customer = Customer::where('customer_id', $row->customer_id)->first();
    		$customer_name = $customer->firstname. ' ' .$customer->lastname;

    		$transactionArr[$x++] = [
    			'customer_name' => $customer_name,
    			'description' => $row->description,
    			'amount' => $row->amount,
    			'status' => $row->status,
    			'date' => date_format($row->created_at,"d F Y")
    		];
    	}

    	$customerArr = json_decode(json_encode($customerArr));
    	$transactionArr = json_decode(json_encode($transactionArr));

    	return view('transactions.list')
    		->with('transactions', $transactionArr)
    		->with('services', $services)
    		->with('customers', $customerArr);
    }

    public function storeServices(Request $request) {
        $serviceName = '';
        $servicePrice = 0;
        $description = '';

        if($request->customer_id == '---') {
            \Alert::error('Invalid input!')->flash();
            return redirect()->back();
        }

        foreach ($_POST as $key => $value) {
            if ($key == '_token' || $key == 'customer_id') {
                continue;
            }

            if ($value == '') {
                continue;
            }

            $service = Service::find($key);
            $serviceName = $service->service;
            $servicePrice = $service->price * $value;
            $description = $value. ' ' .$serviceName;

            $transaction = New Transaction;
            $transaction->customer_id = $request->customer_id;
            $transaction->description = $description;
            $transaction->amount = $servicePrice;
            $transaction->status = 0;
            $transaction->save();
        }

        // show a success message
        \Alert::success('Transaction added.')->flash();
        return redirect()->route('customer.unpaid', $request->customer_id);

    }
}
