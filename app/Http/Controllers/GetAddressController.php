<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Address;
use Validator;
use Config;

class GetAddressController extends Controller
{
    public function list($code)
    {
        $tail = Config::get('constants.addresses.tail');
        $address = Address::where("code", $code)
            ->where("mark_for_delete", false)
            ->where("type","!=", $tail)->firstOrFail();
        $addresses =  $address->children()->select("name", "code")->where("mark_for_delete", false)->get();
        $result = ['addresses' => $addresses];
        return response()->json(['status' => true, 'result' => $result], 200,
            array('Content-Type' => 'application/json;charset=utf8'), JSON_UNESCAPED_UNICODE);
    }
}
