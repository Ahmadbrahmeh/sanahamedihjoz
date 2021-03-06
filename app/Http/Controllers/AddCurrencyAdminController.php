<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Currency;
use App\Helpers\UserHelper;
use App\Organization;
use DB;

class AddCurrencyAdminController extends Controller
{
    public function show()
    {
        return view("admin.pages.currency-add");
    }

    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|min:2',
            'code' => 'required|string|min:1',
            'sign' => 'required|string|min:1',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with(['success' => 'false', "message" => "فشلت عملية اضافة العملة"]);
        }
        $organization = Organization::select("id")->where("type", 0)->first();
        $currency = new Currency();
        $currency->name = $request->name;
        $currency->code = strtoupper($request->code);
        $currency->sign = $request->sign;
        $currency->default = true;
        $currency->organization_id = $organization->id;
        $currency->created_by = auth()->user()->id;
        $result = $currency->save();

        if (!$result) {
            return redirect()->back()
                    ->with(['success' => 'false', "message" => "فشلت عملية اضافة العملة"]);
        }

        return redirect("admin/currency/lookup")
                ->with(['success' => 'true', "message" => "تم اضافة العملة  بنجاح"]); 
    }
}
