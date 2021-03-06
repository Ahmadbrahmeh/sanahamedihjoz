<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentCash extends Model
{
    protected $table = 'payment_cash';

    public function currency()
    {
        return $this->belongsTo('App\Currency')->first();
    }
}
