<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use DataTables;
use App\Address;
use App\Helpers\UserHelper;

class LookupCustomerController extends Controller
{
    public function show()
    {
        return view('manager.pages.customer-lookup');
    }

    public function getCustomers()
    {
        $customers = DB::table('customers')
            ->select('id', 'code', 'name', 'email', 'phone1', 'address_id', 'created_at', 'updated_at')
            ->where('organization_id', UserHelper::getManagerOrganizationId())
            ->get();

        $customers = $customers->map(function ($customer) {
            $customer->created_at =  date('d/m/Y', strtotime($customer->created_at));
            $customer->updated_at =  date('d/m/Y', strtotime($customer->updated_at));
            $customer->address = $this->getCity($customer->address_id);
            return $customer;
        });

        return DataTables::of($customers)->make(true);
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
