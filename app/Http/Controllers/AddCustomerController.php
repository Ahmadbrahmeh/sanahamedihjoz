<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Validator;
use App\Address;
use App\Customer;
use App\Helpers\UserHelper;
use App\Helpers\MainHelper;
use Str;

class AddCustomerController extends Controller
{
    public function show() {
        $cities = Address::where("type", "city")->where("mark_for_delete", false)->get();
        return view('manager.pages.customer-add')->with(['cities' => $cities]);
    }

    public function save(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:2|max:255',
            'address_city' => 'required|string|exists:addresses,code',
            'address_region' => 'nullable|string|exists:addresses,code',
            'address_street' => 'nullable|string|exists:addresses,code',
            'email' => 'nullable|email|unique:customers',
            'certifcate' => 'nullable|string|unique:customers|max:255',
            'phone1' => 'required|string|unique:customers|max:255',
            'phone2' => 'nullable|string|unique:customers|max:255',
            'phone3' => 'nullable|string|unique:customers|max:255'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->with(['success' => 'false', "message" => "فشلت عملية اضافة زبون"])
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::transaction(function() use ($request) {
            $address = DB::table('addresses as cities')->where("cities.code", $request->address_city);
            if($request->address_region != null) {
                $address = $address->join("addresses as regions", "cities.id", "regions.parent_id")
                    ->where("regions.code", $request->address_region);
                if($request->address_street != null) {
                    $address = $address->join("addresses as streets", "regions.id", "streets.parent_id")
                        ->where("streets.code", $request->address_street);
                }
            }

            $last_customer = Customer::latest()->first();
            $number = 0;
            if(isset($last_customer)) {
                $number = $last_customer->part_number;
            }
            $number = $number + 1;
            $address = $address->first();
            $customer = new Customer();
            $customer->name = $request->name;
            $customer->email = $request->email ;
            $customer->address_id = $address->id;
            $customer->phone1 = $request->phone1;
            $customer->phone2 = $request->phone2;
            $customer->phone3 = $request->phone3;
            $customer->code = $this->generateCustomerCode($number);
            $customer->part_number = $number;
            $customer->certifcate = $request->certifcate;
            $customer->organization_id = UserHelper::getManagerOrganizationId();
            $customer->created_by = auth()->user()->id;
            $customer->updated_by = auth()->user()->id;
            $customer->save();
        });
     }
      catch (Exception $e) {
          return redirect()->back()
            ->with(['success' => 'false', "message" => "فشلت عملية اضافة زبون"])
            ->withInput();
      }

	if($request->checker == "2") {
		      return redirect()->back()
            ->with(['success' => 'true', "message"=>"تم اضافة الزبون بنجاح"]);
	} 
	
      return redirect("manager/customers/lookup")
            ->with(['success' => 'true', "message"=>"تم اضافة الزبون بنجاح"]);
    }

    private function generateCustomerCode($number)
    {
        $prefix = "0000";
        $customer_number = substr($prefix, strlen($number)-1, strlen($prefix)) .$number;
        $code = auth()->user()->manager()->organization()->code;
        return "C-".date('Y')."-$code$customer_number";
    }
}