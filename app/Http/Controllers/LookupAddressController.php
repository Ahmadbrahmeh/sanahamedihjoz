<?php

namespace App\Http\Controllers;

use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Address;
use Validator;
use Config;

class LookupAddressController extends Controller
{
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'nullable|string|exists:addresses'
        ]);

        if ($validator->fails()) {
            return redirect()->route("address-lookup");
        }

        $type = "city";
        $code =  $request->query('code');
        $address = Address::where('code', $code)->where("mark_for_delete", false)->first();
        $addresses_chain = collect([]);
        $current_address = $address;
        while($current_address != null) 
        {
            $addresses_chain = $addresses_chain->prepend($current_address);
            $current_address = $current_address->parent();
        }
        if(isset($address))
        {
            $type = $address->children()->firstOrFail()->type ?? ""; 
        }
        $address_types =  Config::get('constants.addresses.types');
        $title = $address_types[$type];
        return view('admin.pages.address-lookup')
            ->with([ "title" => $title,
                     "code" => $code,
                     "breadcrumbs" => $addresses_chain]);
    }

    public function getAddresses(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'nullable|string|exists:addresses',
        ]);
        $addresses = collect([]);
        if (!$validator->fails()) {
            $code = $request->query('code');
            $address = Address::where('code', $code)->where("mark_for_delete", false)->first();
            if(isset($address))
            {
                $addresses = $address->children()->where("mark_for_delete", false)->get();
            }
            else {
                $addresses = Address::where('parent_id', 0)->where("mark_for_delete", false)->get();
            }
            $tail = Config::get('constants.addresses.tail');
            $addresses->map(function($address) use ($tail) {
                return $address->is_tail = $address->type == $tail;
            });
        }
        return DataTables::of($addresses)->make(true);
    }

    public function removeAddress(Request $request)
    {
        $validator = Validator::make($request->all(), ['id' => 'required|integer|exists:addresses']);
        
        if ($validator->fails()) {
            return redirect()->back()->with(['success' => 'false',"message" => "فشلت عملية حذف العنوان"]);
        }
        $hall = Address::find($request->id);
        $hall->mark_for_delete = 1;
        $status = $hall->save();
        
        if (!$status) {
            return redirect()->back()->with(['success' => 'false',"message"=>"فشلت عملية حذف العنوان"]);
        }

        return redirect()->back()->with(['success' => 'true', "message"=>"تم حذف العنوان بنجاح"]); 
    }
}
