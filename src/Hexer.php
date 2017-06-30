<?php

class Hexer
{
    public static function CreateImage(string $input)
    {
        $hexInput = bin2hex($input);
        $colourStrings = self::hexToColours($hexInput);

        $width = count($colourStrings);
        $image = imagecreatetruecolor($width, 1);

        foreach ($colourStrings as $key => $pixelHexCode) {

            if (!ctype_xdigit($pixelHexCode)) {
                continue;
            }

            $color = self::allocateColour($pixelHexCode, $image);
            imagesetpixel($image, $key, 0, $color);
        }
        return $image;
    }

    /**
     * @param $hexInput
     * @return array
     */
    protected static function hexToColours($hexInput): array
    {
        // hex colours are 6 chars long, so we get an array of them
        $colourStrings = str_split($hexInput, 6);

        $colourStrings = array_map(function ($element) {
            $element = strtoupper($element);
            $len = sizeof($element);
            if ($len === 6) {
                return $element;
            }
            $lenToPad = 6 - $len + 1;
            return str_pad($element, $lenToPad, "0", STR_PAD_RIGHT);
        }, $colourStrings);
        return $colourStrings;
    }

    /**
     * @param $pixelHexCode
     * @param $image
     * @return int
     */
    private static function allocateColour($pixelHexCode, $image): int
    {
        $red = hexdec(substr($pixelHexCode, 0, 2));
        $green = hexdec(substr($pixelHexCode, 2, 2));
        $blue = hexdec(substr($pixelHexCode, 4, 2));

        $color = imagecolorallocate($image, $red, $green, $blue);
        return $color;
    }
}
