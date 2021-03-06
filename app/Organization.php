<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    public $timestamps = false;

    public function organizationCurrency()
    {
        return $this->hasOne('App\OrganizationCurrency')->first();
    }
	
	public function receipts()
    {
        return $this->hasMany('App\Receipt')->get();
    }
}
