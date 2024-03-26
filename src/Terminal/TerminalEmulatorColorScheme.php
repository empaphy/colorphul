<?php

declare(strict_types=1);

namespace Empaphy\Colorphul\Terminal;

use Empaphy\Colorphul\ColorSchemeAppearance;
use matthieumastadenis\couleur\ColorInterface;

class TerminalEmulatorColorScheme extends IntensityAwareColorScheme
{
    public function __construct(
        public readonly IntensityAwareColorScheme $colorSets,
        public readonly ColorInterface $background,
        public readonly ColorInterface $foreground,
        public readonly ColorSchemeAppearance $appearance,
        public readonly ?ColorInterface $accent = null,
    ) {
        parent::__construct($colorSets->normal, $colorSets->bright);

        $this['background'] = $background;
        $this['foreground'] = $foreground;

        if (null !== $accent) {
            $this['accent'] = $accent;
        }
    }
}
