<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Service;
use App\Helpers\UserHelper;

use DB;


class EditServiceController extends Controller
{
    /**
     * Direct the user to edit service view and display current service data
     * @param $id the id for current service
     * @return list of current service attributes values
     */
    public static function show($id) {    
        $service = Service::where('id', $id)
                    ->where('mark_for_delete', false)
                    ->where('organization_id', UserHelper::getManagerOrganizationId())->firstOrFail();

        return view('manager.pages.service-add',
                            ["service" => $service,
                            "action" => "edit"]);
        }

    /**
     * Save new service attributes values in database
     * @param $request current request
     * @return redirect with validation message
     */
    public static function save(Request $request, $id) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:1',
            'price' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'notes' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with(['success' => 'false', "message"=>"فشلت عملية تعديل معلومات الخدمة"]);
        }

        $service = Service::where('id', $id)
                    ->where('organization_id', UserHelper::getManagerOrganizationId())->first();

        $service->name = $request->name;
        $service->price = $request->price;
        $service->description = $request->description;
        $service->notes = $request->notes;
        $result = $service->save();

        if (!$result) {
            return redirect()->back()
            ->with(['success' => 'false', "message" => "فشلت عملية تعديل معلومات الخدمة"]);
        }

        return redirect()->back()
            ->with(['success' => 'true', "message"=>"تم تعديل معلومات الخدمة بنجاح"]);
    }
}