<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\UserHelper;
use App\OrganizationCurrency;
use App\ExhangeRate;
use App\Currency;
use DB;
use Validator;



class ExchangeCurrencyController extends Controller
{
    public function show()
    {
        $organization_currency = OrganizationCurrency::select("currencies.name", "organization_currencies.currency_id")
            ->where('organization_currencies.organization_id', UserHelper::getManagerOrganizationId())
            ->join('currencies', 'currencies.id', 'organization_currencies.currency_id')->firstOrFail();

        $currencies = DB::table('currencies')->select('*')
            ->where(function ($query) {
                $query->where("organization_id", UserHelper::getManagerOrganizationId())
                    ->orWhere("default", true);
            })
            ->where("id", "!=", $organization_currency->currency_id)
            ->get();
        
        return view('manager.pages.currency-exchange')
                    ->with(['organization_currency' => $organization_currency,
                            "currencies" => $currencies]);
    }

    public function getExhangeRate($id)
    {
        $currency_from = OrganizationCurrency::select("currency_id")
            ->where('organization_id', UserHelper::getManagerOrganizationId())
            ->firstOrFail();
        $currency_from_value = $currency_from->currency_id;
        $currency_to_value =  $id;
    
        $exchange_rate_default = ExhangeRate::select("value")
            ->where('from', $currency_from_value)
            ->where('to', $currency_to_value)
            ->where('default', true)->first();

        $exchange_rate_organization = ExhangeRate::select("value")
            ->where('from', $currency_from_value)
            ->where('to', $currency_to_value)
            ->where('default', false)
            ->where('organization_id', UserHelper::getManagerOrganizationId())->first();
        $have_exhange_rate = false;
        $exhange_rate_value = 0;

        if($exchange_rate_organization != null)
        {
            $exhange_rate_value  = $exchange_rate_organization->value;
            $have_exhange_rate = true;
        }
        else if($exchange_rate_default)
        {
            $exhange_rate_value = $exchange_rate_default->value; 
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
        
        $currency_from = OrganizationCurrency::select("currency_id")
            ->where('organization_id', UserHelper::getManagerOrganizationId())
            ->firstOrFail();
        $currency_from_value = $currency_from->currency_id;
        
        $exhange_rate = ExhangeRate::select("value")
            ->where('from', $currency_from_value)
            ->where('to', $request->to_currency)->get();
        $status = false;
        
        $exchange_rate_organization = ExhangeRate::select("id")
            ->where('from', $currency_from_value)
            ->where('to', $request->to_currency)
            ->where('default', false)
            ->where('organization_id', UserHelper::getManagerOrganizationId())->first();

        $correspond_exchange_rate_organization = ExhangeRate::select("id")
            ->where('from', $request->to_currency)
            ->where('to', $currency_from_value)
            ->where('default', false)
            ->where('organization_id', UserHelper::getManagerOrganizationId())->first();
        $status = false;
        if($exhange_rate->isEmpty() || $exchange_rate_organization == null)
        {
            /** Add two exchange rates for Organization the exhange rate and corresponding
             * From Currency1 to Currency2 = exhange rate
             * From Currency2 to Currency1 = 1/exhange rate
             */
            $correspond_exchange_rate = round(1/$request->exchange_rate, 3);
            $status1 = $this->addExchangeRate($currency_from_value, $request->to_currency, $request->exchange_rate);
            $status2 = $this->addExchangeRate($request->to_currency, $currency_from_value, $correspond_exchange_rate);
            $status = $status1 && $status2;
        }
        else
        {
            /** Update two exchange rates for Organization and corresponding
             * From Currency1 to Currency2 = exhange rate
             * From Currency2 to Currency1 = 1/exhange rate
             */
            $exhange_rate_id = $exchange_rate_organization->id;
            $correspond_exchange_rate_id = $correspond_exchange_rate_organization->id;
            $correspond_exchange_rate = round(1/$request->exchange_rate, 3);
            $status1 = $this->updateExchangeRate($exhange_rate_id, $request->exchange_rate);
            $status2 = $this->updateExchangeRate($correspond_exchange_rate_id, $correspond_exchange_rate);
            $status = $status1 && $status2;
        }

        if (!$status) {
            return redirect()->back()
                ->with(['success' => 'false', "message" => "فشلت عملية تحديث سعر الصرف "]);
        }

        return redirect()->route('currency-exchange')
                ->with(['success' => 'true', "message"=>"تم تحديث عملة الصرف بنجاح"]); 
            
    }

    private function addExchangeRate($from, $to, $value)
    {
        $value = round($value, 3);
        $exchange_rate =  new ExhangeRate();
        $exchange_rate->from = $from;
        $exchange_rate->to = $to;
        $exchange_rate->value = $value;
        $exchange_rate->created_by = auth()->user()->id;
        $exchange_rate->updated_by = auth()->user()->id;
        $exchange_rate->organization_id = UserHelper::getManagerOrganizationId();
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