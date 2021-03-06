<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReceiptCash extends Model
{
    protected $table = 'receipt_cash';

    public function currency()
    {
        return $this->belongsTo('App\Currency')->first();
    }
}
