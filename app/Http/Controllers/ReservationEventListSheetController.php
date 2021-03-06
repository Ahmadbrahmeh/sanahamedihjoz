<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Reservation;

class ReservationEventListSheetController extends Controller
{
    public function show($reservation_code) {
        $user = auth()->user();
        $organization = $user->manager()->organization();
        $reservation = Reservation::select("*")
            ->where("code", $reservation_code)
            ->where("organization_id",  $organization->id)->firstOrFail(); 
        $reservation->eventlists = $reservation->eventlists()->where("mark_for_delete",  false);

        return view('manager.pages.eventlist-sheet')->with([ 
            'reservation' => $reservation,
            'organization' => $organization
        ]);
    }
}
