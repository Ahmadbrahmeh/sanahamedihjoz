<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReceiptPaymentCash extends Model
{
    protected $table = 'receipt_payment_cash';

    public function currency()
    {
        return $this->belongsTo('App\Currency')->first();
    }
}
