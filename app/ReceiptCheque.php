<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReceiptCheque extends Model
{
    protected $table = 'receipt_cheque';

    public function currency()
    {
        return $this->belongsTo('App\Currency')->first();
    }
}
