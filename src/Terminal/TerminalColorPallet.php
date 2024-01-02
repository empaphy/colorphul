<?php

declare(strict_types=1);

namespace Empaphy\Colorphul\Terminal;

use matthieumastadenis\couleur\ColorInterface;
use matthieumastadenis\couleur\colors\Hsl;

readonly class TerminalColorPallet
{
    public function __construct(
        public ColorInterface $black,
        public ColorInterface $red,
        public ColorInterface $green,
        public ColorInterface $yellow,
        public ColorInterface $blue,
        public ColorInterface $magenta,
        public ColorInterface $cyan,
        public ColorInterface $white,
    ) {
        // Nothing to do.
    }

    protected static function calculateAverageRgbToOkLchHueOffset(float $lightness): float
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
}
