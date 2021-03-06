<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use Hash;
use Validator;
use DB;



class ActivateManagerController extends Controller
{
    public function show()
    {
        if(Auth::check() && auth()->user()->first_login){
            return view('manager.pages.first-login');
        }
        return redirect('login');
    }

    public function activate(Request $request)
    {
        if(Auth::check() && auth()->user()->first_login){
        
            $validator = Validator::make($request->all(), [
                'password' => 'required',
                'password_confirmation' => 'required|same:password',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->with(['success' => 'false', "message" => "فشلت عملية تفعيل الحساب, الرجاء التواصل مع مدير النظام "]);
            }
             try {
                 DB::transaction(function() use ($request) {
                    $user = User::findOrFail(auth()->user()->id);
                    $user->password = Hash::make($request->password);
                    $user->first_login = false;
                    $user->save();
                });
           }
            catch (Exception $e) {
                return redirect()->back()
                  ->with(['success' => 'false', "message" => "فشلت عملية تفعيل الحساب, الرجاء التواصل مع مدير النظام "]);
            }
        }

        return redirect()->route('home');
    }
}