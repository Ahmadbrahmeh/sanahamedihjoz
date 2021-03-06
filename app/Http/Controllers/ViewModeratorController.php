<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\UserHelper;
use App\Manager;
use App\User;
use App\Attachment;
use App\Address;
use DB;
use Config;
use Storage;

class ViewModeratorController extends Controller
{
    public function show($id)
    {
        if(auth()->user()->manager()->type != 1) {
            return abort(404);
        }

        $moderator = User::select('users.fname', 'users.lname', 'users.email', 'users.created_at', 'users.updated_at',
                'users.path','users.temp_password', 'managers.phone1', 'managers.phone2', 'managers.phone3','managers.type',
                'users.first_login as active', 'managers.fb_link', 'managers.certifcate', 'managers.address_id')
            ->join('managers', 'users.id', 'managers.user_id')
            ->where('users.mark_for_delete', false)
            ->where('managers.organization_id', UserHelper::getManagerOrganizationId())
            ->where('managers.user_id', $id)
            ->where('managers.type', 2)
            ->firstOrFail();

        $addresses_chain = collect([]);
        $current_address = Address::where("id", $moderator->address_id)->first();
        while($current_address != null) 
        {
            $addresses_chain = $addresses_chain->prepend($current_address->name);
            $current_address = $current_address->parent();
        }
        $attachments = Attachment::select('attachments.id', 'attachments.path', 'attachments.type')
            ->join('user_attachments', 'attachments.id', 'user_attachments.attachment_id')
            ->where('user_attachments.user_id', $id)
            ->where('attachments.mark_for_delete', false)->get();
        $user_path = $moderator->path;
        $path = Config::get('constants.users.attachments.path')."/$user_path";
        $attachments = $attachments->map(function ($attachment) use ($path ) {
            $attachment->name = $attachment->path;
            $attachment->path =  $path."/".$attachment->path;
            return $attachment;
        });

        $icons = Config::get('constants.attachments.icons');
        $moderator->name = $moderator->fname ." ". $moderator->lname;
        $moderator->created_at =  date('m/d/Y', strtotime($moderator->created_at));
        $moderator->updated_at =  date('m/d/Y', strtotime($moderator->updated_at));
        $moderator->address = $addresses_chain->implode(" - ");
        $moderator->active = !$moderator->active ? "فعال" : "غير فعال";

       
        return view('manager.pages.moderator-view', 
                        ["moderator" => $moderator,
                        "attachments" => $attachments,
                        "icons" => $icons]);
    }

    public function download($id)
    {
        if(auth()->user()->manager()->type != 1) {
            return abort(404);
        }

        $attachment = Attachment::select("attachments.path", "user_attachments.user_id")
            ->join('user_attachments', 'attachments.id', 'user_attachments.attachment_id')
            ->join('managers', 'managers.user_id', 'user_attachments.user_id')
            ->where("attachments.id", $id)
            ->where('attachments.mark_for_delete', false)
            ->where("managers.organization_id", UserHelper::getManagerOrganizationId())
            ->firstOrFail();
        $user = User::select("*")->where("id", $attachment->user_id)->firstOrFail();
        $path = Config::get('constants.users.attachments.path')."/".$user->path ."/". $attachment->path;
        return Storage::disk('public')->download($path);
    }
}
