<?php

declare(strict_types=1);

namespace Empaphy\Colorphul\Schemes;

use Empaphy\Colorphul\Apca;
use Empaphy\Colorphul\ColorSchemeAppearance;
use Empaphy\Colorphul\ColorWheel\StandardColorWheel;
use Empaphy\Colorphul\Terminal\AnsiColorPalette;
use Empaphy\Colorphul\Terminal\IntensityAwareColorScheme;
use Empaphy\Colorphul\Terminal\TerminalEmulatorColorScheme;
use Empaphy\Colorphul\Util;
use matthieumastadenis\couleur\ColorInterface;
use matthieumastadenis\couleur\colors\Hsl;
use matthieumastadenis\couleur\colors\OkLch;
use matthieumastadenis\couleur\colors\Rgb;
use RuntimeException;

class ColorphulApcaScheme extends TerminalEmulatorColorScheme
{
    private const CHROMA = 0.11;

    private const RGB_RANGE_MIN =  16;
    private const RGB_RANGE_MAX = 234;

    public function __construct(ColorSchemeAppearance $appearance)
    {
        // Black and bright white are based on the limited RGB range of 15 - 235.
        //
        // This guarantees a consistent contrast ratio even when a display is not properly calibrated. Why use 16 - 234
        // instead of 15 - 235? Because I want to prevent the screen hitting 0 lightness, to prevent Full-Array Local
        // Dimming (FALD) displays from trying to achieve full black, which would reduce the contrast ratio of text.

        $black3 = new Rgb(0, 0, 0);                                                         #000000
        $white3 = new Rgb(255, 255, 255);                                                   #FFFFFF
        $black2 = (new Rgb(self::RGB_RANGE_MIN, self::RGB_RANGE_MIN, self::RGB_RANGE_MIN)); #101010
        $white2 = (new Rgb(self::RGB_RANGE_MAX, self::RGB_RANGE_MAX, self::RGB_RANGE_MAX)); #EAEAEA

        $gray   = self::findOptimalBgLightness(new OkLch(50, 0, 0), $black2, $white2);      #959595 (67 lightness) (vs #737373 W3)

        // Produces grays that have a specific contrast compared to a background color.
        $black1 = Apca::reverseAPCA( 75, Apca::sRGBtoY($white2), 'bg', 'color');        #515151
        $white1 = Apca::reverseAPCA(-75, Apca::sRGBtoY($black2), 'bg', 'color');        #CCCCCC

        if (false === $black1 || false === $white1) {
            throw new RuntimeException('Failed to calculate optimal text colors.');
        }

        $hueOffset  = Util::calculateAverageRgbToOkLchHueOffset($gray->lightness);        # 27.638934402372
        $colorWheel = new StandardColorWheel($gray->lightness, self::CHROMA, $hueOffset);

        parent::__construct(
            colorSets: new IntensityAwareColorScheme(
                normal: new AnsiColorPalette(
                    black:   $black3,
                    red:     $colorWheel->red,    #db7268 oklab(67% 0.11 0.06)
                    green:   $colorWheel->green,  # oklab(67% -0.1 0.08)
                    yellow:  $colorWheel->yellow, #
                    blue:    $colorWheel->azure,  // Azure simply looks nicer.
                    magenta: $colorWheel->violet, // Violet harmonizes better with azure.
                    cyan:    $colorWheel->cyan,
                    white:   $white1,
                ),
                bright: new AnsiColorPalette(
                    black:   $black1,
                    red:     $colorWheel->rose,
                    green:   $colorWheel->chartreuse,
                    yellow:  $colorWheel->orange,
                    blue:    $colorWheel->blue,
                    magenta: $colorWheel->magenta,
                    cyan:    $colorWheel->spring,
                    white:   $white3,
                ),
            ),
            background: ColorSchemeAppearance::Dark === $appearance ? $black2 : $white2,
            foreground: ColorSchemeAppearance::Dark === $appearance ? $white1 : $black1,
            appearance: $appearance,
            accent:     $gray,
        );
    }

    /**
     * Find the optimal lightness for a text color that is equally readable on both the darker and brighter backgrounds.
     */
    public static function findOptimalTextLightness(
        ColorInterface $textColor,
        ColorInterface $darkerBgColor,
        ColorInterface $brighterBgColor
    ): OkLch {
        $textColor = $textColor->toOkLch();

        // Define an initial lightness that is halfway between black and white.
        $lightness = $darkerBgColor->toOkLch()->lightness + (
            $brighterBgColor->toOkLch()->lightness - $darkerBgColor->toOkLch()->lightness
        ) / 2;

        $i = 0;

        // Approximate the optimal lightness.
        do {
            $textColor = $textColor->change($lightness);

            $onBlackLc = Apca::calcAPCA($textColor, $darkerBgColor);
            $onWhiteLc = Apca::calcAPCA($textColor, $brighterBgColor);

            $diff = round($onWhiteLc + $onBlackLc, 14);

            $lightness += $diff / 2;
        } while ($diff !== 0.0 && $i++ < 100);

        return $textColor;
    }

    /**
     * Find the lightness for a background color that makes both the brighter and darker text equally readable.
     */
    public static function findOptimalBgLightness(
        ColorInterface $bgColor,
        ColorInterface $darkerTextColor,
        ColorInterface $brighterTextColor
    ): OkLch {
        $bgColor = $bgColor->toOkLch();

        // Define an initial lightness that is halfway between dark and bright.
        $lightness = $darkerTextColor->toOkLch()->lightness + (
            $brighterTextColor->toOkLch()->lightness - $darkerTextColor->toOkLch()->lightness
        ) / 2;

        $i = 0;

        // Approximate the optimal lightness.
        do {
            $bgColor = $bgColor->change($lightness);

            $onBrighterBgLc = Apca::calcAPCA($darkerTextColor, $bgColor);
            $onDarkerBgLc   = Apca::calcAPCA($brighterTextColor, $bgColor);

            $diff = round($onBrighterBgLc + $onDarkerBgLc, 14);

            $lightness -= $diff / 2;
        } while ($diff !== 0.0 && $i++ < 100);

        return $bgColor;
    }
}
