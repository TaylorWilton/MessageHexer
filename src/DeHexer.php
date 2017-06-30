<?php

class DeHexer
{
    static public function parseImage($filename)
    {
        $image = imagecreatefrompng($filename);
        $width = imagesx($image);
        $height = imagesy($image);

        $pixels = [];
        for ($x = 0; $x < $width; $x++) {
            for ($y = 0; $y < $height; $y++) {
                $color = imagecolorat($image, $x, $y);
                $pixels[] = $color;
            }
        }

        $message = array_map(function ($pixel) {
            $dec = dechex($pixel);
            return hex2bin($dec);
        }, $pixels);

        return implode("",$message);
    }
}
