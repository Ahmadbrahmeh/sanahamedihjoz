<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Hall;
use App\Helpers\UserHelper;

use DB;


class EditHallController extends Controller
{
    /**
     * Direct the user to edit hall view and display current hall data
     * @param $id the id for current hall
     * @return list of current hall attributes values
     */
    public static function show($id) {    
        $hall = Hall::where('id', $id)
                    ->where('mark_for_delete', false)
                    ->where('organization_id', UserHelper::getManagerOrganizationId())->firstOrFail();

        return view('manager.pages.hall-add',
                            ["hall" => $hall,
                            "action" => "edit"]);
        }

    /**
     * Save new hall attributes values in database
     * @param $request current request
     * @return redirect with validation message
     */
    public static function save(Request $request, $id) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|min:2',
            'price' => 'required|integer|min:1',
            'capacity' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with(['success' => 'false', "message"=>"فشلت عملية تعديل معلومات القاعة"]);
        }

        $hall = Hall::where('id', $id)
                    ->where('organization_id', UserHelper::getManagerOrganizationId())->first();

        $hall->name = $request->name;
        $hall->price = $request->price;
        $hall->capacity = $request->capacity;
        $result = $hall->save();

        if (!$result) {
            return redirect()->back()
            ->with(['success' => 'false', "message" => "فشلت عملية تعديل معلومات القاعة"]);
        }

        return redirect()->back()
            ->with(['success' => 'true', "message"=>"تم تعديل معلومات القاعة بنجاح"]);
    }
}