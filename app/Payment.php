<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    public function paymentsCash()
    {
        return $this->hasMany('App\PaymentCash')->get();
    }

    public function paymentsCheque()
    {
        return $this->hasMany('App\PaymentCheque')->get();
    }
}
