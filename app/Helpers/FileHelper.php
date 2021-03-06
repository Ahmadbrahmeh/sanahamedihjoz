<?php


namespace App\Helpers;
use Str;

class FileHelper
{
    private static $images = "jpg,jpeg,png";
    private static $doc = "doc,docx";
    private static $pdf = "pdf";

    public static function getFileType($extension)
    {
        $imagesCollection =  Str::of(self::$images)->explode(',');
        $docCollection = Str::of(self::$doc)->explode(',');
        $pdfCollection = Str::of(self::$pdf)->explode(',');
        if ($imagesCollection->contains($extension))
        {
            return "image";
        }
        else if ($docCollection->contains($extension))
        {
            return "doc";
        }
        else if($pdfCollection->contains($extension))
        {
            return "pdf";
        }

        return "other";
    }
}

