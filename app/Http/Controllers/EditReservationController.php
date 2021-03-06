<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Customer;
use App\Eventlist;
use App\Service;
use App\Hall;
use App\Event;
use App\Reservation;
use App\ReservationNote;
use App\ReservationTerm;
use App\ReservationService;
use App\ReservationEventList;
use App\ReservationHall;
use Validator;
use DB;
use Carbon\Carbon;
use App\Helpers\TimeHelper;
use App\Helpers\MainHelper;
use Config;
use Exception;

define("INITIAL_STATUS", Config::get('constants.reservations.status.mapping.initial'));
define("PENDING_STATUS", Config::get('constants.reservations.status.mapping.pending'));
define("CANCELED_RESERVATION", Config::get('constants.reservations.status.mapping.cancel'));
define("DELAYED_RESERVATION", Config::get('constants.reservations.status.mapping.delay'));

class EditReservationController extends Controller
{
    public function show($reservation_id)
    {
        $user = auth()->user();
        $organization = $user->manager()->organization();
        $reservation = Reservation::select("*")
            ->where("id", $reservation_id)
            ->where("organization_id",  $organization->id)->firstOrFail();
        $reservation->disable_delay = ($reservation->status != DELAYED_RESERVATION);

        return view("manager.pages.reservation-edit")->with([
            "reservation" =>  $reservation
        ]);
    }

    public function showUpdateDate($reservation_id)
    {
        $user = auth()->user();
        $organization = $user->manager()->organization();
        $reservation = Reservation::select("*")
            ->where("id", $reservation_id)
            ->where("organization_id",  $organization->id)->firstOrFail();
        $event =  $reservation->events()->where("type", 1)->first();
        $prepare_time =  $reservation->events()->where("type", 0)->first();
        $event->from_time = Carbon::parse($event->from_time)->format('H:i');
        $event->to_time = Carbon::parse($event->to_time)->format('H:i');

        $prepare_time->from_time = Carbon::parse($prepare_time->from_time);
        $prepare_time->to_time = Carbon::parse($prepare_time->to_time);
        $prepare_duration = $prepare_time->to_time->diffInMinutes($prepare_time->from_time);

        $prepare_duration = TimeHelper::minutesConvertToTime($prepare_duration);
        $organization->prepare_duration_minutes = $prepare_duration['minutes'];
        $organization->prepare_duration_hours = $prepare_duration['hours'];

        return view("manager.pages.reservation-update-date")->with([
            "event" =>  $event,
            'reservation_id' => $reservation_id,
            'organization' => $organization,
        ]);
    }

    public function updateDate(Request $request, $reservation_id)
    {
        try {
            if(is_null($request->start_time) || is_null($request->end_time)) {
                throw new Exception("null value");
            }
            $request->start_time = Carbon::parse($request->start_time)->format('g:i a');
            $request->end_time = Carbon::parse($request->end_time)->format('g:i a');
            $request['start_time'] = $request->start_time;
            $request['end_time'] = $request->end_time;
        } catch (\Exception $e) {
            return redirect()->back();
        }

        $validator = Validator::make($request->all(), [
            'start_time' => 'required|string|date_format:g:i a',
            'end_time' => 'required|string|date_format:g:i a',
            'event_date' => 'required|string|date_format:yy-m-d',
            'preparation_minutes' => 'required|integer|min:0|max:59',
            'preparation_hours' => 'required|integer|min:0|max:23',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with(['success' => 'false', "message" => "فشلت عملية تأجيل الحجز "]);
        }

        $user = auth()->user();
        $organization = $user->manager()->organization();
        $reservation = Reservation::select("*")
            ->where("id", $reservation_id)
            ->where("organization_id",  $organization->id)->firstOrFail();

        $request['event_date'] = Carbon::parse($request->event_date)->format('d/m/yy');

        try {
            DB::transaction(function() use ($request, $reservation) {
                $events =  $reservation->events()->where("type", 1);
                $isWorkday = MainHelper::isWorkday($request->event_date);
                $isAvailable = MainHelper::isAvailableTime($request->start_time, $request->end_time);
                if(!($isWorkday && $isAvailable)) {
                    throw new Exception("null value");
                }
                foreach($events as $event) {
                    $status = $this->updateEvent($request, $event, $reservation);
                    if(!$status) {
                        throw new Exception("faield update event");
                    }
                }
                $reservation_status = INITIAL_STATUS;
                if($reservation->payments()->count() > 0) {
                    $reservation_status = PENDING_STATUS;
                }
                $reservation->status = $reservation_status;
                $reservation->save();
            });
        }
        catch (Exception $e) {
            return redirect()->back()
            ->with(['success' => 'false', "message" => "فشلت عملية التأجيل, الرجاء التأكد من ان مواعيد الحجز متاحة"])
            ->withInput();
        }
    
            return redirect()->back()
                ->with(['success' => 'true', "message" => "تم تأجيل وقت الحجز بنجاح"]);
    }

    private function updateEvent($request, $event, $reservation) {
        $reservation_date = Carbon::createFromFormat("d/m/yy", $request->event_date);
        $event->from_time = Carbon::parse($request->start_time)->format('H:i');
        $event->to_time = Carbon::parse($request->end_time)->format('H:i');
        $event->reservation_date = $reservation_date;
        if(!$this->checkAvailableTime($event->from_time, $event->to_time, $event->hall_id, $reservation->id, $reservation_date, $event->id)) {
            throw new Exception("The event duration [$event->from_time, $event->to_time] overlaping with other events");
        }
        $status = $this->updatePrepareDurationTime($request,  $event, $reservation->id, $reservation_date);
        return $event->save();
    }

    private function checkAvailableTime($start_time, $end_time, $hall_id, $reservation_id, $reservation_date, $event_id) {
        $organization = auth()->user()->manager()->organization();
        $start_time = Carbon::createFromFormat('H:i', $start_time)->format("H:i");
        $end_time = Carbon::createFromFormat('H:i', $end_time)->subSecond()->format("H:i");

        $count = Event::select("events.id","events.*")
            ->join('reservations', 'reservations.id', 'events.reservation_id')
            ->where("organization_id", $organization->id)
            ->where("hall_id", $hall_id)
            ->where("events.id", "!=", $event_id)
            ->where("reservation_date", $reservation_date->toDateString())
            ->whereNotIn("reservations.status", [DELAYED_RESERVATION, CANCELED_RESERVATION])
            ->where(function ($query) use ($start_time, $end_time) {
                $query->whereRaw("CAST('$start_time' as Time) between DATEADD(minute, 1, from_time)  and DATEADD(minute, -1, to_time)")
                ->orWhereRaw("CAST('$end_time' as Time) between DATEADD(minute, 1, from_time)  and DATEADD(minute, -1, to_time)");
            })->get();

        $availabe = ($count->count() == 0);
        return $availabe;
    }

    private function updatePrepareDurationTime($request, $event,  $reservation_id, $reservation_date) {
        $prepare_duration = TimeHelper::timeConvertToMinutes($request->preparation_hours, $request->preparation_minutes);
        $prepare_event = $event->relatedPrepareEvent();
        if($prepare_duration == 0 && $prepare_event == null) {
            return false;
        }
        
         if($prepare_event == null) {
            $prepare_event = new Event(); 
            $prepare_event->reservation_id = $reservation_id;
            $prepare_event->type = 0;
            $prepare_event->related_id = $related_id;
            $prepare_event->hall_id = $event->hall_id;
        }

        $to_time = Carbon::createFromFormat('H:i', $event->to_time);
        $prepare_duration_start = clone $to_time;
        $prepare_duration_end = $to_time->addMinutes($prepare_duration);
        if($prepare_duration_start->isStartOfDay()) {
             return false;
        }

        if($prepare_duration != 0 && !$this->checkAvailableTime($prepare_duration_start->format('H:i'), $prepare_duration_end->format('H:i'), $event->hall_id, $reservation_id, $reservation_date,  $prepare_event->id)){
            throw new Exception("The prepare duration [$event->from_time, $event->to_time] overlaping with other events");
        }

        $prepare_event->from_time =  $prepare_duration_start;
        $prepare_event->to_time = $prepare_duration_end;
        $prepare_event->reservation_date = $reservation_date;
        return $prepare_event->save();
    }
}
