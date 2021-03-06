<?php


namespace App\Helpers;

class TimeHelper
{
    public static function timeConvertToMinutes($hours, $minutes)
    {
       return ($hours * 60) + $minutes;
    }

    public static function minutesConvertToTime($duration)
    {
       $hours = ($duration / 60);
       $rhours = floor($hours);
       $minutes = ($hours - $rhours) * 60;
       $rminutes = round($minutes);
       return ['hours' => $rhours, 'minutes' => $rminutes];
    }
}