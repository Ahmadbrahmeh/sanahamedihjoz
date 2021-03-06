<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Reservation;
use Carbon\Carbon;
use Config;
use App\Address;

define("RESERVATION_STATMENT", "حجز ");
define("SERVICE_STATMENT", "خدمة ");
define("PAYMENT_STATMENT", "سند قبض ");
define("DEPIT", 1);
define("CREDIT", -1);


class ReservationAccountInvoiceController extends Controller
{
    private $balance = 0;

    public function show($reservation_code) {
        $user = auth()->user();
        $organization = $user->manager()->organization();
        $reservation = Reservation::select("*")
            ->where("code", $reservation_code)
            ->where("organization_id",  $organization->id)->firstOrFail(); 
        $customer = $reservation->customer();
        $customer->address = $this->getCity($customer->address_id);
        $reservation->customer = $customer;

        $status_list = Config::get('constants.reservations.status.arabic');
        $reservation->status = $status_list[$reservation->status];
        $transactions =  collect([]); 
      
        foreach($reservation->halls() as $reservationHall) {
            $depit = $reservationHall->cost;
            $credit = 0;
            if($transactions->isEmpty() && $reservation->include_all_costs) {
                $depit = $reservation->total_cost;
            }
            $currentBalance = $this->moveBalance($depit, DEPIT);
            $transaction = ["title" => (RESERVATION_STATMENT . $reservationHall->hall()->name ), "debit" => $depit,
                "credit" => $credit, 'balance' => $currentBalance, 'status' => $reservation->status, 'note' => $reservationHall->note,
                "date" => $reservationHall->created_at];
            $transactions->push($transaction);
        }
        $reservation->services = $reservation->services()->where("active", true);
        foreach($reservation->services as $reservationService) {
            $depit = $reservationService->cost;
            $credit = 0;
            $currentBalance = $this->moveBalance($reservationService->cost, DEPIT);
            $transaction = ["title" => (SERVICE_STATMENT . $reservationService->service()->name ), "debit" => $depit,
                "credit" => $credit, 'balance' => $currentBalance, 'status' => $reservation->status, 'note' => $reservationService->note,
                "date" => $reservation->created_at];
            $transactions->push($transaction);
        }

        foreach($reservation->payments() as $payment) {
            $credit = $payment->total;
            $depit = 0;
            $currentBalance = $this->moveBalance($payment->total, CREDIT);
            $transaction = ["title" => PAYMENT_STATMENT, "debit" => $depit, "credit" => $credit, 'balance' => $currentBalance,
                'status' => $reservation->status, 'note' => '', "date" => $payment->created_at];
            $transactions->push($transaction);
        }
        
        return view('manager.pages.account-statement')->with([
            "reservation" => $reservation,
            "organization" => $organization,
            "transactions" => $transactions
        ]);
    }

    private function getCity($id)
    {
        $addresses_chain = collect([]);
        $current_address = Address::where("id", $id)->first();
        while($current_address != null) {
            if($current_address->type == "city") {
                return $current_address->name;
            }
            $current_address = $current_address->parent();
        }
        return "";
    }

    private function moveBalance($value, $factor) {
        $this->balance = $this->balance + ($value * $factor);
        return $this->balance;
    }
}
