<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DataTables;
use App\Helpers\UserHelper;
use DB;

class LookupCurrencyController extends Controller
{
    public function index()
    {
        return view('manager.pages.currency-lookup');
    }

    public function getCurrencies()
    {
        $currencies = DB::table('currencies')->select('*')
        ->where("organization_id", UserHelper::getManagerOrganizationId())
        ->orWhere("default", true)
        ->get();

        $currencies = $currencies->map(function ($currency) {
            $currency->created_at =  date('d/m/Y', strtotime($currency->created_at));
            return $currency;
        });

        return DataTables::of($currencies)->make(true);
    }
}
