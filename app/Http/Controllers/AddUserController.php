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
use App\OrganizationCurrency;
use App\Address;
use App\Helpers\UserHelper;
use App\Helpers\FileHelper;
use App\Helpers\MainHelper;
use Hash;
use Exception;
use Illuminate\Support\Str;
use Storage;
use Config;
use Carbon\Carbon;

class AddUserController extends Controller
{
    /**
     * Display add user view
     * @return add user view
     */
    public function show() 
    {
        $cities = Address::where("type", "city")->where("mark_for_delete", false)->get();
        $maxAttachments =  intval(Config::get('constants.users.attachments.max'));
        return view('admin.pages.users-add')
            ->with(['maxAttachments' => $maxAttachments,
                    'cities' => $cities,
                    'attachments_count' => 0,
                    'action' => 'add']);
    }

    public function add(Request $request)
    { 
        try {
            $request->from_time = Carbon::parse($request->from_time)->format('H:i');
            $request->to_time = Carbon::parse($request->to_time)->format('H:i');
            $request['from_time'] = $request->from_time;
            $formated_to_time = Carbon::createFromFormat('H:i', $request->to_time);
            if($formated_to_time->isStartOfDay()) {
                $formated_to_time = $formated_to_time->subSecond();
            }
            $request['from_time'] = $request->from_time;
            $request['to_time'] = $formated_to_time->format("H:i");
        } catch (\Exception $e) {
            return redirect()->back()
            ->with(['success' => 'false', "message" => "فشلت عملية اضافة مستخدم"])
            ->withInput();
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
            'organization_name' => 'required|string',
            'organization_type' => 'required|string',
            'from_time' => 'required|string|date_format:H:i',
            'to_time' => 'required|string|date_format:H:i|after:from_time',
            'attachment.*' => 'mimes:jpg,jpeg,png,pdf,docx,doc|max:5120',
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
                ->with(['success' => 'false', "message" => "فشلت عملية اضافة مستخدم"])
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
            $organization = new Organization();
            $organization->name = $request->organization_name;
            $organization->type = $request->organization_type;
            $organization->from_time =  $request->from_time;
            $organization->to_time =  $request->to_time;
            $organization->code = MainHelper::randomString(3);
            $organization->save();

            $organizationAdmin = Organization::select("id")->where("type", 0)->first();
            $default_currency = $organizationAdmin->organizationCurrency()->currency();

            $organizationCurrency = new OrganizationCurrency();
            $organizationCurrency->currency_id = $default_currency->id;
            $organizationCurrency->organization_id = $organization->id;
            $organizationCurrency->save();

            $weekday = new Weekday();
            $weekday->saturday  = ($request->saturday === "on");
            $weekday->sunday  = ($request->sunday === "on");
            $weekday->monday  = ($request->monday === "on");
            $weekday->tuesday  = ($request->tuesday === "on");
            $weekday->wednesday  = ($request->wednesday === "on");
            $weekday->thursday  = ($request->thursday === "on");
            $weekday->friday  = ($request->friday === "on");
            $weekday->organization_id = $organization->id;
            $weekday->save();

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
            $manager->type = 1; /* type 1 => manager */
            $manager->phone1 = $request->phone1;
            $manager->phone2 = $request->phone2;
            $manager->phone3 = $request->phone3;
            $manager->fb_link = $request->fb_link;
            $manager->certifcate = $request->certifcate;
            $manager->organization_id = $organization->id;
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
             ->with(['success' => 'false', "message" => "فشلت عملية اضافة المستخدم"])
             ->withInput();
       }

        return redirect("admin/users/lookup")
                    ->with(['success' => 'true', "message"=>"تم اضافة المستخدم بنجاح"]);
    }

}