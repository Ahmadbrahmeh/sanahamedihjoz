<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Reservation;
use App\Address;
use Config;
use Carbon\Carbon;
use Str;

define("FINISHED_RESERVATION", Config::get('constants.reservations.status.mapping.finished'));
define("CANCELED_RESERVATION", Config::get('constants.reservations.status.mapping.cancel'));
define("DELAYED_RESERVATION", Config::get('constants.reservations.status.mapping.delay'));

class ViewReservationController extends Controller
{
    public function show($reservation_code) {
        $user = auth()->user();
        $organization = $user->manager()->organization();
        $reservation = Reservation::select("*")
            ->where("code", $reservation_code)
            ->where("organization_id",  $organization->id)->firstOrFail();

        $addresses_chain = collect([]);
        $current_address = Address::where("id", $reservation->customer()->address_id)->first();

        while($current_address != null) 
        {
            $addresses_chain = $addresses_chain->prepend($current_address->name);
            $current_address = $current_address->parent();
        }

        $reservation->disable_cancel = ($reservation->status == FINISHED_RESERVATION || $reservation->status == CANCELED_RESERVATION );
        $reservation->disable_delay = ($reservation->status == FINISHED_RESERVATION || $reservation->status == CANCELED_RESERVATION ||  $reservation->status == DELAYED_RESERVATION);
        $status_list = Config::get('constants.reservations.status.arabic');
        $reservation->status = $status_list[$reservation->status];
        /* Fix it */
        // $reservation->created_at =  date('d/m/Y h:i', strtotime($reservation->created_at));
        // $reservation->updated_at =  date('d/m/Y h:i', strtotime($reservation->updated_at));
        $reservation->customer_address = $addresses_chain->implode(" - ");
        $reservation->total_cost .= " " . $reservation->currency()->name;
        $reservation->deposit_amount .= " " . $reservation->currency()->name;
        $reservation->remaining_amount .= " " . $reservation->currency()->name;
        $reservation->services = $reservation->services()->where("active", true)->map( function($reservation_service) {
            return $reservation_service->service()->name;
        })->implode(", ");

        $reservation->events = $reservation->events()->where("type", 1)->map(function($event) {
            $from_time = Carbon::parse($event->from_time)->format('g:i a');
            $to_time = Carbon::parse($event->to_time)->format('g:i a');
            $event->from_time = $this->replaceToArabicTime($from_time);
            $event->to_time = $this->replaceToArabicTime($to_time);
            return $event;
        });
        
        return view('manager.pages.reservation-view')->with([
            "reservation" => $reservation,
        ]);
    }

    private function replaceToArabicTime($time) {
        $time = strtoupper($time);
        $time =  Str::replaceLast('AM', 'ص', $time);
        $time =  Str::replaceLast('PM', 'م', $time);
        return $time;
    }
}
