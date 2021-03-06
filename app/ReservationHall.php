<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReservationHall extends Model
{
    public function hall()
    {
        return $this->belongsTo('App\Hall')->first();
    }
}
