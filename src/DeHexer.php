<?php

class DeHexer
{
    static public function parseImage(string $filename, string $channel): string
    {
        $image = imagecreatefrompng($filename);
        $width = imagesx($image);
        $height = imagesy($image);

        $pixels = [];
        for ($x = 0; $x < $width; $x++) {
            for ($y = 0; $y < $height; $y++) {
                $color = imagecolorat($image, $x, $y);
                if ($channel === Channel::ALL) {
                    $pixels[] = $color;
                } else {
                    $pixels[] = self::extractChannel($color, $channel);
                }
            }
        }

        $message = array_map(function ($pixel) {
            $dec = dechex($pixel);
            return hex2bin($dec);
        }, $pixels);

        return implode("", $message);
    }

    static private function extractChannel($rgb, $channel)
    {
        $r = ($rgb >> 16) & 0xFF;
        $g = ($rgb >> 8) & 0xFF;
        $b = $rgb & 0xFF;

        switch ($channel) {
            case $channel === Channel::RED:
                return $r;
            case $channel === Channel::GREEN:
                return $g;
            case $channel === Channel::BLUE:
                return $b;
            default:
                throw new InvalidArgumentException("Not a valid channel");
        }
    }
}
