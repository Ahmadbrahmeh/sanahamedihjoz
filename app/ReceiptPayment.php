<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReceiptPayment extends Model
{
    public function receiptsCash()
    {
        return $this->hasMany('App\ReceiptPaymentCash')->get();
    }

    public function receiptsCheque()
    {
        return $this->hasMany('App\ReceiptPaymentCheque')->get();
    }
	
	public function organization()
    {
        return $this->belongsTo('App\Organization')->first();
    }

    public function customer()
    {
        return $this->belongsTo('App\Customer')->first();
    }

    public function supplier()
    {
        return $this->belongsTo('App\Supplier')->whereNotNull("id")->first();
    }
    public function employee()
    {
        return $this->belongsTo('App\Employee')->whereNotNull("id")->first();
    }
}
