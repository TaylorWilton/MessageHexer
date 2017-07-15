<?php

class Hexer
{
    public static function CreateImage(string $input, string $channel)
    {
        $hexInput = bin2hex($input);
        $colourStrings = self::hexToColours($hexInput, $channel);

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
     * @param string $hexInput
     * @param string $channel
     * @return array
     * @throws TypeError
     */
    protected static function hexToColours(string $hexInput, string $channel): array
    {
        switch ($channel) {
            case $channel === Channel::ALL:
                // hex colours are 6 chars long, so we get an array of them
                $colourStrings = str_split($hexInput, 6);
                return self::createColourStrings($colourStrings, STR_PAD_RIGHT);

            case $channel === Channel::RED:
                $colourStrings = str_split($hexInput, 2);
                return self::createColourStrings($colourStrings, STR_PAD_RIGHT);

            case $channel === Channel::GREEN:
                $colourStrings = str_split($hexInput, 2);
                return self::createColourStrings($colourStrings, STR_PAD_BOTH);

            case $channel === Channel::BLUE:
                $colourStrings = str_split($hexInput, 2);
                return self::createColourStrings($colourStrings, STR_PAD_LEFT);

            default:
                throw new InvalidArgumentException("Not a valid Color Channel");
        }
    }

    /**
     * @param $pixelHexCode
     * @param $image
     * @return int
     */
    protected
    static function allocateColour(
        $pixelHexCode,
        $image
    ): int {
        $red = hexdec(substr($pixelHexCode, 0, 2));
        $green = hexdec(substr($pixelHexCode, 2, 2));
        $blue = hexdec(substr($pixelHexCode, 4, 2));

        $color = imagecolorallocate($image, $red, $green, $blue);
        return $color;
    }

    /**
     * @param $colourStrings
     * @param int $padType
     * @return array
     */
    protected
    static function createColourStrings(
        $colourStrings,
        int $padType
    ): array {
        $colourStrings = array_map(function ($element) use ($padType) {
            $element = strtoupper($element);
            $len = sizeof($element);
            if ($len === 6) {
                return $element;
            }
            $lenToPad = 6 - $len + 1;
            return str_pad($element, $lenToPad, "0", $padType);
        }, $colourStrings);
        return $colourStrings;
    }
}
