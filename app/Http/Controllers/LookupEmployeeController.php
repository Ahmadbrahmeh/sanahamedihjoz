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
use Config;

class LookupEmployeeController extends Controller
{
    public function show() {
        return view('manager.pages.employee-lookup');
    }
    
    public function getEmployee()
    {
        $employees = DB::table('employees')
            ->select('id','name', 'email', 'phone1','address_id','salary_type', 'created_at', 'updated_at')
            ->where('organization_id', UserHelper::getManagerOrganizationId())
            ->get();

        $salary_types = collect(Config::get('constants.employees.salary.type'));

        $employees = $employees->map(function ($employee) use ($salary_types) {
            $employee->created_at =  date('d/m/Y', strtotime($employee->created_at));
            $employee->updated_at =  date('d/m/Y', strtotime($employee->updated_at));
            $employee->address = $this->getCity($employee->address_id);
            $employee->salary_type = $salary_types->get($employee->salary_type);
            return $employee;
        });

        return DataTables::of($employees)->make(true);
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