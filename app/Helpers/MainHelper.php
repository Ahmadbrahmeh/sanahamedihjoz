<?php

namespace App\Helpers;
use Str;
use App\ExhangeRate;
use App\Weekday;
use App\Reservation;
use Carbon\Carbon;
use Config;

class MainHelper
{
    public static function randomString($length = 16)
    {
        $pool = 'ABCDEFGHIJKLMNOPQRSTUVWXYZABCDEFGHIJKLMNOPQRSTUVWXYZ';
        return substr(str_shuffle(str_repeat($pool, $length)), 0, $length);
    }

    public static function convertCurrency($from, $to, $value) {
        if($from == $to) {
            return $value;
        }

        $organization = auth()->user()->manager()->organization();

        $exchange_rate_default = ExhangeRate::select("value")
            ->where('from', $from)
            ->where('to', $to)
            ->where('default', true)->first();

        $exchange_rate_organization = ExhangeRate::select("value")
            ->where('from', $from)
            ->where('to', $to)
            ->where('default', false)
            ->where('organization_id', $organization->id)->first();
        $exhange_rate = 0;

        if($exchange_rate_organization != null)
        {
            $exhange_rate  = $exchange_rate_organization->value;
        }
        else if($exchange_rate_default)
        {
            $exhange_rate = $exchange_rate_default->value; 
        }
        $new_value =  $exhange_rate * $value;

        return  $new_value;
    }

    public static function isWorkday($event_date) {
        $event_date = Carbon::createFromFormat("d/m/yy", $event_date);
        $organization = auth()->user()->manager()->organization();
        $organization_id =$organization->id;

        $working_days = Weekday::select('saturday', 'sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday')
            ->where("organization_id", $organization_id)->firstOrFail();
        $weekdays = collect(Config::get('constants.reservations.weekdays'));
        $working_days = collect($working_days)->filter()->all(); // all() filter the false values
        $off_days = $weekdays->diffKeys($working_days);

        return !$off_days->contains($event_date->dayOfWeek);
    }

    public static function isAvailableTime($start_time, $end_time) {
        $organization = auth()->user()->manager()->organization();
        $start_time = Carbon::parse($start_time)->format('H:i');
        $start_time = Carbon::createFromFormat("H:i", $start_time);

        $end_time = Carbon::parse($end_time)->format('H:i');
        $end_time = Carbon::createFromFormat("H:i", $end_time);

        $from_time = Carbon::parse($organization->from_time)->format('H:i');
        $to_time = Carbon::parse($organization->to_time)->format('H:i');
        $from_time = Carbon::createFromFormat('H:i', $from_time);
        $to_time = Carbon::createFromFormat('H:i', $to_time );

        if($to_time->isStartOfDay()) {
            $to_time = $to_time->subSecond()->addDay();
        }
        if($end_time->isStartOfDay()) {
            $end_time = $end_time->subSecond()->addDay();
        }

        $availabe_time = $start_time->between($from_time,  $to_time) && $end_time->between($from_time,  $to_time);
        return $availabe_time;
    }
    
    public static function recalculateReservation($reservation_id, $total_cost = 0) {
        $reservation = Reservation::find($reservation_id);
        $reservation->halls = $reservation->halls();
        $reservation->services = $reservation->services()->where("active", true);
        $reservation->payments = $reservation->payments();
        $halls_cost = $services_cost = $reservation->halls->sum("cost");
        $services_cost = $reservation->services->sum("cost");
        $total_cost = $halls_cost + $services_cost;
        $deposit_amount = $reservation->payments->sum("total");

        $reservationObject = Reservation::find($reservation_id);
        $reservationObject->total_cost = $total_cost;
        $reservationObject->deposit_amount =   $deposit_amount ;
        $reservationObject->remaining_amount = $total_cost - $deposit_amount;

        return $reservationObject->save();
    }

}