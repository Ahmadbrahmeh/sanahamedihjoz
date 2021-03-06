<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Hall;
use App\Helpers\UserHelper;
use DB;


class AddHallController extends Controller
{

    public function show()
    {
        return view("manager.pages.hall-add", ["action" => "add"]);
    }
    

    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|min:2',
            'price' => 'required|integer|min:1',
            'capacity' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with(['success' => 'false', "message"=>"فشلت عملية اضافة القاعة"]);
        }

        $organization = auth()->user()->manager()->organization();
        $currency = $organization->organizationCurrency()->currency();

        $hall = new Hall();
        $hall->name = $request->name;
        $hall->price = $request->price;
        $hall->capacity = $request->capacity;
        $hall->type = 1;
        $hall->organization_id = UserHelper::getManagerOrganizationId();
        $hall->currency_id = $currency->id;
        $result = $hall->save();

        if (!$result) {
            return redirect()->back()
                    ->with(['success' => 'false', "message" => "فشلت عملية اضافة القاعة"]);
        }

        return redirect("manager/halls/lookup")
                    ->with(['success' => 'true', "message"=>"تم اضافة القاعة  بنجاح"]); 
    }
}
