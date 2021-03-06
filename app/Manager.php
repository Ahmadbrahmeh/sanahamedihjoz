<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Manager extends Model
{
    public function organization()
    {
        return $this->belongsTo('App\Organization')->first();
    }
}
