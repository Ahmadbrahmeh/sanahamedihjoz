<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReservationService extends Model
{
    public $timestamps = true;

    public function service()
    {
        return $this->belongsTo('App\Service')->first();
    }
}
