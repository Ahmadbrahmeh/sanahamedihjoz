<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Hall extends Model
{
    public function reservationHalls()
    {
        return $this->hasOne('App\ReservationHall')->first();
    }
}
