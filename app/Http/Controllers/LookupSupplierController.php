<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Validator;
use DataTables;
use App\Address;
use App\Helpers\UserHelper;
use App\Helpers\MainHelper;
use Str;

class LookupSupplierController extends Controller
{
    public function show() {
        return view('manager.pages.supplier-lookup');
    }

    public function getSupplier()
    {
        $suppliers = DB::table('suppliers')
            ->select('id','name', 'email', 'phone1', 'certifcate','address_id', 'created_at', 'updated_at')
            ->where('organization_id', UserHelper::getManagerOrganizationId())
            ->get();


        $suppliers = $suppliers->map(function ($supplier) {
            $supplier->created_at =  date('d/m/Y', strtotime($supplier->created_at));
            $supplier->updated_at =  date('d/m/Y', strtotime($supplier->updated_at));
            $supplier->address = $this->getCity($supplier->address_id);
            return $supplier;
        });

        return DataTables::of($suppliers)->make(true);
    }

    
    private function getCity($id)
    {
        $addresses_chain = collect([]);
        $current_address = Address::where("id", $id)->first();
        while($current_address != null) {
            if($current_address->type == "city") {
                return $current_address->name;
            }
            $current_address = $current_address->parent();
        }
        return "";
    }


}