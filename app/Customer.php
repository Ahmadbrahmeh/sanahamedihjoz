<?php

namespace App;
  
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    public function receipts()
    {
        return $this->hasMany('App\Receipt')->get(); 

    }
  
    public function receiptPayments()
    {
        return $this->hasMany("App\ReceiptPayment")->orderBy("created_at", "asc")->get();
    }
}
