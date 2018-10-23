<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use App\Models\Customer;
use App\Models\Roomtype;
use App\Models\Room;

class Reservation extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'reservations';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['reservation_code', 'customer_id', 'roomtype_id', 'room_id', 'rate_id', 'arrival', 'departure', 'adults', 'check_in', 'check_out', 'payment', 'discount','notes', 'additional_information'];
    // protected $hidden = [];
    // protected $dates = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    public function openGoogle($crud = false)
    {
        return '<a class="btn btn-primary" target="_blank" href="guests-today" data-toggle="tooltip" title="Just a demo custom button."><i class="fa fa-users"></i> View Guests Today</a>';
    }

    public function expectedGuests($crud = false)
    {
        return '<a class="btn btn-primary" target="_blank" href="expected-guests" data-toggle="tooltip" title="View expected guests."><i class="fa fa-users"></i> View Expected Guests</a>';
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | ACCESORS
    |--------------------------------------------------------------------------
    */

    public function getCustomerNameAttribute($value){
        $customer = Customer::where('customer_id', $this->customer_id)->first();
        return $customer->customer_id. '. ' .$customer->firstname. ' ' .$customer->lastname;
    }

    public function getRoomTypeAttribute($value){
        $roomtype = Roomtype::find($this->roomtype_id);
        return $roomtype->typecode;
    }

    public function getRoomAttribute($value){
        $room = Room::find($this->room_id);
        return $room->roomcode;
    }

    public function getRateAttribute($value){
        $rate = Rate::find($this->rate_id);
        return $rate->ratecode;
    }

    public function getNightAttribute($value){
        $arrival_date = strtotime($this->arrival);
        $departure_date = strtotime($this->departure);


        $datediff = $departure_date - $arrival_date;

        $night = round($datediff / (60 * 60 * 24));

        return $night; 
    }

    public function getNoteAttribute($value){
        return $this->notes;
    }

    /*public function getEarlyInAttribute($value){
        if ($this->early_checkin == 1) {
            return 'TRUE';
        }
        return 'FALSE';
    }

    public function getLateOutAttribute($value){
        if ($this->late_checkout == 1) {
            return 'TRUE';
        }
        return 'FALSE';
    }*/

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
