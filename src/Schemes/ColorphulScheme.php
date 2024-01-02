<?php

declare(strict_types=1);

namespace Empaphy\Colorphul\Schemes;

use Empaphy\Colorphul\ColorSchemeAppearance;
use Empaphy\Colorphul\Terminal\IntensityAwareColorPallet;
use Empaphy\Colorphul\Terminal\TerminalColorPallet;
use Empaphy\Colorphul\Terminal\TerminalEmulatorColorPallet;
use matthieumastadenis\couleur\ColorInterface;
use matthieumastadenis\couleur\colors\OkLch;
use matthieumastadenis\couleur\colors\Rgb;
use Empaphy\Colorphul\apca_w3;
use function Empaphy\Colorphul\apca_w3\sRGBtoY;

readonly class ColorphulScheme extends TerminalEmulatorColorPallet
{
    public const HUE_RED        = 0;
    public const HUE_ORANGE     = 30;
    public const HUE_YELLOW     = 60;
    public const HUE_CHARTREUSE = 90;
    public const HUE_GREEN      = 120;
    public const HUE_SPRING     = 150;
    public const HUE_CYAN       = 180;
    public const HUE_AZURE      = 210;
    public const HUE_BLUE       = 240;
    public const HUE_VIOLET     = 270;
    public const HUE_MAGENTA    = 300;
    public const HUE_ROSE       = 330;

    private const RGB_RANGE_MIN =  16;
    private const RGB_RANGE_MAX = 234;

    public function __construct()
    {
        // Black and bright white are based on the limited RGB range of 15 - 235.
        //
        // This guarantees a consistent contrast ratio even when a display is not properly calibrated. Why use 16 - 234
        // instead of 15 - 235? Because I want to prevent the screen hitting 0 lightness, to prevent Full-Array Local
        // Dimming (FALD) displays from trying to achieve full black, which would reduce the contrast ratio of text.
        $black   = (new Rgb(self::RGB_RANGE_MIN, self::RGB_RANGE_MIN, self::RGB_RANGE_MIN));
        $brWhite = (new Rgb(self::RGB_RANGE_MAX, self::RGB_RANGE_MAX, self::RGB_RANGE_MAX));

//        $gray = $this->findOptimalLightness(new OkLch(50, 0, 0), $black, $brWhite);

        $midLightness = ($this->findOptimalLightness(new OkLch(50, 0.13, 25 + self::HUE_SPRING), $black, $brWhite))->lightness;

        $gray = new OkLch($midLightness, 0, 0);

        $hueOffset = self::calculateAverageRgbToOkLchHueOffset($midLightness);

        parent::__construct(
            colors: new IntensityAwareColorPallet(
                normal: new TerminalColorPallet(
                    black:   $black,
                    red:     new OkLch($midLightness, 0.13, $hueOffset + self::HUE_RED),
                    green:   new OkLch($midLightness, 0.13, $hueOffset + self::HUE_GREEN),
                    yellow:  new OkLch($midLightness, 0.13, $hueOffset + self::HUE_YELLOW),
                    blue:    new OkLch($midLightness, 0.13, $hueOffset + self::HUE_AZURE),  // Azure simply looks nicer.
                    magenta: new OkLch($midLightness, 0.13, $hueOffset + self::HUE_VIOLET), // Better harmony with ^.
                    cyan:    new OkLch($midLightness, 0.11, $hueOffset + self::HUE_CYAN),
                    white:   new OkLch(85.5, 0, 0),
                ),
                bright: new TerminalColorPallet(
                    black:   new OkLch(46.4, 0, 0),
                    red:     new OkLch($midLightness, 0.13, $hueOffset + self::HUE_ROSE),
                    green:   new OkLch($midLightness, 0.13, $hueOffset + self::HUE_CHARTREUSE),
                    yellow:  new OkLch($midLightness, 0.13, $hueOffset + self::HUE_ORANGE),
                    blue:    new OkLch($midLightness, 0.13, $hueOffset + self::HUE_BLUE),
                    magenta: new OkLch($midLightness, 0.13, $hueOffset + self::HUE_MAGENTA),
                    cyan:    new OkLch($midLightness, 0.11, $hueOffset + self::HUE_SPRING),
                    white:   $brWhite,
                ),
            ),
            accent:     $gray,
            background: $black,
            foreground: new OkLch(85.5, 0, 0),
            appearance: ColorSchemeAppearance::Dark,
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
