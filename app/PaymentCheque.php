<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentCheque extends Model
{
    protected $table = 'payment_cheque';

    public function currency()
    {
        return $this->belongsTo('App\Currency')->first();
    }
}
