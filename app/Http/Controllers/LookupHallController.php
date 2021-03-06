<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DataTables;
use Validator;
use App\Hall;
use App\Helpers\UserHelper;
use DB;

class LookupHallController extends Controller
{
    public function index()
    {
        return view('manager.pages.hall-lookup');
    }

    public function getHalls()
    {
        $halls = DB::table('halls')->select('*')
            ->where('organization_id', UserHelper::getManagerOrganizationId())
            ->where('mark_for_delete', false)
            ->get();

        $halls = $halls->map(function ($hall) {
            $hall->created_at =  date('d/m/Y', strtotime($hall->created_at));
            $hall->updated_at =  date('d/m/Y', strtotime($hall->updated_at));
            return $hall;
        });

        return DataTables::of($halls)->make(true);
    }

    /**
     * Remove current hall from lookup
     * @param $request
     * @return redirect with validation message
    */

    public function removeHall(Request $request) {
        $validator = Validator::make($request->all(), ['id' => 'required|integer|exists:halls']);
        
        if ($validator->fails()) {
            return redirect()->back()->with(['success' => 'false',"message"=>"فشلت عملية حذف القاعة"]);
        }
        
        $id = $request['id'];
        $hall = Hall::where('id', $id)
                    ->where('organization_id', UserHelper::getManagerOrganizationId())->firstOrFail();
        if($hall->reservationHalls() != null) {
            return redirect()->back()->with(['success' => 'false',"message"=>"فشلت عملية حذف القاعة, لا يمكن حذف قاعة مستخدمة"]);
        }
        $hall->mark_for_delete = 1;
        $status = $hall->save();
        
        if (!$status) {
            return redirect()->back()->with(['success' => 'false',"message"=>"فشلت عملية حذف القاعة"]);
        }

        return redirect()->back()->with(['success' => 'true', "message"=>"تم حذف القاعة بنجاح"]); 
    }

}
