<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Validator;
use App\Address;
use App\Supplier;
use App\Helpers\UserHelper;
use App\Helpers\MainHelper;
use Str;

class AddSupplierController extends Controller
{
    public function show() {
        $cities = Address::where("type", "city")->where("mark_for_delete", false)->get();
        return view('manager.pages.supplier-add')->with(['cities' => $cities]);
    }
    public function save(Request $request) {   
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:2|max:255',
            'address_city' => 'required|string|exists:addresses,code',
            'address_region' => 'nullable|string|exists:addresses,code',
            'address_street' => 'nullable|string|exists:addresses,code',
            'email' => 'nullable|email|unique:suppliers',
            'certifcate' => 'nullable|string|unique:suppliers|max:255',
            'phone1' => 'required|string|unique:suppliers|max:255',
            'phone2' => 'nullable|string|unique:suppliers|max:255',
            'phone3' => 'nullable|string|unique:suppliers|max:255'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->with(['success' => 'false', "message" => "فشلت عملية اضافة المورد"])
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

            $address = $address->first();
            $supplier = new Supplier();
            $supplier->name = $request->name;
            $supplier->email = $request->email ;
            $supplier->address_id = $address->id;
            $supplier->phone1 = $request->phone1;
            $supplier->phone2 = $request->phone2;
            $supplier->phone3 = $request->phone3;
            $supplier->certifcate = $request->certifcate;
            $supplier->organization_id = UserHelper::getManagerOrganizationId();
            $supplier->created_by = auth()->user()->id;
            $supplier->updated_by = auth()->user()->id;
            $supplier->save();
        });
     }
      catch (Exception $e) {
          return redirect()->back()
            ->with(['success' => 'false', "message" => "فشلت عملية اضافة المورد"])
            ->withInput();
      }
        return redirect("manager/supplier/lookup");
    }
}