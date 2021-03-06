<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DataTables;
use Validator;
use DB;
use App\Helpers\UserHelper;
use App\Service;

class LookupServiceController extends Controller
{
    public function index()
    {
        return view('manager.pages.service-lookup');
    }

    public function getServices()
    {
        $services = DB::table('services')->select('*')
            ->where('organization_id', UserHelper::getManagerOrganizationId())
            ->where('mark_for_delete', false)
            ->get();

        $services = $services->map(function ($service) {
            $service->created_at =  date('d/m/Y', strtotime($service->created_at));
            $service->updated_at =  date('d/m/Y', strtotime($service->updated_at));
            return $service;
        });

        return DataTables::of($services)->make(true);
    }

    /**
     * Remove current service from lookup
     * @param $request
     * @return redirect with validation message
    */

    public function removeService(Request $request) {
        $validator = Validator::make($request->all(), ['id' => 'required|integer|exists:services']);
        
        if ($validator->fails()) {
            return redirect()->back()->with(['success' => 'false',"message"=>"فشلت عملية حذف الخدمة"]);
        }
        
        $id = $request['id'];
        $service = Service::where('id', $id)
                    ->where('organization_id', UserHelper::getManagerOrganizationId())->firstOrFail();
        $service->mark_for_delete = 1;
        $status = $service->save();
        
        if (!$status) {
            return redirect()->back()->with(['success' => 'false',"message"=>"فشلت عملية حذف الخدمة"]);
        }

        return redirect()->back()->with(['success' => 'true', "message"=>"تم حذف الخدمة بنجاح"]); 
    }
}
