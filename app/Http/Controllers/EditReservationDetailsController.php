<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use App\Customer;
use App\Eventlist;
use App\Service;
use App\Hall;
use App\Address;
use App\Event;
use App\Reservation;
use App\ReservationNote;
use App\ReservationTerm;
use App\ReservationService;
use App\ReservationEventList;
use App\ReservationHall;
use Validator;
use DB;
use App\Helpers\TimeHelper;
use App\Helpers\MainHelper;
use Config;
use Exception;
use Carbon\Carbon;
use Illuminate\Support\Str;


class EditReservationDetailsController extends Controller
{
    public function show($reservation_id) {
        $user = auth()->user();
        $organization = $user->manager()->organization();
        $organization_id = $organization->id;

        $reservation = Reservation::select("*")
            ->where("id", $reservation_id)
            ->where("organization_id",  $organization->id)->firstOrFail();

        $default_currency = $reservation->currency();
		$customers = Customer::select("name", "id")
            ->where("organization_id",  $organization_id)->get();
        $eventlists = EventList::select("name", "id")->where("organization_id", $organization_id)->get();
		
        $services = Service::select("id", "name", "price", "currency_id")
            ->where("organization_id", $organization_id)
            ->where("mark_for_delete", false)->get();

        $halls = Hall::select("id", "name", "price", "capacity")
            ->where("organization_id",  $organization_id)
            ->where("mark_for_delete", false)->get();

        $services = $services->map(function($service) use ($default_currency) {
            $service->price = MainHelper::convertCurrency($service->currency_id, $default_currency->id, $service->price);
            return $service;
        });
        $reservation->terms = $reservation->terms()->where("mark_for_delete", false);
        $reservation->notes = $reservation->notes()->where("mark_for_delete", false);
        $reservation->eventlists = $reservation->eventlists()->where("mark_for_delete", false);
        $reservation->services = $reservation->services()->where("active", true)->keyBy("service_id");
        $defaultHall =  $reservation->halls()->where("mark_for_delete", false)->first();
        $reservation->hall =  $defaultHall;
        $reservation->hall->name = $defaultHall->hall()->name;
        
        $maxLimit['notes'] = Config::get('constants.reservations.notes.max');
        $maxLimit['terms'] = Config::get('constants.reservations.terms.max');
        $maxLimit['halls'] = $halls->count() - 1;
        $maxLimit['eventlist'] = Config::get('constants.reservations.eventlist.max');

        $count['notes'] = $reservation->notes->count();
        $count['terms'] = $reservation->terms->count();
        $count['halls'] = 0;
        $count['eventlist'] = $reservation->eventlists->count();
        
        $services = $services->map(function($service) use ($reservation) {
            $service->selected = "false";
            if($reservation->services->contains("service_id", $service->id)) {
                $service->selected = "true";
                $service->price = $reservation->services->get($service->id)->cost;
            }
            return $service;
        });
       
        $reservation->currency = $default_currency;
        $reservation->maxLimit = $maxLimit;
        $reservation->count =  $count;

        $prepare_time =  $reservation->events()->where("type", 0)->first();
        $prepare_time->from_time = Carbon::parse($prepare_time->from_time);
        $prepare_time->to_time = Carbon::parse($prepare_time->to_time);
        $prepare_duration = $prepare_time->to_time->diffInMinutes($prepare_time->from_time);

        $prepare_duration = TimeHelper::minutesConvertToTime($prepare_duration);
        $organization->prepare_duration_minutes = $prepare_duration['minutes'];
        $organization->prepare_duration_hours = $prepare_duration['hours'];

		$cities = Address::where("type", "city")->where("mark_for_delete", false)->get();
        $events = Event::where('reservation_id', $reservation->id)->where("type", 1)->get();

        $events = $events->map(function($event) {
            $from_time =  explode(":",  $event->from_time);
            $event->from_time_hours = $from_time[0];
            $event->from_time_minutes = $from_time[1];

            $to_time =  explode(":",  $event->to_time);
            $event->to_time_hours = $to_time[0];
            $event->to_time_minutes = $to_time[1];
            return $event;
        });

        return view('manager.pages.reservation-update')->with(
            [
			    "customers" => $customers,
                "eventlists" => $eventlists,
                "services" => $services,
                "halls" => $halls,
                'reservation' => $reservation,
				'organization' => $organization,
				'cities' => $cities,
				'events' => $events
                ]);
        }

    public function update(Request $request, $reservation_id) {
        $validator = Validator::make($request->all(), [
            'price' => 'nullable|numeric|min:1',
            'hall.*.id' => 'required|string|exists:reservation_halls,id',
            'hall.*.note' => 'nullable|string',
            'hall.*.capacity' => 'nullable|numeric|min:1',
            'hall.*.person_price' => 'nullable|numeric|min:1',
            'note.*.id' => 'nullable|string|exists:reservation_notes,id',
            'note.*.value' => 'nullable|string|min:1',
            'term.*.id' => 'nullable|string|exists:reservation_terms,id',
            'term.*.value' => 'nullable|string|min:1',
            'eventlist.*.question' => 'nullable|string|min:1',
            'eventlist.*.answer' => 'nullable|string|min:1',
            'pay_type' => 'required|string|in:type1,type2',
            'service.*.id' => 'nullable|string|exists:services,id',
            'service.*.price' => 'nullable|numeric|min:0',
            // 'start_time' => 'required|string|date_format:G:i',
            // 'end_time' => 'required|string|date_format:G:i'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->with(['success' => 'false', "message" => "فشلت عملية تحديث معلومات الحجز "]);;
        }

		$events = Event::where(function ($query) use($request){
				$query->
				where(function ($query0) use($request){
					$query0->where('from_time', '>=' , $request->start_time)
					->where('to_time', '>=' , $request->end_time)
					->where('from_time', '<' , $request->end_time);
				})
				->orWhere(function ($query1) use($request){
					$query1->where('from_time', '>=' , $request->start_time)
					->where('to_time', '<=' , $request->end_time);
				})
				->orWhere(function ($query2) use($request){
					$query2->where('from_time', '<=' , $request->start_time)
					->where('to_time', '<=' , $request->end_time)
					->where('to_time', '>' , $request->start_time);
                });
            })->where('reservation_date','=', $request->event_date)->get();
				
        $user = auth()->user();
        $organization = $user->manager()->organization();
        $organization_id = $organization->id;
        $reservation = Reservation::select("*")
            ->where("id", $reservation_id)
            ->where("organization_id",  $organization->id)->firstOrFail();
			
        $events2 = Event::where('reservation_id',$reservation->id)->get();

        if($events->count() > 0) {
            $checker = true;
            foreach($events as $event) {
                if($events2->first()->id == $event->id) {
                    $checker = false;
                    break;
                }
            }
            if($checker) {
                return redirect()->back()
                    ->with(['success' => 'false', "message" => "فشلت عملية الحجز, الرجاء التأكد من ان مواعيد الحجز متاحة"])
                    ->withInput();
            }
        }

        try {
            DB::transaction(function() use ($request, $reservation, $organization_id) {
                $event =  $reservation->events()->where("type", 1)->first();
                $customer = Customer::select("name", "id")
                    ->where("id",  $request->customer)
                    ->where("organization_id",  $organization_id)->first();

                $status = $this->updateEvent($request, $event, $reservation);
                if(!$status) {
                    return redirect()->back()
                        ->with(['success' => 'false', "message" => "فشلت عملية التأجيل, الرجاء التأكد من ان مواعيد الحجز متاحة"])
                        ->withInput();
                }
				
                $reservation->customer_id = $customer->id;
                $reservation->customer_name =  $customer->name;

                $reservation->include_all_costs = ($request->pay_type == "type1");
                $total_cost = $this->calculateCost($request);
                $reservation->total_cost = $total_cost;
                $reservation->remaining_amount = $total_cost;
				$reservation->save();

                if($request->has('hall')) {
                    $reservationHallList = $reservation->halls()->keyBy("id");
                    foreach($request->get('hall') as $hall) {
                        $reservationHall = $reservationHallList->get($hall['id']);
                        $reservationHall->note = $hall['note'];
                        if($reservation->include_all_costs) {
                            $reservationHall->cost = $hall['price'];
                        } else {
                            $reservationHall->cost = ($hall['person_price'] * $hall['capacity']);
                            $reservationHall->persons = $hall['capacity'];
                            $reservationHall->cost_per_person = $hall['person_price'];
                        }
                        $reservationHall->save();
                    }
                }

                $affected = DB::table('reservation_services')
                    ->where('reservation_id', $reservation->id)
                    ->update(['active' => false]);
                if($request->has('service')) {
                    $services = $reservation->services()->keyBy("service_id");
                    $newServices = collect();

                    foreach($request->get('service') as $service) {
                        if($services->contains("service_id", $service['id'])) {
                            $reservationService = $services->get($service['id']);
                            $cost = $service['price'];
                            $reservationService->cost = $service['price'];
                            $reservationService->active = true;
                            $reservationService->save();
                        } else {
                            $cost = $service['price'];
                            $reservationService = ['service_id' => $service['id'], 'cost' => $cost, 'reservation_id' => $reservation->id];
                            $newServices->push($reservationService);
                        }
                    }
                    ReservationService::insert($newServices->all());
                }

                $affected = DB::table('reservation_notes')
                    ->where('reservation_id', $reservation->id)
                    ->update(['mark_for_delete' => true]);

                if($request->has('note')) {
                    $notes = $reservation->notes()->keyBy("id");
                    $count = $notes->max("sequence") + 1;
                    $newNotes = collect();
                    foreach($request->get('note') as $note) {
                        if(Arr::exists($note, 'id') && $notes->contains($note['id'])) {
                            $reservationNote = $notes->get($note['id']);
                            $reservationNote->value = $note['value'];
                            $reservationNote->mark_for_delete = false;
                            $reservationNote->save();
                        } else {
                            $reservationNote = ['sequence' => $count, 'value' =>  $note['value'], 'reservation_id' => $reservation->id];
                            $count++;
                            $newNotes->push($reservationNote);
                        }
                    }
                    ReservationNote::insert($newNotes->all());
                }

                $affected = DB::table('reservation_terms')
                    ->where('reservation_id', $reservation->id)
                    ->update(['mark_for_delete' => true]);

                if($request->has('term')) {
                    $terms = $reservation->terms()->keyBy("id");
                    $count = $terms->max("sequence") + 1;
                    $newTerms = collect();
                    foreach($request->get('term') as $term) {
                        if(Arr::exists($term, 'id') && $terms->contains($term['id'])) {
                            $reservationTerm = $terms->get($term['id']);
                            $reservationTerm->value = $term['value'];
                            $reservationTerm->mark_for_delete = false;
                            $reservationTerm->save();
                        } else {
                            $reservationTerm = ['sequence' => $count, 'value' =>  $term['value'], 'reservation_id' => $reservation->id];
                            $count++;
                            $newTerms->push($reservationTerm);
                        }
                    }
                    ReservationTerm::insert($newTerms->all());
                }

                $affected = DB::table('reservation_event_list')
                    ->where('reservation_id', $reservation->id)
                    ->update(['mark_for_delete' => true]);
                if($request->has('eventlist')) {
                    $eventlists = $reservation->eventlists()->keyBy("id");
                    $count = $eventlists->max("sequence") + 1;
                    $newEventlists = collect();
                    foreach($request->get('eventlist') as $eventlist) {
                        if(Arr::exists($eventlist, 'id') && $eventlists->contains($eventlist['id'])) {
                            $reservationEventlist = $eventlists->get($eventlist['id']);
                            $reservationEventlist->question = $eventlist['question'];
                            $reservationEventlist->answer = $eventlist['answer'];
                            $reservationEventlist->mark_for_delete = false;
                            $reservationEventlist->save();
                        } else {
                            $reservationEventlist = ['sequence' => $count, 'question' =>  $eventlist['question'], 'answer' =>  $eventlist['answer'], 'reservation_id' => $reservation->id];
                            $count++;
                            $newEventlists->push($reservationEventlist);
                        }
                    }
                    ReservationEventlist::insert($newEventlists->all());
                }
                $status = false;
                if($reservation->include_all_costs) {
                    $status =  MainHelper::recalculateReservation($reservation->id, $request->price);
                } else {
                    $status =  MainHelper::recalculateReservation($reservation->id);
                }                
                if(!$status) {
                    throw new Exception("Reservation [$reservation->id] didn't updated");
                }
            });
        } catch (Exception $e) {
             return redirect()->back()
               ->with(['success' => 'false', "message" => "فشلت عملية تحديث معلومات الحجز"])
               ->withInput();
         }

         return redirect()->back()
            ->with(['success' => 'true', "message" => "تم تحديث معلومات الحجز بنجاح"]);
    }

    private function calculateCost($request) {
        $halls_cost = 0;
        $services_cost = 0;
        if($request->has('hall')) {
            $halls = collect($request->hall);
            $halls_cost = $halls->sum(function($hall) use ($request) {
                $include_all_costs = ($request->pay_type == "type1");
                return ($include_all_costs)? $hall['price'] : ($hall['capacity'] * $hall['person_price']);
            });
        }
        
        if($request->has('service')) {
            $services = collect($request->service);
            $services_cost = $services->sum("price");
        }
        $total_cost = $halls_cost + $services_cost;
        return $total_cost;
    }

    private function updateEvent($request, $event, $reservation) {
        $reservation_date = Carbon::createFromFormat("Y-m-d", $event->reservation_date);
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
        $end_time = Carbon::createFromFormat('H:i', $end_time)->format("H:i");
        $reservation =  Reservation::where('id', $reservation_id)->first();
        $primary_event = $reservation->events()->where("type", 1)->first();

        $count = Event::select("events.id","events.*")
            ->join('reservations', 'reservations.id', 'events.reservation_id')
            ->where("organization_id", $organization->id)
            ->where("hall_id", $hall_id)
            ->where("events.id", "!=", $event_id)
            ->where("events.id", "!=", $primary_event->id)
            ->where("reservation_date", $reservation_date->toDateString())
            ->where(function ($query) use ($start_time, $end_time){
                $query->whereRaw("CAST('$start_time' as Time) > from_time and CAST('$start_time' as Time) < to_time")
                    ->orWhereRaw("CAST('$end_time' as Time) > from_time and CAST('$end_time' as Time) < to_time");
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
