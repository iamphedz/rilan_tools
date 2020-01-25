<?php

namespace App\Http\CustomClass;

use InterventionImage;
use Illuminate\Support\Str;

class Image extends InterventionImage
{

    /**
     * custom random name generator
     * 
     * @param int $length
     * @return string
     */
    static function generateName($length = 20)
    {
        $charset = "abcdefghijklmnopqrstuvwxyz0123456789";
        $string = "";
        for ($i = 1; $i <= $length; $i++) {
            $string .= substr($charset, rand(0, 35), 1);
        }

        return $string;
    }

    /**
     * Saves image to specified folder
     * 
     * @param image $image
     * @param string $path
     * @param string $image_name
     * @return string
     */
    static function saveImage($image, $path, $image_name = null)
    {

        $image_name = !$image_name ?
            self::generateName() . "." . $image->getClientOriginalExtension() : $image_name . "." . $image->getClientOriginalExtension();
        $image_path = $path . "/" . $image_name;

        $image->move($path, $image_name);

        InterventionImage::make($image_path)
            ->fit(700, 700, null)
            ->save();

        return $image_path;
    }

    /**
     * Removes the image folder
     * 
     * @param string $folder name
     * @return bool
     */
    static function removeImageFolder($path)
    {
        array_map('unlink', glob($path . "/*"));
        if (file_exists($path))
        rmdir($path);

        return true;
    }
}
