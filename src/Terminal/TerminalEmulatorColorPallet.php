<?php

declare(strict_types=1);

namespace Empaphy\Colorphul\Terminal;

use Empaphy\Colorphul\ColorSchemeAppearance;
use matthieumastadenis\couleur\ColorInterface;

readonly class TerminalEmulatorColorPallet extends IntensityAwareColorPallet
{
    public function __construct(
        public IntensityAwareColorPallet $colors,
        public ColorInterface $accent,
        public ColorInterface $background,
        public ColorInterface $foreground,
        public ColorSchemeAppearance $appearance,
    ) {
        parent::__construct($colors->normal, $colors->bright);
    }
}
