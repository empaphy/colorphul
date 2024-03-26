<?php

declare(strict_types=1);

namespace Empaphy\Colorphul;

use LogicException;
use matthieumastadenis\couleur\ColorInterface;
use matthieumastadenis\couleur\colors\OkLch;

class Wcag
{
    public static function colorContrastRatio(ColorInterface $fgColor, ColorInterface $bgColor): float
    {
        $luminances = [self::relativeLuminance($fgColor), self::relativeLuminance($bgColor)];
        rsort($luminances, SORT_NUMERIC);
        [$l1, $l2] = $luminances;

        return self::contrastRatio($l1, $l2);
    }

    /**
     * @param  float  $l1  The relative luminance of the lighter of the colors.
     * @param  float  $l2  The relative luminance of the darker of the colors.
     * @return float The contrast ratio.
     */
    public static function contrastRatio(float $l1, float $l2): float
    {
        if ($l1 < $l2) {
            throw new LogicException('The first argument must be the relative luminance of the lighter color.');
        }

        return ($l1 + 0.05) / ($l2 + 0.05);
    }

    public static function relativeLuminance(ColorInterface $color): float
    {
        return $color->toXyzD65()->y;
    }


    /**
     * Find the lightness for a background color that makes both the brighter and darker text equally readable.
     */
    public static function reverseDarker(float $contrastRatio, ColorInterface $lighterColor): OkLch {
        $lighterColor = $lighterColor->toOkLch();
        $darkerColor  = $lighterColor->change(lightness: 0);

        // Define an initial lightness.
        $lightness = $lighterColor->lightness / 2;

        $i = 0;

        // Approximate the lightness for the target contrast ratio.
        do {
            $darkerColor = $darkerColor->change(lightness: $lightness);

            $c = Wcag::colorContrastRatio($lighterColor, $darkerColor);

            $diff = round($c - $contrastRatio, 14);

            $lightness += 10 * $diff / 2;
        } while (0.0 !== $diff && $i++ < 100);

        return $darkerColor;
    }

    /**
     * Reverse the luminance of the lighter color for the given contrast ratio and darker luminance.
     */
    public static function reverseLighter(float $contrastRatio, ColorInterface $darkerColor): ColorInterface
    {
        $darkerColor  = $darkerColor->toOkLch();
        $lighterColor = $darkerColor->change(lightness: 100);

        // Define an initial lightness.
        $lightness = $darkerColor->lightness / 2 + 50;

        $i = 0;

        // Approximate the lightness for the target contrast ratio.
        do {
            $lighterColor = $lighterColor->change(lightness: $lightness);

            $c = Wcag::colorContrastRatio($lighterColor, $darkerColor);

            $diff = round($c - $contrastRatio, 14);

            $lightness -= 10 * $diff / 2;
        } while (0.0 !== $diff && $i++ < 100);

        return $lighterColor;
    }
}
