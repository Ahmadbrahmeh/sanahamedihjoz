<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrganizationCurrency extends Model
{
    public function currency()
    {
        return $this->belongsTo('App\Currency')->first();
    }
}