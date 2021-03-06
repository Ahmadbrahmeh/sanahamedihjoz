<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use DataTables;
use App\Reservation;
use Config;
use Validator;

define("FINISHED_RESERVATION", Config::get('constants.reservations.status.mapping.finished'));
define("CANCELED_RESERVATION", Config::get('constants.reservations.status.mapping.cancel'));
define("DELAYED_RESERVATION", Config::get('constants.reservations.status.mapping.delay'));

class LookupReservationController extends Controller
{
    public function show() {
        return view('manager.pages.reservation-lookup');
    }

    public function getReservations(Request $request) {
        $manager = auth()->user()->manager();
        $organization = $manager->organization();

        $reservations = DB::table('reservations')
            ->select('reservations.id', 'reservations.code', 'reservations.customer_name', 'reservations.title', 'reservations.status',
                'customers.phone1', 'users.fname', 'users.lname', 'reservations.created_at','reservations.updated_at')
        ->where('reservations.organization_id', $organization->id)
        ->join("users", "users.id", "reservations.created_by")
        ->join("customers", "customers.id", "reservations.customer_id")
        ->orderBy("created_at", "desc")
        ->get();
        $display_cancel = ($manager->type == 1);
        $reservations = $reservations->map(function ($reservation) use ($display_cancel) {
            $status_list = Config::get('constants.reservations.status.arabic');
            $reservation->created_at =  date('d/m/Y', strtotime($reservation->created_at));
            $reservation->updated_at =  date('d/m/Y', strtotime($reservation->updated_at));
            $reservation->creator_name = $reservation->fname . " " . $reservation->lname;
            $reservation->disable_cancel = ($reservation->status == FINISHED_RESERVATION || $reservation->status == CANCELED_RESERVATION );
            $reservation->status = $status_list[$reservation->status];        
            $reservation->display_cancel = $display_cancel;        
            return $reservation;
        });

    return DataTables::of($reservations)->make(true);
    }

    public function cancelReservation(Request $request) {
        $validator = Validator::make($request->all(), ['id' => 'required|integer|exists:reservations']);
        
        if ($validator->fails()) {
            return redirect()->back()->with(['success' => 'false',"message" => "فشلت عملية الغاء الحجز"]);
        }
        $manager = auth()->user()->manager();
        $organization = $manager->organization();
        $status = false;
        if($manager->type == 1) {
            $reservation = Reservation::select("*")
                ->where("id", $request->id)
                ->where("organization_id", $organization->id)
                ->whereNotIn("status", [FINISHED_RESERVATION, CANCELED_RESERVATION])->firstOrFail();
        
            $reservation->status = CANCELED_RESERVATION;
            $reservation->updated_by = auth()->user()->id;
            $status = $reservation->save();
        }
        
        if (!$status) {
            return redirect()->back()->with(['success' => 'false',"message" => "فشلت عملية الغاء الحجز"]);
        }

        return redirect()->back()->with(['success' => 'true', "message" => "تم الغاء عملية الحجز بنجاح"]); 
    }

    public function delayReservation(Request $request) {
        $validator = Validator::make($request->all(), ['id' => 'required|integer|exists:reservations']);

        if ($validator->fails()) {
            return redirect()->back()->with(['success' => 'false',"message" => "فشلت عملية تأجيل الحجز"]);
        }
        $manager = auth()->user()->manager();
        $organization = $manager->organization();
        $status = false;
        if($manager->type == 1) {
            $reservation = Reservation::select("*")
                ->where("id", $request->id)
                ->where("organization_id", $organization->id)
                ->whereNotIn("status", [FINISHED_RESERVATION, CANCELED_RESERVATION, DELAYED_RESERVATION])->firstOrFail();
        
            $reservation->status = DELAYED_RESERVATION;
            $reservation->updated_by = auth()->user()->id;
            $status = $reservation->save();
        }
        
        if (!$status) {
            return redirect()->back()->with(['success' => 'false',"message" => "فشلت عملية تأجيل الحجز"]);
        }

        return redirect()->back()->with(['success' => 'true', "message" => "تم تأجيل الحجز بنجاح"]); 
    }
    
 }
