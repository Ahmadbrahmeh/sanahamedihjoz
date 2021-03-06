<?php

namespace App\Http\Controllers\Auth;


use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use \Validator;
use Illuminate\Support\Facades\Auth;
use App\User;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {
        $messages = [
            "email.required" => "Email is required",
            "email.email" => "Email is not valid",
            "email.exists" => "Email doesn't exists",
            "password.required" => "Password is required",
            "password.min" => "Password must be at least 8 characters"
        ];
        // validate the form data
        $validator = Validator::make($request->all(), [
                'email' => 'required|email|exists:users,email',
                'password' => 'required|min:8',
            ], $messages);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        } else {
            $user = User::where('email', $request->email)->firstOrFail();
            if($user->block) {
                return back()->withErrors([
                    'block' => 'Blocked user.',
                ]);
            }
            // attempt to log
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password ], $request->remember)) {
                // if successful -> redirect forward
                return redirect()->intended($this->redirectTo());
            }

            // if unsuccessful -> redirect back
            return redirect()->back()->withInput($request->only('email', 'remember'))->withErrors([
                'password' => 'Wrong password.',
            ]);
        }
    }

    public function logout(Request $request) {
        Auth::logout();
        return redirect()->route("login");
    }

    protected function redirectTo()
    {
        if(Auth::check() && auth()->user()->isAdmin == 1)
        {
            return "/admin/currency/lookup";
        }
        else if(Auth::check() && auth()->user()->isAdmin == 0)
        {
            return "/manager/reservation/calender";
        }
      return 'login';
    }
}
