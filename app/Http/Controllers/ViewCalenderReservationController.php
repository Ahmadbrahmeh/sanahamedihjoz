<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Config;
use Validator;
use App\Event;
use App\Weekday;
use App\Hall;
use Carbon\Carbon;

define("CANCELED_RESERVATION", Config::get('constants.reservations.status.mapping.cancel'));
define("DELAYED_RESERVATION", Config::get('constants.reservations.status.mapping.delay'));

class ViewCalenderReservationController extends Controller
{
    
    public function show(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'hall' => 'nullable|string|exists:halls,id'
        ]);

        if ($validator->fails()) {
            return redirect()->route("reservation-calender");
        }
        $organization_id = auth()->user()->manager()->organization_id;
        $organization = auth()->user()->manager()->organization();
        $organization->from_time = $this->getCalenderTime($organization->from_time);
        $organization->to_time = $this->getCalenderTime($organization->to_time);
        $organization_days = $this->getDays($organization_id);
        $organization->work_days = $organization_days["work"];
        $organization->off_days = $organization_days["off"];

        $selected_hall = Hall::select("name", "id")
            ->where("organization_id", $organization_id)
            ->where("id", $request->query("hall"))
            ->where("mark_for_delete", false)
            ->first();

        $organization_halls = Hall::select("name", "id")
            ->where("mark_for_delete", false)
            ->where("organization_id", $organization_id)
            ->orderBy('created_at', 'desc')
            ->get();

        $default_hall = $selected_hall ??  $organization_halls->first();
        $default_hall_id  = ($default_hall != null) ? $default_hall->id : -1;
        $organization->hall = $default_hall;
        $events = Event::select("events.id", "from_time", "to_time", "reservation_date", "type", "events.reservation_id",
            "reservations.title as title", "reservations.customer_name as customer_name", "reservations.code as reservations_code")
            ->where("reservations.organization_id", $organization_id)
            ->where("events.hall_id", $default_hall_id)
            ->join("reservations", "reservations.id", "events.reservation_id")
            ->whereNotIn("reservations.status", [DELAYED_RESERVATION, CANCELED_RESERVATION])
            ->get();

        $events = $events->map(function($event) {
            $event->title = $event->customer_name;
            if($event->type == 0) {
                $event->title = "وقت استراحة";
            }
            $event->from_time = $this->getCalenderTime($event->from_time);
            $event->to_time = $this->getCalenderTime($event->to_time);
            $event->groupId = $event->type == 1 ? "A" : "B";
           return $event;
        })->toJson();

        return view('manager.pages.reservation-calender')
            ->with(["events" => $events,
                    "halls" => $organization_halls,
                    "organization" => $organization]);
    }

    private function getCalenderTime($time) {
        $formated_time = Carbon::parse($time)->format('H:i');
        $formated_time = Carbon::createFromFormat('H:i', $formated_time);

        if($formated_time->isStartOfDay()) {
            $formated_time = $formated_time->subSecond();
        }

       return $formated_time->format("H:i");
    }
    // private function getCalenderTime($time) {
    //     $formated_time = Carbon::parse($time)->format('H:i');
    //     if ($formated_time === '00:00') {
    //         $formated_time = '24:00';
    //     }
    //     return $formated_time;
    // }

    private function getDays($organization_id) {
        $working_days = Weekday::select('saturday', 'sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday')
            ->where("organization_id", $organization_id)->firstOrFail();
        $weekdays = Config::get('constants.organizations.weekdays');
        $organization_work_days = "";
        $organization_off_days = "";
        $separator1 = "";
        $separator2 = "";
        foreach($weekdays as $key => $day)
        {
            if($working_days[$key])
            {
                $organization_work_days .= $separator1."".$key;
                $separator1 = ",";
            }
            else {
                $organization_off_days .= $separator2."".$key;
                $separator2 = ",";
            }
        }

        return ["work" => $organization_work_days, "off" => $organization_off_days];
    }
}
