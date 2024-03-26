<?php

declare(strict_types=1);

namespace Empaphy\Colorphul;

use matthieumastadenis\couleur\ColorInterface;
use matthieumastadenis\couleur\colors\Hsl;
use matthieumastadenis\couleur\ColorSpace;
use function matthieumastadenis\couleur\utils\toArray;

class Util
{
    public const CHROMA_CLIP_PRECISION = 14;

    public static function calculateAverageRgbToOkLchHueOffset(float $lightness): float
    {
        $red   = new Hsl(0,   100, $lightness);
        $green = new Hsl(120, 100, $lightness);
        $blue  = new Hsl(240, 100, $lightness);

        $hueOffsets = [
            $red->toOkLch()->hue - $red->hue,
            $green->toOkLch()->hue - $green->hue,
            $blue->toOkLch()->hue - $blue->hue,
        ];

        return array_sum($hueOffsets) / count($hueOffsets);
    }

    public static function clipChroma(ColorInterface $color): ColorInterface
    {
        $okLch  = $color->toOkLch();

        if (! self::colorWithinSrgbGamut($color)) {
            $chroma = $okLch->chroma;
            $chromaAdjust = 0.1;
            $chromaDelta  = 0;

            $i = 0;
            do {
                $okLch = $okLch->change(chroma: $chroma + $chromaDelta);

                if (self::colorWithinSrgbGamut($okLch)) {
                    $chromaAdjust /= 2;
                    $chromaDelta += $chromaAdjust;
                } else {
                    $chromaDelta -= $chromaAdjust;
                }

                $exceedsPrecision = self::floatExceedsPrecision($okLch->chroma, self::CHROMA_CLIP_PRECISION);
                $withinSrgbGamut  = self::colorWithinSrgbGamut($okLch);
            } while (
                $chromaDelta < 0 && ! ($exceedsPrecision && $withinSrgbGamut) && $i++ < 100
            );
        }

        return $okLch;
    }

    /**
     * Returns true if the precision of the float exceeds the given precision.
     */
    public static function floatExceedsPrecision(float $value, int $precision): bool
    {
        $round1 = round($value, $precision);
        $round2 = round($value, $precision - 1);

        return $round1 !== $round2;
    }

    public static function colorWithinSrgbGamut(ColorInterface $color): bool
    {
        if (self::minCoordinate($color) < 0 || self::maxCoordinate($color) > 1) {
            return false;
        }

        return true;
    }

    public static function minCoordinate(ColorInterface $color): float
    {
        return min(self::toRawColor($color, ColorSpace::LinRgb));
    }

    public static function maxCoordinate(ColorInterface $color): float
    {
        return max(self::toRawColor($color, ColorSpace::LinRgb));
    }

    /**
     * @return float[]
     */
    public static function toRawColor(ColorInterface $color, ColorSpace $to): array
    {
        $from     = $color::space();
        $fromName = lcfirst($from->name);

        $coordinates = $color->coordinates();

        /** @var callable $convert */
        $convert  = "\\matthieumastadenis\\couleur\\utils\\{$fromName}\\to{$to->name}";
        $rawColor = $convert(...$coordinates);

        return $rawColor;
    }
}
