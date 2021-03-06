<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Manager;
use App\User;
use App\Attachment;
use App\Weekday;
use App\Address;
use DB;
use Config;
use Storage;
use Carbon\Carbon;

class ViewUserController extends Controller
{
    public function show($id)
    {
        $user = User::select('users.fname', 'users.lname', 'users.email', 'users.created_at', 'users.updated_at', 'users.path',
                'users.temp_password', 'managers.phone1', 'managers.phone2', 'managers.phone3','managers.type', 'managers.fb_link',
                'managers.certifcate','managers.organization_id', 'managers.address_id', 'organizations.name as organization_name',
                'organization_types.name as organization_type', 'users.first_login as active', 'organizations.from_time',
                'organizations.to_time')
            ->join('managers', 'users.id', 'managers.user_id')
            ->join('organizations', 'managers.organization_id', 'organizations.id')
            ->join('organization_types', 'organizations.type', 'organization_types.type')
            ->where('users.mark_for_delete', false)
            ->where('managers.user_id', $id)
            ->where('managers.type', 1)
            ->firstOrFail();
        $addresses_chain = collect([]);
        $current_address = Address::where("id", $user->address_id)->first();
        while($current_address != null) 
        {
            $addresses_chain = $addresses_chain->prepend($current_address->name);
            $current_address = $current_address->parent();
        }

        $working_days = Weekday::select('saturday', 'sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday')
                        ->where("organization_id", $user->organization_id)->firstOrFail();
        $weekdays = Config::get('constants.organizations.weekdays');
        $organization_working_days = "";
        $separator = "";
        foreach($weekdays as $key => $day)
        {
            if($working_days[$key])
            {
                $organization_working_days .= $separator."".$day;
                $separator = ", ";
            }
        }

        $user->organization_working_days = $organization_working_days;

        $attachments = Attachment::select('attachments.id', 'attachments.path', 'attachments.type')
            ->join('user_attachments', 'attachments.id', 'user_attachments.attachment_id')
            ->where('user_attachments.user_id', $id)
            ->where('attachments.mark_for_delete', false)->get();
        $user_path = $user->path;
        $path = Config::get('constants.users.attachments.path')."/$user_path";
        $attachments = $attachments->map(function ($attachment) use ($path ) {
            $attachment->name = $attachment->path;
            $attachment->path =  $path."/".$attachment->path;
            return $attachment;
        });

        $icons = Config::get('constants.attachments.icons');
        $user->name = $user->fname ." ". $user->lname;
        $user->created_at =  date('m/d/Y', strtotime($user->created_at));
        $user->updated_at =  date('m/d/Y', strtotime($user->updated_at));
        $user->address = $addresses_chain->implode(" - ");
        $user->active = !$user->active ? "فعال" : "غير فعال";
        $user->from_time = Carbon::parse($user->from_time)->format('g:ia');
        $user->to_time = Carbon::parse($user->to_time)->format('g:ia');
       
        return view('admin.pages.users-view', 
                        ["user" => $user,
                        "attachments" => $attachments,
                        "icons" => $icons]);
    }

    public function download($id)
    {
        $attachment = Attachment::select("attachments.path", "user_attachments.user_id")
            ->join('user_attachments', 'attachments.id', 'user_attachments.attachment_id')
            ->join('managers', 'managers.user_id', 'user_attachments.user_id')
            ->where("attachments.id", $id)
            ->where('attachments.mark_for_delete', false)
            ->firstOrFail();
        $user = User::select("*")->where("id", $attachment->user_id)->firstOrFail();
        $path = Config::get('constants.users.attachments.path')."/".$user->path ."/". $attachment->path;
        return Storage::disk('public')->download($path);
    }

}
