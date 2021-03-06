<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Validator;
use App\Employee;
use App\Address;
use App\Helpers\UserHelper;
use App\Helpers\MainHelper;
use Str;

class AddEmployeeController extends Controller
{
    public function show() {
        $cities = Address::where("type", "city")->where("mark_for_delete", false)->get();
        return view('manager.pages.employee-add')->with(['cities' => $cities]);
    }
    public function save(Request $request) {   
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:2|max:255',
            'address_city' => 'required|string|exists:addresses,code',
            'address_region' => 'nullable|string|exists:addresses,code',
            'address_street' => 'nullable|string|exists:addresses,code',
            'email' => 'nullable|email|unique:employees',
            'phone1' => 'required|string|unique:employees|max:255',
            'phone2' => 'nullable|string|unique:employees|max:255',
            'phone3' => 'nullable|string|unique:employees|max:255',
            'salary_type' => 'required|integer|in:1,2,3'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->with(['success' => 'false', "message" => "فشلت عملية اضافة الموظف"])
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
            $employee = new Employee();
            $employee->name = $request->name;
            $employee->email = $request->email ;
            $employee->address_id = $address->id;
            $employee->phone1 = $request->phone1;
            $employee->phone2 = $request->phone2;
            $employee->phone3 = $request->phone3;
            $employee->salary_type = $request->salary_type;
            $employee->organization_id = UserHelper::getManagerOrganizationId();
            $employee->created_by = auth()->user()->id;
            $employee->updated_by = auth()->user()->id;
            $employee->save();
        });
     }
      catch (Exception $e) {
          return redirect()->back()
            ->with(['success' => 'false', "message" => "فشلت عملية اضافة الموظف"])
            ->withInput();
      }
        return redirect("/manager/employee/lookup");
    }
    
}