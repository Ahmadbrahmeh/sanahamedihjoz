<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DataTables;
use App\Helpers\UserHelper;
use DB;

class LookupCurrencyAdminController extends Controller
{
    public function index()
    {
        return view('admin.pages.currency-lookup');
    }

    public function getCurrencies()
    {
        $currencies = DB::table('currencies')->select('*')
        ->where("default", true)
        ->get();

        $currencies = $currencies->map(function ($currency) {
            $currency->created_at =  date('d/m/Y', strtotime($currency->created_at));
            return $currency;
        });

        return DataTables::of($currencies)->make(true);
    }
}
