<?php


namespace App\Helpers;
use DB;

class UserHelper
{
    public static function getManagerOrganizationId()
    {
        $userId = auth()->user()->id;
        $manager =  DB::table('managers')
            ->select('organization_id')
            ->where('user_id', '=', $userId)
            ->get()->first();
        return $manager->organization_id;
    }
}

