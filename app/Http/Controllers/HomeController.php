<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if(Auth::check() && auth()->user()->isAdmin == 1){
            return redirect()->route("admin-currency-lookup");
        }
        else if(Auth::check() && auth()->user()->isAdmin != 1){
            return redirect()->route("reservation-calender");
        }
        return redirect('login');
    }
}
