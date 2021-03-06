<?php

namespace App\Http\Controllers;
use DB;
use Validator;
use Hash;
use App\User;
use Illuminate\Http\Request;

class ChangePasswordController extends Controller
{
    public function show()
    {
        return view('manager.pages.change-password');
    }

    public function save(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password_old' => 'required',
            'password' => 'required',
            'password_confirmation' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->with(['success' => 'false', "message" => "فشلت عملية تغيير كلمة المرور"]);
        }

        $oldPassword = $request->password_old;
        $newPassword = Hash::make($request->password);
        $user = User::findOrFail(auth()->user()->id); 
        $currentPassword = $user->password;
        $result = false;
        if(Hash::check($oldPassword, $currentPassword)) {
            $user->password = $newPassword;
            $result = $user->save();
        }

        if(!$result){
            return redirect()->back()
                ->with(['success' => 'false', "message" => "الرجاء التأكد من كلمة المرور الحالية"]);
        }

        return redirect()->back()
            ->with(['success' => 'true', "message"=>"تم تغيير كلمة المرور بنجاح"]);
    }
}