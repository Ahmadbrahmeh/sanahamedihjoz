<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\UserHelper;
use App\OrganizationCurrency;
use App\Organization;
use App\ExhangeRate;
use App\Currency;
use App\Setting;
use DB;
use Validator;

class ExchangeCurrencyAdminController extends Controller
{
    public function show()
    {
        $system_currency = Setting::select("currencies.name", "settings.currency_id")
            ->where('settings.user_id', auth()->user()->id)
            ->join('currencies', 'currencies.id', 'settings.currency_id')->firstOrFail();

        $currencies = DB::table('currencies')->select('*')
            ->where('default', true)
            ->where("id", "!=", $system_currency->currency_id)->get();

        return view('admin.pages.currency-exchange')
            ->with(['system_currency' => $system_currency,
            "currencies" => $currencies]);;
    }

    public function getExhangeRate($id)
    {
        $currency_from = Setting::select("currency_id")
            ->where('user_id', auth()->user()->id)->firstOrFail();

        $currency_from_value = $currency_from->currency_id;
        $currency_to_value =  $id;
        $exchange_rate = ExhangeRate::select("value")
            ->where('from', $currency_from_value)
            ->where('to', $currency_to_value)
            ->where('default', true)->first();
            
        $exhange_rate_value = 0;
        $have_exhange_rate = false;
        if($exchange_rate != null)
        {
            $exhange_rate_value = $exchange_rate->value;
            $have_exhange_rate = true;
        }
        $result = ['have_exhange_rate' => $have_exhange_rate, 'value' =>  $exhange_rate_value];
        return response()->json(['status' => true, 'result' => $result], 200,
            array('Content-Type' => 'application/json;charset=utf8'), JSON_UNESCAPED_UNICODE);
    }

    public function save(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'to_currency' => 'required|string|exists:currencies,id',
            'exchange_rate' => 'required|min:0.01'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with(['success' => 'false', "message" => "فشلت عملية تحديث سعر الصرف "]);
        }

        $currency_from = Setting::select("currency_id")
            ->where('user_id', auth()->user()->id)->firstOrFail();
        $currency_from_value = $currency_from->currency_id;

        $exhange_rate = ExhangeRate::select("id")
            ->where('from', $currency_from_value)
            ->where('to', $request->to_currency)
            ->where('default', true)->first();

        $correspond_exchange_rate = ExhangeRate::select("id")
            ->where('from', $request->to_currency)
            ->where('to', $currency_from_value)
            ->where('default', true)->first();
        $status = false;
        if($exhange_rate == null){
             /** Add two exchange rates for Organization the exhange rate and corresponding
             * From Currency1 to Currency2 = exhange rate
             * From Currency2 to Currency1 = 1/exhange rate
             */
            $correspond_exchange_rate_value = round(1/$request->exchange_rate, 3);
            $status1 = $this->addExchangeRate($currency_from_value, $request->to_currency, $request->exchange_rate);
            $status2 = $this->addExchangeRate($request->to_currency, $currency_from_value, $correspond_exchange_rate_value);
            $status = $status1 && $status2;
        }
        else
        {
            /** Update two exchange rates for Organization and corresponding
             * From Currency1 to Currency2 = exhange rate
             * From Currency2 to Currency1 = 1/exhange rate
             */
            $exhange_rate_id = $exhange_rate->id;
            $correspond_exchange_rate_id = $correspond_exchange_rate->id;
            $correspond_exchange_rate_value =  round(1/$request->exchange_rate, 3);
            $status1 = $this->updateExchangeRate($exhange_rate_id, $request->exchange_rate);
            $status2 = $this->updateExchangeRate($correspond_exchange_rate_id, $correspond_exchange_rate_value);
            $status = $status1 && $status2;
        }

        if (!$status) {
            return redirect()->back()
                ->with(['success' => 'false', "message" => "فشلت عملية تحديث سعر الصرف "]);
        }

        return redirect()->route('admin-currency-exchange')
            ->with(['success' => 'true', "message"=>"تم تحديث عملة الصرف بنجاح"]); 
    }

    private function addExchangeRate($from, $to, $value)
    {
        $organization = Organization::select("id")->where("type", 0)->first();
        $value = round($value, 3);
        $exchange_rate = new ExhangeRate();
        $exchange_rate->from = $from;
        $exchange_rate->to = $to;
        $exchange_rate->value = $value;
        $exchange_rate->default = true;
        $exchange_rate->created_by = auth()->user()->id;
        $exchange_rate->updated_by = auth()->user()->id;
        $exchange_rate->organization_id = $organization->id;
        return $exchange_rate->save();
    }

    private function updateExchangeRate($id, $value)
    {
        $value = round($value, 3);
        $exchange_rate =  ExhangeRate::find($id);
        $exchange_rate->value = $value;
        $exchange_rate->updated_by = auth()->user()->id;
        return $exchange_rate->save();
    }
}