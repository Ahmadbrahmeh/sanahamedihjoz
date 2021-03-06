<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DataTables;
use Validator;
use App\Moderator;
use App\Address;
use App\Helpers\UserHelper;
use DB;

class LookupUserController extends Controller
{
    public function index()
    {
        return view('admin.pages.users-lookup');
    }

    public function getUsers()
    {
        $users = DB::table('users')
        ->select('users.id', 'users.fname', 'users.lname', 'users.email', 'users.created_at', 'managers.certifcate',
                 'users.updated_at', 'managers.phone1','organizations.name as organizations_name'
                 , 'organization_types.name as organization_type', 'managers.address_id')
        ->join('managers', 'users.id', 'managers.user_id')
        ->join('organizations', 'organizations.id', 'managers.organization_id')
        ->join('organization_types', 'organizations.type', 'organization_types.type')
        ->where('managers.type', 1)
        ->get();

    $users = $users->map(function ($user) {
        $user->name = $user->fname ." ". $user->lname;
        $user->created_at =  date('d/m/Y', strtotime($user->created_at));
        $user->updated_at =  date('d/m/Y', strtotime($user->updated_at));
        $user->address = $this->getCity($user->address_id);
        return $user;
    });

    return DataTables::of($users)->make(true);
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
