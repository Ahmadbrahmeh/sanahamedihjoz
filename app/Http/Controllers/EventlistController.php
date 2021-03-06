<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Eventlist;


class EventlistController extends Controller
{
    public function show() {
        $organization_id = auth()->user()->manager()->organization_id;
        $eventlists = EventList::select("name", "id")
            ->where("organization_id", $organization_id)
            ->where("mark_for_delete", false)
            ->get();
        return view('manager.pages.eventlist')->with(['eventlists' => $eventlists]);
    }

    public function add(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with(['success' => 'false', "message" => "فشلت عملية اضافة عنصر جدول مناسبة"]);
        }
        
        $eventlist = new Eventlist();
        $eventlist->name = $request->name;
        $eventlist->organization_id = auth()->user()->manager()->organization_id;
        $eventlist->created_by = auth()->user()->id;
        $eventlist->updated_by = auth()->user()->id;
        $result = $eventlist->save();

        if (!$result) {
            return redirect()->back()
                    ->with(['success' => 'false', "message" => "فشلت عملية اضافة عنصر جدول مناسبة"]);
        }

        return redirect()->route("eventlist-add")
                ->with(['success' => 'true', "message" => "تم اضافة عنصر جدول المناسبة  بنجاح"]);

    }

    /**
     * Remove current eventlist from eventlist table
     * @param $request
     * @return redirect with validation message
    */

    public function removeEventlist(Request $request) {
        $validator = Validator::make($request->all(), ['id' => 'required|integer|exists:eventlist']);
        
        if ($validator->fails()) {
            return redirect()->back()->with(['success' => 'false',"message" => "فشلت عملية حذف المناسبة"]);
        }
        $organization_id =  auth()->user()->manager()->organization_id;
        $id = $request['id'];
        $eventlist = Eventlist::where('id', $id)
                    ->where('organization_id', $organization_id)->firstOrFail();

        $eventlist->mark_for_delete = 1;
        $status = $eventlist->save();
        
        if (!$status) {
            return redirect()->back()->with(['success' => 'false',"message" => "فشلت عملية حذف المناسبة"]);
        }

        return redirect()->back()->with(['success' => 'true', "message" => "تم حذف المناسبة بنجاح"]); 
    }
}
