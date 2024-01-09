<?php

declare(strict_types=1);

namespace Empaphy\Colorphul\Schemes;

use Empaphy\Colorphul\ColorSchemeAppearance;
use Empaphy\Colorphul\StandardColorWheel;
use Empaphy\Colorphul\Terminal\IntensityAwareColorPallet;
use Empaphy\Colorphul\Terminal\TerminalColorPallet;
use Empaphy\Colorphul\Terminal\TerminalEmulatorColorPallet;
use matthieumastadenis\couleur\ColorInterface;
use matthieumastadenis\couleur\colors\OkLch;
use matthieumastadenis\couleur\colors\Rgb;
use Empaphy\Colorphul\apca_w3;

readonly class ColorphulScheme extends TerminalEmulatorColorPallet
{
    private const CHROMA = 0.13;

    private const RGB_RANGE_MIN =  16;
    private const RGB_RANGE_MAX = 234;

    public function __construct(ColorSchemeAppearance $appearance)
    {
        // Black and bright white are based on the limited RGB range of 15 - 235.
        //
        // This guarantees a consistent contrast ratio even when a display is not properly calibrated. Why use 16 - 234
        // instead of 15 - 235? Because I want to prevent the screen hitting 0 lightness, to prevent Full-Array Local
        // Dimming (FALD) displays from trying to achieve full black, which would reduce the contrast ratio of text.

        $white3 = new Rgb(255, 255, 255);                                                   #FFFFFF
        $black3 = new Rgb(0, 0, 0);                                                         #000000
        $white2 = (new Rgb(self::RGB_RANGE_MAX, self::RGB_RANGE_MAX, self::RGB_RANGE_MAX)); #EAEAEA
        $black2 = (new Rgb(self::RGB_RANGE_MIN, self::RGB_RANGE_MIN, self::RGB_RANGE_MIN)); #101010
        $white1 = apca_w3\reverseAPCA(-75, apca_w3\sRGBtoY($black2), 'bg', 'color');        #CCCCCC
        $white0 = apca_w3\reverseAPCA( 30, apca_w3\sRGBtoY($white2), 'bg', 'color');        #B0B0B0
        $black1 = apca_w3\reverseAPCA( 75, apca_w3\sRGBtoY($white2), 'bg', 'color');        #515151
        $black0 = apca_w3\reverseAPCA(-30, apca_w3\sRGBtoY($black2), 'bg', 'color');        #777777

//        $mid = $this->findOptimalLightness(new OkLch(50, 0.13, 25 + self::HUE_SPRING), $black2, $white2);
//        $gray = new OkLch($midLightness, 0, 0);
//        $midLightness = $gray->lightness;

        $gray       = $this->findOptimalLightness(new OkLch(50, 0, 0), $black2, $white2);
        $hueOffset  = $this->calculateAverageRgbToOkLchHueOffset($gray->lightness);
        $colorWheel = new StandardColorWheel($gray->lightness, self::CHROMA, $hueOffset);

        parent::__construct(
            colors: new IntensityAwareColorPallet(
                normal: new TerminalColorPallet(
                    black:   $black3,
                    red:     $colorWheel->red,
                    green:   $colorWheel->green,
                    yellow:  $colorWheel->yellow,
                    blue:    $colorWheel->azure,  // Azure simply looks nicer.
                    magenta: $colorWheel->violet, // Violet harmonizes better with azure.
                    cyan:    $colorWheel->cyan,
                    white:   $white0,
                ),
                bright: new TerminalColorPallet(
                    black:   $black0,
                    red:     $colorWheel->rose,
                    green:   $colorWheel->chartreuse,
                    yellow:  $colorWheel->orange,
                    blue:    $colorWheel->blue,
                    magenta: $colorWheel->magenta,
                    cyan:    $colorWheel->spring,
                    white:   $white3,
                ),
            ),
            accent:     $gray,
            background: ColorSchemeAppearance::Dark === $appearance ? $black2 : $white2,
            foreground: ColorSchemeAppearance::Dark === $appearance ? $white1 : $black1,
            appearance: $appearance,
        );
    }

    /**
     * Find the lightness for a gray that is equally readable on both the background and foreground, according to APCA.
     */
    protected function findOptimalLightness(ColorInterface $color, ColorInterface $black, ColorInterface $white): OkLch
    {
        $color = $color->toOkLch();

        // Define an initial lightness that is halfway between black and white.
        $lightness = $black->toOkLch()->lightness + ($white->toOkLch()->lightness - $black->toOkLch()->lightness) / 2;

        $i = 0;

        // Approximate the optimal lightness.
        do {
            $color = $color->change($lightness);

            $grayOnBlackLc = apca_w3\calcAPCA($color, $black);
            $grayOnWhiteLc = apca_w3\calcAPCA($color, $white);

            $diff = round($grayOnWhiteLc + $grayOnBlackLc, 14);

            $lightness += $diff / 2;
        } while ($diff !== 0.0 && $i++ < 100);

        return $color;
    }
}
