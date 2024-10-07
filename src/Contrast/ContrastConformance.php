<?php

declare(strict_types=1);

namespace Empaphy\Colorphul\Contrast;

use LogicException;
use matthieumastadenis\couleur\ColorInterface;
use matthieumastadenis\couleur\colors\OkLch;
use RangeException;

abstract class ContrastConformance
{
    abstract public static function max(ContrastPolarity $polarity): float;

    abstract public static function min(ContrastPolarity $polarity): float;

    public function level(ColorInterface $text, ColorInterface $background): ConformanceLevel
    {
        return self::contrastLevel($this->contrast($text, $background));
    }

    public static function contrastLevel(int | float $contrast): ConformanceLevel
    {
        $contrast = abs($contrast);

        foreach (ConformanceLevel::cases() as $case) {
            if ($contrast >= $case->value) {
                return $case;
            }
        }

        return ConformanceLevel::Lowest;
    }

    abstract public function contrast(ColorInterface $text, ColorInterface $background): float;

    /**
     * @param  ColorInterface  $backgroundColor
     * @param  float           $targetContrast
     * @return ColorInterface
     *
     * @throws LogicException If the contrast ratio is invalid.
     * @throws RangeException If it's impossible to achieve the desired contrast ratio with the given color.
     */
    public function reverseTextColor(ColorInterface $backgroundColor, float $targetContrast): ColorInterface
    {
        $backgroundColor = $backgroundColor->toOkLch();

        $maxText = $backgroundColor->change(lightness: $targetContrast > 0 ? 0 : 100);
        $maxContrast = $this->contrast($maxText, $backgroundColor);

        if ($maxContrast === $targetContrast) {
            return $maxText;
        }

        if (abs($maxContrast) < abs($targetContrast)) {
            throw new RangeException(
                "It is impossible to achieve a contrast higher than {$maxContrast} with the given color,"
                . " but a contrast of {$targetContrast} was desired."
            );
        }

        $textColor = $backgroundColor->toOkLch();
        $lightness = $textColor->lightness;
        $contrast = 0.0;
        $i = 1;

        do {
            $diff = round($targetContrast - $contrast, 14);
            $lightness -= $diff / 2;
            $textColor = $textColor->change(lightness: $lightness);
            $contrast = $this->contrast($textColor, $backgroundColor);
        } while ((abs($targetContrast) > abs($contrast) || abs($diff) > 0.00000000000001) && $i++ < 1000);

        if (abs($contrast) < abs($targetContrast)) {
            throw new LogicException("Failed to reverse a text color. Needed {$targetContrast}, got {$contrast}.");
        }

        return $textColor;
    }


    /**
     * @param  ColorInterface  $textColor
     * @param  float           $targetContrast
     * @return ColorInterface
     *
     * @throws LogicException If the contrast ratio is invalid.
     * @throws RangeException If it's impossible to achieve the desired contrast ratio with the given color.
     */
    public function reverseBackgroundColor(ColorInterface $textColor, float $targetContrast): ColorInterface
    {
        $textColor = $textColor->toOkLch();

        $maxBackground = $textColor->change(lightness: $targetContrast < 0 ? 0 : 100);
        $maxContrast = $this->contrast($textColor, $maxBackground);

        if ($maxContrast === $targetContrast) {
            return $maxBackground;
        }

        if (abs($maxContrast) < abs($targetContrast)) {
            throw new RangeException(
                "It is impossible to achieve a contrast higher than {$maxContrast} with the given color,"
                . " but a contrast of {$targetContrast} was desired."
            );
        }

        $backgroundColor = $textColor->toOkLch();
        $lightness = $backgroundColor->lightness;
        $diff = $targetContrast;
        $i = 1;

        do {
            $lightness += $diff / 3;
            $backgroundColor = $backgroundColor->change(lightness: $lightness);
            $contrast = $this->contrast($textColor, $backgroundColor);
            $diff = $targetContrast - round($contrast, 14);
        } while ((abs($targetContrast) > abs($contrast) || 0.0 !== $diff) && $i++ < 100);

        if (abs($contrast) < abs($targetContrast)) {
            throw new LogicException("Failed to reverse a text color.");
        }

        return $backgroundColor;
    }

    /**
     * Find the lightness for a background color that makes both the brighter and darker text equally readable.
     *
     * @param  ColorInterface  $backgroundColor    Initial background color.
     * @param  ColorInterface  $darkerTextColor    Darker text color.
     * @param  ColorInterface  $brighterTextColor  Brighter text color.
     */
    public function findOptimalBgLightness(
        ColorInterface $backgroundColor,
        ColorInterface $darkerTextColor,
        ColorInterface $brighterTextColor
    ): OkLch {
        $backgroundColor   = $backgroundColor->toOkLch();
        $darkerLightness   = $darkerTextColor->toOkLch()->lightness;
        $brighterLightness = $brighterTextColor->toOkLch()->lightness;

        // Define an initial lightness that is halfway between dark and bright.
        $lightness = $darkerLightness + ($brighterLightness - $darkerLightness) / 2;

        $i = 0;

        // Approximate the optimal lightness.
        do {
            $backgroundColor = $backgroundColor->change($lightness);

            $onBrighterBgContrastRatio = $this->contrast($darkerTextColor, $backgroundColor);
            $onDarkerBgContrastRatio   = $this->contrast($brighterTextColor, $backgroundColor);

            $diff = round($onDarkerBgContrastRatio - $onBrighterBgContrastRatio, 14);

            $lightness += 10 * $diff / 2;
        } while (0.0 !== $diff && $i++ < 100);

        return $backgroundColor;
    }
}
