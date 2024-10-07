<?php

declare(strict_types=1);

namespace Empaphy\Colorphul\Contrast;

use matthieumastadenis\couleur\ColorInterface;
use matthieumastadenis\couleur\colors\Rgb;
use function Empaphy\Colorphul\utils\apca\lightness_contrast;

/**
 * SAPC APCA - Advanced Perceptual Contrast Algorithm.
 *
 * Functions to parse color values and determine Lc contrast.
 *
 * @version Beta 0.1.9 W3 • contrast function only
 * @see https://github.com/Myndex/SAPC-APCA
 * @see https://github.com/Myndex/apca-w3
 */
class Apca extends ContrastConformance
{
    public static function max(ContrastPolarity $polarity): float
    {
        static $black = new Rgb(0, 0, 0);
        static $white = new Rgb(255, 255, 255);

        [$txt, $bg] = $polarity->isReverse() ? [$white, $black] : [$black, $white];

        return lightness_contrast($txt, $bg);
    }

    public static function min(ContrastPolarity $polarity): float
    {
        static $black = new Rgb(0, 0, 0);

        return lightness_contrast($black, $black);
    }

    public function contrast(ColorInterface $text, ColorInterface $background): float
    {
        return lightness_contrast($text->toRgb(), $background->toRgb());
    }

    public static function normalize(float $cL): float
    {
        return $cL;
    }
}
