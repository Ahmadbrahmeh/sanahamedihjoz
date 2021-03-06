<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\MainHelper;
use App\Helpers\TimeHelper;
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
use App\Weekday;
use App\ExhangeRate;
use App\ReservationExhangeRate;
use Validator;
use Carbon\Carbon;
use Str;
use Config;
use Exception;
use DB;
use App\Organization;
use App\Address;
use App\Helpers\UserHelper;

define("CANCELED_RESERVATION", Config::get('constants.reservations.status.mapping.cancel'));
define("DELAYED_RESERVATION", Config::get('constants.reservations.status.mapping.delay'));

class AddReservationController extends Controller
{
    public function show(Request $request) {
        $start_time = $this->replaceToArabicTime($request->start_time);
        $end_time =  $this->replaceToArabicTime($request->end_time);
        try {
            if(is_null($request->start_time) || is_null($request->end_time) || is_null($request->event_date)) {
                throw new Exception("null value");
            }
            $request->start_time = Carbon::parse($request->start_time)->format('g:i a');
            $request->end_time = Carbon::parse($request->end_time)->format('g:i a');
            $request->event_date = Carbon::parse($request->event_date)->format('d/m/Y');
            $request['start_time'] = $request->start_time;
            $request['end_time'] = $request->end_time;
            $request['event_date'] = $request->event_date;

        } catch (\Exception $e) {
            return redirect()->route('reservation-calender');
        }

        $validator = Validator::make($request->all(), [
            'hall' => 'required|string|exists:halls,id',
            'start_time' => 'required|string|date_format:g:i a',
            'end_time' => 'required|string|date_format:g:i a',
            'event_date' => 'required|string|date_format:d/m/Y',
        ]);

        if ($validator->fails()) {
            return redirect()->route('reservation-calender');
        }
        
        $organization = auth()->user()->manager()->organization();
        $organization_id =$organization->id;
        $default_currency = $organization->organizationCurrency()->currency();

        $customers = Customer::select("name", "id")
            ->where("organization_id",  $organization_id)->orderBy('id','desc')->get();
        $eventlists = EventList::select("name", "id")->where("organization_id", $organization_id)->get();

        $services = Service::select("id", "name", "price", "currency_id")
            ->where("organization_id", $organization_id)
            ->where("mark_for_delete", false)->get();

        $halls = Hall::select("id", "name", "price", "capacity")
            ->where("organization_id",  $organization_id)
            ->where("mark_for_delete", false)->get();

        $default_hall = Hall::select("id", "name", "price", "capacity")
                ->where("id", $request->hall)
                ->where("organization_id",  $organization_id)
                ->where("mark_for_delete", false)->firstOrFail();

        $services = $services->map(function($service) use ($default_currency) {
            $service->price = MainHelper::convertCurrency($service->currency_id, $default_currency->id, $service->price);
            return $service;
        });

        $maxLimit['notes'] = Config::get('constants.reservations.notes.max');
        $maxLimit['terms'] = Config::get('constants.reservations.terms.max');
        $maxLimit['halls'] = $halls->count() - 1;
        $maxLimit['eventlist'] = Config::get('constants.reservations.eventlist.max');
        
        $reservation = new Reservation();
        $reservation->event_date = $request->event_date;
        $reservation->start_time_localized = $this->replaceToArabicTime($request->start_time);
        $reservation->end_time_localized = $this->replaceToArabicTime($request->end_time);
        $reservation->start_time_value = $request->start_time;
        $reservation->end_time_value = $request->end_time;
        $reservation->hall = $default_hall;
        $reservation->currency = $default_currency;
        $reservation->maxLimit = $maxLimit;

        $prepare_duration = TimeHelper::minutesConvertToTime($organization->prepare_duration);
        $organization->prepare_duration_minutes = $prepare_duration['minutes'];
        $organization->prepare_duration_hours = $prepare_duration['hours'];
			
        $cities = Address::where("type", "city")->where("mark_for_delete", false)->get();

        $hall = Hall::where('id', $reservation->hall->id)
                    ->where('organization_id', UserHelper::getManagerOrganizationId())->first();

        return view('manager.pages.reservation-add')
            ->with([
                "customers" => $customers,
                "eventlists" => $eventlists,
                "services" => $services,
                "halls" => $halls,
                'reservation' => $reservation,
				'organization' => $organization,
				'cities' => $cities,
				'specificHall' => $hall,
            ]);
    }

    public function add(Request $request) {
        $validator = Validator::make($request->all(), [
            'customer' => 'required|string|exists:customers,id',
            'title' => 'required|string',
            'price' => 'nullable|numeric|min:1',
            'pay_type' => 'required|string|in:type1,type2',
            // 'start_time' => 'required|string|date_format:G:i',
            // 'end_time' => 'required|string|date_format:G:i',
            'event_date' => 'required|string|date_format:d/m/Y',
            'preparation_minutes' => 'required|integer|min:0|max:59',
            'preparation_hours' => 'required|integer|min:0|max:23',
            'hall.*.id' => 'required|string|exists:halls,id',
            'hall.*.note' => 'nullable|string',
            'hall.*.capacity' => 'nullable|numeric|min:1',
            'hall.*.person_price' => 'nullable|numeric|min:1',
            'note.*.value' => 'nullable|string|min:1',
            'term.*.value' => 'nullable|string|min:1',
            'eventlist.*.question' => 'nullable|string|min:1',
            'eventlist.*.answer' => 'nullable|string|min:1',
            'service.*.id' => 'nullable|string|exists:services,id',
            'service.*.price' => 'nullable|numeric|min:1',
        ]);
           
        if ($validator->fails()) {
            return redirect()->route('reservation-add');
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
				});})
				->where('reservation_date','=', Carbon::createFromFormat('d/m/Y', $request->event_date))->get();
    
				if($events->count() > 0) {
					return redirect()->back()
               ->with(['success' => 'false', "message" => "فشلت عملية الحجز, الرجاء التأكد من ان مواعيد الحجز متاحة"])
               ->withInput();
            }

        try {
            DB::transaction(function() use ($request) {
                $isWorkday = MainHelper::isWorkday($request->event_date);
                $isAvailable = MainHelper::isAvailableTime($request->start_time, $request->end_time);
                if(!($isWorkday && $isAvailable)) {
                    throw new Exception("null value");
                }
                $organization = auth()->user()->manager()->organization();
                $default_currency = $organization->organizationCurrency()->currency();
                
                $last_reservation = Reservation::latest()
                    ->where("organization_id",  $organization->id)->first();
                $number = 0;
                if(isset($last_reservation)) {
                    $number = $last_reservation->part_number;
                }
                $number = $number + 1;
                $customer = Customer::select("name", "id")
                    ->where("id",  $request->customer)
                    ->where("organization_id",  $organization->id)->first();
                $reservation = new Reservation();
                $reservation->customer_id = $customer->id;
                $reservation->customer_name =  $customer->name;
                $reservation->code = $this->generateReservationCode($number);
                $reservation->part_number = $number;
                $reservation->title = $request->title;
                $reservation->organization_id = $organization->id;
                $reservation->currency_id = $default_currency->id;
                $reservation->created_by = auth()->user()->id;
                $reservation->updated_by = auth()->user()->id;
                $reservation->include_all_costs = ($request->pay_type == "type1");
                $total_cost = $this->calculateCost($request);
                $reservation->total_cost = $total_cost;
                $reservation->remaining_amount = $total_cost;
                $reservation->save();

                if($request->has('hall')) {
                    $reservationHalls = collect();
                    foreach($request->get('hall') as $hall) {
                        $this->addEvent($request, $hall['id'], $reservation->id);
                        $reservationHall = new ReservationHall();
                        $reservationHall->hall_id = $hall['id'];
                        $reservationHall->note = $hall['note'];
                        $reservationHall->reservation_id = $reservation->id;
                        if($reservation->include_all_costs) {
                            $reservationHall->cost = $hall['price'];
                            $reservationHall->persons = $reservationHall->hall()->capacity;
                            $reservationHall->cost_per_person = $reservationHall->hall()->price;
                        } else {
                            $reservationHall->cost = ($hall['person_price'] * $hall['capacity']);
                            $reservationHall->persons = $hall['capacity'];
                            $reservationHall->cost_per_person = $hall['person_price'];
                        }
                        $reservationHall->save();
                    }
                }

                $this->addReservationExchangeRates($reservation);

                if($request->has('note')) {
                    $count = 1;
                    $reservationNotes = collect();
                    foreach($request->get('note') as $note) {
                        $reservationNote = ['sequence' => $count, 'value' => $note['value'], 'reservation_id' => $reservation->id];
                        $count++;
                        $reservationNotes->push($reservationNote);
                    }
                    ReservationNote::insert($reservationNotes->all());
                }

                if($request->has('term')) {
                    $count = 1;
                    $reservationTerms= collect();
                    foreach($request->get('term') as $term) {
                        $reservationTerm= ['sequence' => $count, 'value' => $term['value'], 'reservation_id' => $reservation->id];
                        $count++;
                        $reservationTerms->push($reservationTerm);
                    }
                    ReservationTerm::insert($reservationTerms->all());
                }

                if($request->has('eventlist')) {
                    $count = 1;
                    $eventlists = collect();
                    foreach($request->get('eventlist') as $eventlist) {
                        $reservationEventList= ['sequence' => $count, 'question' => $eventlist['question'], 'answer' => $eventlist['answer'], 'reservation_id' => $reservation->id];
                        $count++;
                        $eventlists->push($reservationEventList);
                    }
                    ReservationEventList::insert($eventlists->all());
                }

                if($request->has('service')) {
                    $services = collect();
                    foreach($request->get('service') as $service) {
                        $cost = $service['price'];
                        $reservationService = ['service_id' => $service['id'], 'cost' => $cost, 'reservation_id' => $reservation->id];
                        $services->push($reservationService);
                    }
                    ReservationService::insert($services->all());
                }

            });
        }
         catch (Exception $e) {
             return redirect()->back()
               ->with(['success' => 'false', "message" => "فشلت عملية الحجز, الرجاء التأكد من ان مواعيد الحجز متاحة"])
               ->withInput();
         }
         return redirect()->route('reservation-calender');
    }

    private function addEvent($request, $hall_id, $reservation_id) {
        $reservation_date = Carbon::createFromFormat("d/m/yy", $request->event_date);
        $event = new Event();
        $event->from_time = Carbon::parse($request->start_time)->format('H:i');
        $event->to_time = Carbon::parse($request->end_time)->format('H:i');
        $event->reservation_date = $reservation_date;
        $event->reservation_id = $reservation_id;
        $event->hall_id = $hall_id;
        if(!$this->checkAvailableTime($event->from_time, $event->to_time, $hall_id, $reservation_id, $reservation_date)) {
            throw new Exception("The event duration [$event->from_time, $event->to_time] overlaping with other events");
        }
        $status = $event->save();
        $this->AddPrepareDurationTime($request,  $event, $hall_id, $reservation_id, $reservation_date);
        return $status;
    }

    private function addReservationExchangeRates($reservation) {
        $organization = auth()->user()->manager()->organization();
        $default_currency = $organization->organizationCurrency()->currency();

        $exhange_rates = ExhangeRate::select("id", "default", "value", "organization_id", "to", "from")
            ->where("to", $default_currency->id)
            ->where(function ($query) use ($organization) {
                $query->where("organization_id", $organization->id)
                    ->where("default", false)
                    ->orWhere("default", true);
            })->get();
        $default_exhage_rates = $exhange_rates->where("default", true)->keyBy("from");
        $primary_exhange_rates = $exhange_rates->where("default", false)->keyBy("from");
        $exhange_rate_filiterd= $primary_exhange_rates->map(function($rate) use ($default_exhage_rates) {
                $default_exhage_rates->pull($rate->from);
            return $rate;
        })->union($default_exhage_rates);

        $reservationExhangeRates = collect();
        foreach($exhange_rate_filiterd as $rate) {
            $reservationRate = ['from' => $rate->from, 'to' => $default_currency->id, 'value' =>  $rate->value, 'reservation_id' => $reservation->id, ];
            $reservationExhangeRates->push($reservationRate);
        }
        ReservationExhangeRate::insert($reservationExhangeRates->all());
    }

    private function AddPrepareDurationTime($request, $event, $hall_id,  $reservation_id, $reservation_date) {
        $prepare_duration = TimeHelper::timeConvertToMinutes($request->preparation_hours, $request->preparation_minutes);
        if($prepare_duration == 0) {
            return false;
        }
        $related_id = $event->id;        
        $to_time = Carbon::createFromFormat('H:i', $event->to_time);
        $prepare_duration_start = clone $to_time;
        $prepare_duration_end = $to_time->addMinutes($prepare_duration);
         if($prepare_duration_start->isStartOfDay()) {
             return false;
         }
        if(!$this->checkAvailableTime($prepare_duration_start->format('H:i'), $prepare_duration_end->format('H:i'), $hall_id, $reservation_id, $reservation_date)){
            throw new Exception("The prepare duration [$event->from_time, $event->to_time] overlaping with other events");
        }
        $event = new Event();
        $event->from_time =  $prepare_duration_start;
        $event->to_time = $prepare_duration_end;
        $event->reservation_date = $reservation_date;
        $event->reservation_id = $reservation_id;
        $event->type = 0;
        $event->related_id = $related_id;
        $event->hall_id = $hall_id;
        return $event->save();
    }
     
    private function checkAvailableTime($start_time, $end_time, $hall_id, $reservation_id, $reservation_date) {
        $organization = auth()->user()->manager()->organization();
        $start_time = Carbon::createFromFormat('H:i', $start_time)->format("H:i");
        $end_time = Carbon::createFromFormat('H:i', $end_time)->format("H:i");
        
        $count = Event::select("events.id","events.*")
            ->join('reservations', 'reservations.id', 'events.reservation_id')
            ->where("organization_id", $organization->id)
            ->where("hall_id", $hall_id)
            ->where("reservation_date", $reservation_date->toDateString())
            ->whereNotIn("reservations.status", [DELAYED_RESERVATION, CANCELED_RESERVATION])
            ->where(function ($query) use ($start_time, $end_time){
                $query->whereRaw("CAST('$start_time' as Time) > from_time and CAST('$start_time' as Time) < to_time")
                    ->orWhereRaw("CAST('$end_time' as Time) > from_time and CAST('$end_time' as Time) < to_time");
            })->get();

        $availabe = ($count->count() == 0);
        return $availabe;
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

    private function generateReservationCode($number)
    {
        $prefix = "0000";
        $reservation_number = substr($prefix, strlen($number)-1, strlen($prefix)) .$number;
        $code = auth()->user()->manager()->organization()->code;
        return "R-".date('Y')."-$code$reservation_number";
    }

    private function replaceToArabicTime($time) {
        $time = strtoupper($time);
        $time =  Str::replaceLast('AM', 'ص', $time);
        $time =  Str::replaceLast('PM', 'م', $time);
        return $time;
    }
	
	public function checkTime(Request $request) {
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
				});})
				->where('reservation_date','=', Carbon::createFromFormat('d/m/Y', $request->event_date))->get();
				
		if($events->count() == 0) {
			return response()->json(['success'=> true]);
		} else {
			return response()->json(['success'=> false]);
		}		

	}
}