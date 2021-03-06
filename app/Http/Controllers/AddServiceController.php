<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Service;

class AddServiceController extends Controller
{

    public function show()
    {
        return view("manager.pages.service-add", ["action" => "add"]);
    }

    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:1',
            'price' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'notes' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with(['success' => 'false', "message"=>"فشلت عملية اضافة الخدمة"]);
        }

        $organization = auth()->user()->manager()->organization();
        $currency = $organization->organizationCurrency()->currency();

        $service = new Service();
        $service->name = $request->name;
        $service->price = $request->price;
        $service->description = $request->description;
        $service->notes = $request->notes;
        $service->organization_id = $organization->id;
        $service->currency_id = $currency->id;
        $result = $service->save();

        if (!$result) {
            return redirect()->back()
                    ->with(['success' => 'false', "message" => "فشلت عملية اضافة الخدمة"]);
        }

        return redirect("manager/service/lookup")
                    ->with(['success' => 'true', "message"=>"تم اضافة الخدمة  بنجاح"]); 
    }

}
