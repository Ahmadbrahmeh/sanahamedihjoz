<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Weekday;
use App\Organization;
use App\OrganizationCurrency;
use App\ExhangeRate;
use App\Helpers\UserHelper;
use App\Helpers\TimeHelper;
use DB;
use Validator;
use Carbon\Carbon;

class OrganizationInfoSettingsController extends Controller
{
    public function show()
    {
        $working_days = Weekday::select('saturday', 'sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday')
            ->where("organization_id", UserHelper::getManagerOrganizationId())->firstOrFail();
        $working_days = collect($working_days->toArray())->map(function ($value) {
            return $value ? "checked" : ""; 
        });

        $organizationCurrency = OrganizationCurrency::select("currency_id")
            ->where('organization_id', UserHelper::getManagerOrganizationId())->firstOrFail();

        $currencies = ExhangeRate::select("currencies.id", "currencies.name")
            ->join("currencies", "currencies.id", "exhange_rates.from")
            ->where("exhange_rates.to", $organizationCurrency->currency_id)
            ->where("currencies.organization_id", UserHelper::getManagerOrganizationId())->get();

        $default_currencies = ExhangeRate::select("currencies.id", "currencies.name")
            ->join("currencies", "currencies.id", "exhange_rates.from")
            ->where("currencies.default", true)->get();
        
        $currencies = $currencies->merge($default_currencies);

        $organization = Organization::select('name', 'from_time', 'to_time', 'prepare_duration')
            ->join('managers', 'managers.organization_id', 'organizations.id')
            ->where('managers.user_id', auth()->user()->id)
            ->where('managers.type', 1)
            ->firstOrFail();

        $prepare_duration = TimeHelper::minutesConvertToTime($organization->prepare_duration);
        $organization->prepare_duration_minutes = $prepare_duration['minutes'];
        $organization->prepare_duration_hours = $prepare_duration['hours'];
        $organization->default_currency = $organizationCurrency->currency_id;
        
        return view('manager.pages.organization-info')
            ->with([
                'working_days' => $working_days,
                'currencies' => $currencies,
                'organization' => $organization,
            ]);
    }

    public function save(Request $request)
    {
        try {
            $request->from_time = Carbon::parse($request->from_time)->format('H:i');
            $request->to_time = Carbon::parse($request->to_time)->format('H:i');
            $formated_to_time = Carbon::createFromFormat('H:i', $request->to_time);
            if($formated_to_time->isStartOfDay()) {
                $formated_to_time = $formated_to_time->subSecond();
            }
            $request['from_time'] = $request->from_time;
            $request['to_time'] = $formated_to_time->format("H:i");
        } catch (\Exception $e) {
            return redirect()->back()
            ->with(['success' => 'false', "message" => "فشلة عملية تحديث اعدادات المؤسسة"])
            ->withInput();
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'from_time' => 'required|string|date_format:H:i',
            'to_time' => 'required|string|date_format:H:i|after:from_time',
            'currency' => 'required|exists:currencies,id',
            'preparation_minutes' => 'required|integer|min:0|max:59',
            'preparation_hours' => 'required|integer|min:0|max:12',
            'saturday' =>  'nullable|string',
            'sunday' =>  'nullable|string',
            'monday' =>  'nullable|string',
            'tuesday' =>  'nullable|string',
            'wednesday' =>  'nullable|string',
            'thursday' =>  'nullable|string',
            'friday' =>  'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->with(['success' => 'false', "message" => "فشلة عملية تحديث اعدادات المؤسسة"])
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::transaction(function() use ($request) {
            $organization_id = UserHelper::getManagerOrganizationId();
            $prepare_duration = TimeHelper::timeConvertToMinutes($request->preparation_hours, $request->preparation_minutes);

            $organization = Organization::where('id', $organization_id)->firstOrFail();
            $organization->name = $request->name;
            $organization->from_time =  $request->from_time;
            $organization->to_time =   $request->to_time;
            $organization->prepare_duration = $prepare_duration;
            $organization->save();

            $organization_currency = OrganizationCurrency::where('organization_id', $organization_id)->firstOrFail();
            $organization_currency->currency_id = $request->currency;
            $organization_currency->save();

            $weekday = Weekday::where('organization_id', $organization_id)->firstOrFail();
            $weekday->saturday  = ($request->saturday === "on");
            $weekday->sunday  = ($request->sunday === "on");
            $weekday->monday  = ($request->monday === "on");
            $weekday->tuesday  = ($request->tuesday === "on");
            $weekday->wednesday  = ($request->wednesday === "on");
            $weekday->thursday  = ($request->thursday === "on");
            $weekday->friday  = ($request->friday === "on");
            $weekday->save();
        });
     }
      catch (Exception $e) {
          return redirect()->back()
            ->with(['success' => 'false', "message"  => "فشلة عملية تحديث اعدادات المؤسسة"])
            ->withInput();
      }

      return redirect()->back()
        ->with(['success' => 'true', "message" => "تم تحديث اعدادات المؤسسة بنجاح"]);

    }
}
