<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Manager;
use App\Address;
use Validator;
use DB;

class PersonalInfoSettingsController extends Controller
{
    public function show()
    {
        $user = User::select('users.fname', 'users.lname', 'users.email', 'managers.phone1',
            'managers.phone2', 'managers.phone3', 'managers.fb_link', 'managers.address_id')
            ->join('managers', 'users.id', 'managers.user_id')
            ->where('users.id', auth()->user()->id)
            ->firstOrFail();
        $address_types = collect([]);
        $current_address = Address::where("id", $user->address_id)->first();
        while($current_address != null) 
        {
            $address_types[$current_address->type] = $current_address;
            $current_address = $current_address->parent();
        }
        $cities = Address::where("type", "city")->where("mark_for_delete", false)->get();
        $regions = !isset($address_types["region"]) ? [] : $address_types["city"]->children()->where("mark_for_delete", false)->get();
        $streets = !isset($address_types["street"]) ? [] : $address_types["region"]->children()->where("mark_for_delete", false)->get();

        return view('manager.pages.personal-info')
            ->with(['user' => $user,
                    "cities" => $cities,
                    "regions" => $regions,
                    "streets" => $streets,
                    "address_types" => $address_types,]);
    }

    public function save(Request $request)
    {
        $id = auth()->user()->id;
        $manager_id = $this->getManagerId($id);
        $validator = Validator::make($request->all(), [
            'fname' => 'required|string|min:2|max:255',
            'lname' => 'required|string|min:2|max:255',
            'address_city' => 'required|string|exists:addresses,code',
            'address_region' => 'nullable|string|exists:addresses,code',
            'address_street' => 'nullable|string|exists:addresses,code',
            'phone1' => "required|string|unique:managers,phone1,$manager_id|max:255",
            'phone2' => "nullable|string|unique:managers,phone2,$manager_id|max:255",
            'phone3' => "nullable|string|unique:managers,phone3,$manager_id|max:255",
            'fb_link' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->with(['success' => 'false', "message" => "فشلت عملية تحديث المعلومات الشخصية"])
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::transaction(function() use ($request, $id) {
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

                $user = User::findOrFail($id);
                $user->fname = $request->fname;
                $user->lname = $request->lname;
                $user->save();
                    
                $manager = Manager::where('user_id', $id)->firstOrFail();
                $manager->address_id = $address->id;
                $manager->phone1 = $request->phone1;
                $manager->phone2 = $request->phone2;
                $manager->phone3 = $request->phone3;
                $manager->fb_link = $request->fb_link;
                $manager->save();
            });
        }
        catch (Exception $e) {
            return redirect()->back()
                ->with(['success' => 'false', "message" => "فشلت عملية تحديث المعلومات الشخصية"])
                ->withInput();
        }

        return redirect()->back()
        ->with(['success' => 'true', "message" => "تم تحديث المعلومات الشخصية بنجاح"]);
    }

    private function getManagerId($user_id)
    {
        $manager = Manager::where('user_id', $user_id)->firstOrFail();
        return $manager->id;
    }
}