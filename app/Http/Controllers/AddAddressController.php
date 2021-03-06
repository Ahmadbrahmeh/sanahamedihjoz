<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Address;
use Validator;
use Config;

class AddAddressController extends Controller
{
    function show(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'nullable|string|exists:addresses'
        ]);

        if ($validator->fails()) {
            return redirect()->route("address-lookup");
        }

        $type = "city";
        $address_types =  Config::get('constants.addresses.types');
        $code =  $request->query('code');
        $address = Address::where('code', $code)->first();
        $chain =  Config::get('constants.addresses.chain');
        if(isset($address))
        {
            $type = $chain[$address->type];
            $address->title = $address_types [$address->type];
        }
        $title = $address_types[$type];

        $addresses_chain = collect([]);
        $current_address = $address;
        while($current_address != null) 
        {
            $addresses_chain = $addresses_chain->prepend($current_address);
            $current_address = $current_address->parent();
        }

        return view('admin.pages.address-add')
            ->with(["title" => $title,
                    "code" => $code,
                    "address" => $address,
                    "breadcrumbs" => $addresses_chain]);
    }

    function save(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'parent_code' => 'nullable|string|exists:addresses,code',
            'name' => 'required|string|unique:addresses|min:2',
            'code' => 'required|string|unique:addresses|min:2'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->with(['success' => 'false', "message" => "فشلت عملية اضافة العنوان"])
                ->withErrors($validator)
                ->withInput();
        }

        $parent_address = Address::where("code", $request->parent_code)->first();
        $parent_id = 0;
        $type = "city";
        $chain =  Config::get('constants.addresses.chain');
        if(isset($parent_address))
        {
            $parent_id = $parent_address->id;
            $type = $chain[$parent_address->type];
        }

        $address = new Address();
        $address->name = $request->name;
        $address->code = strtolower($request->code);
        $address->parent_id = $parent_id;
        $address->type = $type;
        $result = $address->save();

        if (!$result) {
            return redirect()->back()
                    ->with(['success' => 'false', "message" => "فشلت عملية اضافة العنوان"]);
        }
        
        return redirect()->route("address-lookup", ["code" => $request->parent_code])
            ->with(['success' => 'true', "message" => "تم اضافة العنوان بنجاح"]);
    }
}