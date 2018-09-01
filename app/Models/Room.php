<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use App\Models\Reservation;
use DateTime;
use DateInterval;
use DatePeriod;

class Room extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'rooms';
    protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['roomcode','roomtype_id','building_id','floor','status'];
    // protected $hidden = [];
    // protected $dates = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function roomtype()
    {
       return $this->belongsTo('App\Models\Roomtype');
    }

    public function building()
    {
        return $this->belongsTo('App\Models\Building');
    }

    public function roomrate()
    {
        return $this->hasMany('App\Models\Roomrate');
    }
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
    public function getStatsAttribute() {

        $reservation = Reservation::where('room_id', $this->id)->get();
        $today = date("Y-m-d");
        $status = 'Available';

        foreach ($reservation as $row) {
            $start_date = new DateTime($row->arrival);
            $end_date = new DateTime($row->departure);

            $interval = DateInterval::createFromDateString('1 day');
            $period = new DatePeriod($start_date, $interval, $end_date);

            foreach ($period as $dt) {
              // echo $dt->format("Y-m-d"). "<br>";
                if($today == $dt->format("Y-m-d") ){
                    $status = 'Occupied';
                    break;
                }
            }
        }

        if($this->status == 2){
            $status = 'Under Maintenance';
        }

        return $status;
        /*if($this->status == 0){
            return 'Available';
        }
        if($this->status == 1){
            return 'Occupied';
        }
        if($this->status == 2){
            return 'Under Maintenance';
        }*/
    }

    public function getTypeAttribute() {
        return $this->roomtype->typecode;
    }
    public function getBldgAttribute() {
        return $this->building->bldgname;
    }
    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
