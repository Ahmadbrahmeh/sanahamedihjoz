<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Setting;
use DB;
use Validator;
use App\Helpers\TimeHelper;

class SystemSettingsController extends Controller
{
    public function show()
    {
        $currencies = DB::table('currencies')->select('*')
            ->where('default', true)->get();

        $settings = Setting::select("currency_id as default_currency", "prepare_duration")
            ->where('user_id', auth()->user()->id)->firstOrFail();
        $prepare_duration = TimeHelper::minutesConvertToTime($settings->prepare_duration);
        $settings->prepare_duration_minutes = $prepare_duration['minutes'];
        $settings->prepare_duration_hours = $prepare_duration['hours'];

        return view('admin.pages.settings-system')->with(['currencies' => $currencies, 'settings' => $settings]);
    }

    public function save(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'currency' => 'required|exists:currencies,id',
            'preparation_minutes' => 'required|integer|min:0|max:59',
            'preparation_hours' => 'required|integer|min:0|max:12',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with(['success' => 'false', "message" => "فشلت عملية تحديث اعدادات النظام "]);
        }
        
        $prepare_duration = TimeHelper::timeConvertToMinutes($request->preparation_hours, $request->preparation_minutes);
        $settings = Setting::select("settings.id")
            ->join("currencies", "currencies.id", "settings.currency_id")
            ->where("default", true)->firstOrFail();
        
        $settings->currency_id = $request->currency;
        $settings->prepare_duration = $prepare_duration;
        $result = $settings->save();
        
        if (!$result) {
            return redirect()->back()
            ->with(['success' => 'false', "message" => "فشلت عملية تحديث اعدادات النظام"]);
        }

        return redirect()->back()
            ->with(['success' => 'true', "message" => "تم تحديث اعدادات النظام بنجاح"]);
    }
}