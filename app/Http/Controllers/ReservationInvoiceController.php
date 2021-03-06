<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Reservation;
use Carbon\Carbon;
use Config;

define("RESERVATION_STATMENT", "حجز ");

class ReservationInvoiceController extends Controller
{
    public function show($reservation_code) {
        $user = auth()->user();
        $organization = $user->manager()->organization();
        $reservation = Reservation::select("*")
            ->where("code", $reservation_code)
            ->where("organization_id",  $organization->id)->firstOrFail(); 
        $statements =  collect([]);    

        $reservation->halls()->map(function($reservationHall) use ($statements, $reservation) {
            $status_list = Config::get('constants.reservations.status.arabic');
            $status = $status_list[$reservation->status];
            $cost = $reservationHall->cost;
            
            $statement = ["title" => (RESERVATION_STATMENT . $reservationHall->hall()->name ), "cost" => $cost,
                'status' => $status, 'note' => $reservationHall->note];
            $statements->push($statement);
        });

        $reservation->services()->where("active", true)->map(function($reservationService) use ($statements, $reservation) {
            $status_list = Config::get('constants.reservations.status.arabic');
            $status = $status_list[$reservation->status];
            $statement = ["title" => $reservationService->service()->name , "cost" => $reservationService->cost,
                'status' => $status, 'note' => ''];
            $statements->push($statement);
        });
        $reservation->statements = $statements;
        
        return view('manager.pages.reservation-invoice')->with([
            "reservation" => $reservation,
        ]);
    }
}
