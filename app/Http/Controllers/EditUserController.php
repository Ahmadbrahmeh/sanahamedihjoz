<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Validator;
use App\Manager;
use App\User;
use App\UserAttachment;
use App\Attachment;
use App\Weekday;
use App\Organization;
use App\Address;
use App\Helpers\FileHelper;
use Exception;
use Illuminate\Support\Str;
use Storage;
use Config;
use Carbon\Carbon;

class EditUserController extends Controller
{
    /**
     * Display edit user view
     * @return users-add view
     */
    public function show($id) 
    {
        $maxAttachments =  intval(Config::get('constants.users.attachments.max'));

        $user = User::select('users.id', 'users.fname', 'users.lname', 'users.email', 'users.created_at', 'users.updated_at',
                'users.path','users.temp_password', 'managers.phone1', 'managers.phone2', 'managers.phone3','managers.type',
                'managers.fb_link', 'managers.certifcate','managers.organization_id','organizations.name as organization_name',
                'organization_types.name as organization_type','users.first_login as active', 'organizations.from_time',
                'organizations.to_time', 'managers.address_id')
            ->join('managers', 'users.id', 'managers.user_id')
            ->join('organizations', 'managers.organization_id', 'organizations.id')
            ->join('organization_types', 'organizations.type', 'organization_types.type')
            ->where('users.mark_for_delete', false)
            ->where('managers.user_id', $id)
            ->where('managers.type', 1)
            ->firstOrFail();
        $address_types = collect([]);
        $current_address = Address::where("id", $user->address_id)->first();
        while($current_address != null) 
        {
            $address_types[$current_address->type] = $current_address;
            $current_address = $current_address->parent();
        }
        $cities = Address::where("type", "city")->where("mark_for_delete", false)->get();
        $regions = !isset($address_types["region"]) ? [] : $address_types["city"]->children()->where("mark_for_delete", false)->get();
        $streets = !isset($address_types["street"]) ? [] : $address_types["region"]->children()->where("mark_for_delete", false)->get();

        $working_days = Weekday::select('saturday', 'sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday')
            ->where("organization_id", $user->organization_id)->firstOrFail();
        
        $working_days = collect($working_days->toArray())->map(function ($value) {
            return $value ? "checked" : ""; 
        });

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

        $attachments_count = $attachments->count();
        $icons = Config::get('constants.attachments.icons');

        return view('admin.pages.users-add')
            ->with(['maxAttachments' => $maxAttachments,
                'attachments_count' => $attachments_count,
                "user" => $user,
                'working_days' => $working_days,
                "attachments" => $attachments,
                "cities" => $cities,
                "regions" => $regions,
                "streets" => $streets,
                "address_types" => $address_types,
                "icons" => $icons,
                'action' => 'edit']);
    }

    public function save(Request $request, $id) {
        try {
            $request->from_time = Carbon::parse($request->from_time)->format('H:i');
            $request->to_time = Carbon::parse($request->to_time)->format('H:i');
            $request['from_time'] = $request->from_time;
            $request['to_time'] = $request->to_time;
            $formated_to_time = Carbon::createFromFormat('H:i', $request->to_time);
            if($formated_to_time->isStartOfDay()) {
                $formated_to_time = $formated_to_time->subSecond();
            }
            $request['from_time'] = $request->from_time;
            $request['to_time'] = $formated_to_time->format("H:i");
        } catch (\Exception $e) {
            return redirect()->back()
            ->with(['success' => 'false', "message" => "فشلت عملية حفظ بيانات المستخدم"])
            ->withInput();
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
            'attachment_to_delete.*' => 'distinct',
            'organization_name' => 'required|string',
            'from_time' => 'required|string|date_format:H:i',
            'to_time' => 'required|string|date_format:H:i|after:from_time',
            'saturday' =>  'nullable|string',
            'sunday' =>  'nullable|string',
            'monday' =>  'nullable|string',
            'tuesday' =>  'nullable|string',
            'wednesday' =>  'nullable|string',
            'thursday' =>  'nullable|string',
            'friday' =>  'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->with(['success' => 'false', "message" => "فشلت عملية حفظ بيانات المستخدم"])
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
            $organization_id = $this->getOrganizationId($id);

            $organization = Organization::where('id', $organization_id)->firstOrFail();
            $organization->name = $request->organization_name;
            $organization->from_time =  $request->from_time;
            $organization->to_time =  $request->to_time;
            $organization->save();

            $weekday = Weekday::where('organization_id', $organization_id)->firstOrFail();
            $weekday->saturday  = ($request->saturday === "on");
            $weekday->sunday  = ($request->sunday === "on");
            $weekday->monday  = ($request->monday === "on");
            $weekday->tuesday  = ($request->tuesday === "on");
            $weekday->wednesday  = ($request->wednesday === "on");
            $weekday->thursday  = ($request->thursday === "on");
            $weekday->friday  = ($request->friday === "on");
            $weekday->save();

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
            ->with(['success' => 'false', "message" => "فشلت عملية حفظ بيانات المستخدم"])
            ->withInput();
      }

      return redirect()->back()
            ->with(['success' => 'true', "message" => "تم حفظ بيانات المستخدم بنجاح"]);
    } 

    private function getManagerId($user_id)
    {
        $manager = Manager::where('user_id', $user_id)->firstOrFail();
        return $manager->id;
    }

    private function getOrganizationId($user_id)
    {
        $manager = Manager::where('user_id', $user_id)->firstOrFail();
        return $manager->organization_id;
    }
}
