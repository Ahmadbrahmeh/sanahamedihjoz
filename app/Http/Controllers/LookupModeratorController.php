<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DataTables;
use Validator;
use App\Moderator;
use App\User;
use App\Address;
use App\Helpers\UserHelper;
use DB;

class LookupModeratorController extends Controller
{
    public function index()
    {  
        if(auth()->user()->manager()->type != 1) {
            return abort(404);
        }
        return view('manager.pages.moderator-lookup');
    }

    public function getModerators()
    {
        if(auth()->user()->manager()->type != 1) {
            return abort(404);
        }
        $moderators = DB::table('users')
            ->select('users.id', 'users.fname', 'users.lname', 'users.email', 'users.created_at',
                     'users.updated_at', 'managers.phone1', 'managers.address_id')
            ->join('managers', 'users.id', 'managers.user_id')
            ->where('managers.organization_id', UserHelper::getManagerOrganizationId())
            ->where('managers.type', 2)
            ->where('users.mark_for_delete', false)
            ->get();

        $moderators = $moderators->map(function ($moderator) {
            $moderator->name = $moderator->fname ." ". $moderator->lname;
            $moderator->created_at =  date('d/m/Y', strtotime($moderator->created_at));
            $moderator->updated_at =  date('d/m/Y', strtotime($moderator->updated_at));
            $moderator->address = $this->getCity($moderator->address_id);
            return $moderator;
        });

        return DataTables::of($moderators)->make(true);
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

    /**
     * Remove current Moderator from lookup
     * @param $request
     * @return redirect with validation message
    */

    public function removeModerator(Request $request) {
        if(auth()->user()->manager()->type != 1) {
            return abort(404);
        }
        
        $validator = Validator::make($request->all(), ['id' => 'required|integer|exists:users']);
        
        if ($validator->fails()) {
            return redirect()->back()->with(['success' => 'false',"message"=>"فشلت عملية حذف المشرف"]);
        }
        
        $id = $request->id;
        $user = User::select("users.id")
                    ->where('users.id', $id)
                    ->where('organization_id', UserHelper::getManagerOrganizationId())
                    ->join("managers", "managers.user_id", "users.id")->firstOrFail();
        $user->mark_for_delete = true;
        $status = $user->save();
        
        if (!$status) {
            return redirect()->back()->with(['success' => 'false',"message"=>"فشلت عملية حذف المشرف"]);
        }

        return redirect()->back()->with(['success' => 'true', "message"=>"تم حذف المشرف بنجاح"]); 
    }
}