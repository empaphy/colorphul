<?php

declare(strict_types=1);

namespace Empaphy\Colorphul\Schemes;

use Empaphy\Colorphul\Apca;
use Empaphy\Colorphul\ColorSchemeAppearance;
use Empaphy\Colorphul\ColorWheel\StandardColorWheel;
use Empaphy\Colorphul\Terminal\AnsiColorPalette;
use Empaphy\Colorphul\Terminal\IntensityAwareColorScheme;
use Empaphy\Colorphul\Terminal\TerminalEmulatorColorScheme;
use matthieumastadenis\couleur\ColorInterface;
use matthieumastadenis\couleur\colors\HexRgb;
use matthieumastadenis\couleur\colors\Hsl;
use matthieumastadenis\couleur\colors\OkLch;
use matthieumastadenis\couleur\colors\Rgb;
use RuntimeException;

/**
 * Color Scheme used by the Color Graphics Adapter (CGA).
 */
class CgaScheme extends TerminalEmulatorColorScheme
{
    public function __construct()
    {
        $black     = new HexRgb('00', '00', '00');
        $white     = new HexRgb('FF', 'FF', 'FF');
        $lightGray = new HexRgb('AA', 'AA', 'AA');
        $darkGray  = new HexRgb('55', '55', '55');

        parent::__construct(
            colorSets: new IntensityAwareColorScheme(
                normal: new AnsiColorPalette(
                    black:   $black,                       // black
                    red:     new HexRgb('AA', '00', '00'), // red
                    green:   new HexRgb('00', 'AA', '00'), // green
                    yellow:  new HexRgb('AA', '55', '00'), // brown
                    blue:    new HexRgb('00', '00', 'AA'), // blue
                    magenta: new HexRgb('AA', '00', 'AA'), // magenta
                    cyan:    new HexRgb('00', 'AA', 'AA'), // cyan
                    white:   $lightGray,                   // light gray
                ),
                bright: new AnsiColorPalette(
                    black:   $darkGray,                    // dark gray
                    red:     new HexRgb('FF', '55', '55'), // light red
                    green:   new HexRgb('55', 'FF', '55'), // light green
                    yellow:  new HexRgb('FF', 'FF', '55'), // yellow
                    blue:    new HexRgb('55', '55', 'FF'), // light blue
                    magenta: new HexRgb('FF', '55', 'FF'), // light magenta
                    cyan:    new HexRgb('55', 'FF', 'FF'), // light cyan
                    white:   $white,                       // white
                ),
            ),
            background: $black,
            foreground: $lightGray,
            appearance: ColorSchemeAppearance::Dark,
        );
    }
}
