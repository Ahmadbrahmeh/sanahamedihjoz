<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    public function hall()
    {
        return $this->belongsTo('App\Hall')->first();
    }

    public function relatedPrepareEvent()
    {
        return $this->hasOne('App\Event', 'related_id')->first();
    }

}
