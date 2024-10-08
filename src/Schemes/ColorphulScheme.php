<?php

declare(strict_types=1);

namespace Empaphy\Colorphul\Schemes;

use Empaphy\Colorphul\ColorSchemeAppearance;
use Empaphy\Colorphul\ColorWheel\StandardColorWheel;
use Empaphy\Colorphul\Terminal\AnsiColorPalette;
use Empaphy\Colorphul\Terminal\IntensityAwareColorScheme;
use Empaphy\Colorphul\Terminal\TerminalEmulatorColorScheme;
use Empaphy\Colorphul\Util;
use Empaphy\Colorphul\Wcag;
use matthieumastadenis\couleur\ColorInterface;
use matthieumastadenis\couleur\colors\OkLch;
use matthieumastadenis\couleur\colors\Rgb;

class ColorphulScheme extends TerminalEmulatorColorScheme
{
    private const CHROMA = 0.12;

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

        $gray   = self::findOptimalBgLightness(new OkLch(55, 0, 0), $black2, $white2);

        // Produces grays that have a specific contrast compared to a background color.
        $black1 = Wcag::reverseDarker(7, $white2); // #4D4D4D
        $white1 = Wcag::reverseLighter(7, $black2); //

        $hueOffset  = Util::calculateAverageRgbToOkLchHueOffset($gray->lightness);        # 27.638934402372
        $colorWheel = new StandardColorWheel($gray->lightness, self::CHROMA, $hueOffset);

        parent::__construct(
            colorSets: new IntensityAwareColorScheme(
                normal: new AnsiColorPalette(
                    black:   $black3,
                    red:     Util::clipChroma($colorWheel->red),    #db7268 oklab(67% 0.11 0.06)
                    green:   Util::clipChroma($colorWheel->green),  # oklab(67% -0.1 0.08)
                    yellow:  Util::clipChroma($colorWheel->yellow), #
                    blue:    Util::clipChroma($colorWheel->azure),  // Azure simply looks nicer.
                    magenta: Util::clipChroma($colorWheel->violet), // Violet harmonizes better with azure.
                    cyan:    Util::clipChroma($colorWheel->cyan),
                    white:   $white1,
                ),
                bright: new AnsiColorPalette(
                    black:   $black1,
                    red:     Util::clipChroma($colorWheel->rose),
                    green:   Util::clipChroma($colorWheel->chartreuse),
                    yellow:  Util::clipChroma($colorWheel->orange),
                    blue:    Util::clipChroma($colorWheel->blue),
                    magenta: Util::clipChroma($colorWheel->magenta),
                    cyan:    Util::clipChroma($colorWheel->spring),
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

            $onBrighterBgContrastRatio = Wcag::colorContrastRatio($darkerTextColor, $bgColor);
            $onDarkerBgContrastRatio   = Wcag::colorContrastRatio($brighterTextColor, $bgColor);

            $diff = round($onDarkerBgContrastRatio - $onBrighterBgContrastRatio, 14);

            $lightness += 10 * $diff / 2;
        } while (0.0 !== $diff && $i++ < 100);

        return $bgColor;
    }
}
