<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReceiptPaymentCheque extends Model
{
    protected $table = 'receipt_payment_cheque';

    public function currency()
    {
        return $this->belongsTo('App\Currency')->first();
    }
}
