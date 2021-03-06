<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Validator;
use App\Manager;
use App\User;
use App\UserAttachment;
use App\Attachment;
use App\Address;
use App\Helpers\UserHelper;
use App\Helpers\FileHelper;
use Hash;
use Exception;
use Illuminate\Support\Str;
use Storage;
use Config;
use DateTime;

class AddModeratorController extends Controller
{

    
    /**
     * Display add moderator view
     * @return add moderator view
     */
    public function show() {
        if(auth()->user()->manager()->type != 1) {
            return abort(404);
        }
        
        $maxAttachments =  intval(Config::get('constants.users.attachments.max'));
        $cities = Address::where("type", "city")->where("mark_for_delete", false)->get();
        return view('manager.pages.moderator-add')
            ->with(['maxAttachments' => $maxAttachments,
                    'cities' => $cities,
                    'attachments_count' => 0,
                    'action' => 'add']);
    }

    public function add(Request $request) {
        if(auth()->user()->manager()->type != 1) {
            return abort(404);
        }
        
        $validator = Validator::make($request->all(), [
            'fname' => 'required|string|min:2|max:255',
            'lname' => 'required|string|min:2|max:255',
            'address_city' => 'required|string|exists:addresses,code',
            'address_region' => 'nullable|string|exists:addresses,code',
            'address_street' => 'nullable|string|exists:addresses,code',
            'email' => 'required|email|unique:users',
            'certifcate' => 'nullable|string|unique:managers|max:255',
            'phone1' => 'required|string|unique:managers|max:255',
            'phone2' => 'nullable|string|unique:managers|max:255',
            'phone3' => 'nullable|string|unique:managers|max:255',
            'fb_link' => 'nullable|string|max:255',
            'temp_password' => 'required|string',
            'attachment.*' => 'mimes:jpg,jpeg,png,pdf,docx,doc|max:5120'
        ]);
            
        if ($validator->fails()) {
            return redirect()->back()
                ->with(['success' => 'false', "message" => "فشلت عملية اضافة مشرف"])
                ->withErrors($validator)
                ->withInput();
        }

         try {
            DB::transaction(function() use ($request) {
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
            $user = new User();
            $user->fname = $request->fname;
            $user->lname = $request->lname;
            $user->email = $request->email ;
            $user->path = "user_".Str::random(12);
            $user->temp_password =  $request->temp_password;
            $user->password = Hash::make($request->temp_password);
            $user->save();

            $manager = new Manager();
            $manager->user_id = $user->id;
            $manager->address_id = $address->id;
            $manager->type = 2; /* type 2 => moderator */
            $manager->phone1 = $request->phone1;
            $manager->phone2 = $request->phone2;
            $manager->phone3 = $request->phone3;
            $manager->fb_link = $request->fb_link;
            $manager->certifcate = $request->certifcate;
            $manager->organization_id = UserHelper::getManagerOrganizationId();
            $manager->save();

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
            ->with(['success' => 'false', "message" => "فشلت عملية اضافة مشرف"])
            ->withInput();
      }

        return redirect("manager/moderator/lookup")
                    ->with(['success' => 'true', "message"=>"تم اضافة المستخدم الفرعي بنجاح"]);
    }
}
