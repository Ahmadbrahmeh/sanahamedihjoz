<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Currency;
use App\Helpers\UserHelper;
use DB;

class AddCurrencyController extends Controller
{
    public function show()
    {
        return view("manager.pages.currency-add", ["action" => "add"]);
    }

    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|min:2',
            'code' => 'required|string|min:1',
            'sign' => 'required|string|min:1',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with(['success' => 'false', "message"=>"فشلت عملية اضافة العملة"]);
        }

        $currency = new Currency();
        $currency->name = $request->name;
        $currency->code = strtoupper($request->code);
        $currency->sign = $request->sign;
        $currency->created_by = auth()->user()->id;
        $currency->organization_id = UserHelper::getManagerOrganizationId();
        $result = $currency->save();

        if (!$result) {
            return redirect()->back()
                    ->with(['success' => 'false', "message" => "فشلت عملية اضافة العملة"]);
        }

        return redirect("manager/currency/lookup")
                ->with(['success' => 'true', "message" => "تم اضافة العملة  بنجاح"]); 
    }
    
}
