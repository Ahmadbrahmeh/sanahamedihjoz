<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\User;
use App\Attachment;
use App\Manager;
use App\UserAttachment;
use App\Address;
use App\Helpers\UserHelper;
use App\Helpers\FileHelper;
use DB;
use Str;
use Storage;
use Config;


class EditModeratorController extends Controller
{
    public function show($id) {
        if(auth()->user()->manager()->type != 1) {
            return abort(404);
        }

        $maxAttachments =  intval(Config::get('constants.users.attachments.max'));
        $moderator = User::select('users.id', 'users.fname', 'users.lname', 'users.email', 'users.created_at', 'users.updated_at',
                'users.path','users.temp_password', 'managers.phone1', 'managers.phone2', 'managers.phone3', 'managers.type',
                'managers.fb_link', 'managers.certifcate', 'managers.address_id')
            ->join('managers', 'users.id', 'managers.user_id')
            ->where('users.mark_for_delete', false)
            ->where('managers.organization_id', UserHelper::getManagerOrganizationId())
            ->where('managers.user_id', $id)
            ->where('managers.type', 2)
            ->firstOrFail();
        $address_types = collect([]);
        $current_address = Address::where("id", $moderator->address_id)->first();
        while($current_address != null) 
        {
            $address_types[$current_address->type] = $current_address;
            $current_address = $current_address->parent();
        }
        $cities = Address::where("type", "city")->where("mark_for_delete", false)->get();
        $regions = !isset($address_types["region"]) ? [] : $address_types["city"]->children()->where("mark_for_delete", false)->get();
        $streets = !isset($address_types["street"]) ? [] : $address_types["region"]->children()->where("mark_for_delete", false)->get();

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

        $attachments_count = $attachments->count();
        $icons = Config::get('constants.attachments.icons');

        return view('manager.pages.moderator-add')
            ->with(['maxAttachments' => $maxAttachments,
                    'attachments_count' => $attachments_count,
                    "moderator" => $moderator,
                    "attachments" => $attachments,
                    "cities" => $cities,
                    "regions" => $regions,
                    "streets" => $streets,
                    "address_types" => $address_types,
                    "icons" => $icons,
                    'action' => 'edit']);
    }

    public function save(Request $request, $id) {
        if(auth()->user()->manager()->type != 1) {
            return abort(404);
        }
        
        $manager_id = $this->getManagerId($id);
        $validator = Validator::make($request->all(), [
            'fname' => 'required|string|min:2|max:255',
            'lname' => 'required|string|min:2|max:255',
            'address_city' => 'required|string|exists:addresses,code',
            'address_region' => 'nullable|string|exists:addresses,code',
            'address_street' => 'nullable|string|exists:addresses,code',
            'certifcate' => "nullable|string|unique:managers,certifcate,$manager_id|max:255",
            'phone1' => "required|string|unique:managers,phone1,$manager_id|max:255",
            'phone2' => "nullable|string|unique:managers,phone2,$manager_id|max:255",
            'phone3' => "nullable|string|unique:managers,phone3,$manager_id|max:255",
            'fb_link' => 'nullable|string|max:255',
            'attachment.*' => 'mimes:jpg,jpeg,png,pdf,docx,doc|max:5120',
            'attachment_to_delete.*' => 'distinct'
        ]);
        if ($validator->fails()) {
            return redirect()->back()
                ->with(['success' => 'false', "message" => "فشلت عملية حفظ بيانات المشرف"])
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
            $manager->certifcate = $request->certifcate;
            $manager->save();

            /**Delete Attachments */
            if($request->has("attachment_to_delete"))
            {
                Attachment::whereIn('id', $request->attachment_to_delete)->update(['mark_for_delete' => true]);
            }
            
            $directory = Config::get('constants.users.attachments.path')."/$user->path";
            $userAttachmentList = collect();
            if($request->hasfile('attachment'))
             {
                foreach($request->file('attachment') as $file)
                {
                    $extension = $file->extension();
                    $file_name = "attachment_".Str::random(5).".$extension";
                    $attachment = new Attachment();
                    $attachment->path = $file_name;
                    $attachment->type = FileHelper::getFileType($extension);
                    $attachment->save();
                    $userAttachment = ['user_id' => $user->id, 'attachment_id' => $attachment->id];
                    $userAttachmentList->push($userAttachment);

                    Storage::disk("public")->putFileAs($directory, $file, $file_name);
                }
                UserAttachment::insert($userAttachmentList->all());
            }
        });
     }
      catch (Exception $e) {
          return redirect()->back()
            ->with(['success' => 'false', "message" => "فشلت عملية حفظ بيانات المشرف"])
            ->withInput();
      }

      return redirect()->back()
            ->with(['success' => 'true', "message" => "تم حفظ بيانات المشرف بنجاح"]);
    } 

    private function getManagerId($user_id)
    {
        $manager = Manager::where('user_id', $user_id)->firstOrFail();
        return $manager->id;
    }
}
