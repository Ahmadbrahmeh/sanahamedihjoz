<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    public function currency()
    {
        return $this->belongsTo('App\Currency')->first();
    }

    public function creator()
    {
        return $this->belongsTo('App\User', 'created_by')->first();
    }
    
    public function customer()
    {
        return $this->belongsTo('App\Customer')->first();
    }

    public function services()
    {
        return $this->hasMany("App\ReservationService")->get();
    }

    public function payments()
    {
        return $this->hasMany("App\Payment")->orderBy("created_at", "asc")->get();
    }
    
    public function notes()
    {
        return $this->hasMany("App\ReservationNote")->orderBy("sequence", "asc")->get();
    }

    public function terms()
    {
        return $this->hasMany("App\ReservationTerm")->orderBy("sequence", "asc")->get();
    }

    public function events()
    {
        return $this->hasMany("App\Event")->get();
    }

    public function halls()
    {
        return $this->hasMany("App\ReservationHall")->get();
    }

    public function eventlists()
    {
        return $this->hasMany("App\ReservationEventList")->orderBy("sequence", "asc")->get();
    }
}
