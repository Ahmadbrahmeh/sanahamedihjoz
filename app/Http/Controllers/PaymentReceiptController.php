<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Reservation;
use App\Payment;

class PaymentReceiptController extends Controller
{
    public function show($reservation_code, $payment_id) 
    {
        $user = auth()->user();
        $organization = $user->manager()->organization();
        $reservation = Reservation::select("*")
            ->where("code", $reservation_code)
            ->where("organization_id",  $organization->id)->firstOrFail();
        
        $payment = Payment::select("*")
            ->where("id", $payment_id)
            ->where("reservation_id", $reservation->id)->firstOrFail();
        
        return view("manager.pages.payment-receipt")->with([
            "organization" => $organization,
            "reservation" => $reservation,
            "payment" => $payment,
        ]);
    }
}
