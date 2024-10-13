<?php

declare(strict_types=1);

namespace Empaphy\Colorphul\Terminal;

use Empaphy\Colorphul\ColorSchemeAppearance;
use Empaphy\Colorphul\ColorWheel\ColorCircle;
use matthieumastadenis\couleur\ColorInterface;

/**
 * @template TColorPalette of AnsiColorSchemeInterface
 * @template-extends IntensityAwareColorScheme<TColorPalette>
 * @template-implements TerminalEmulatorColorSchemeInterface<TColorPalette>
 */
class TerminalEmulatorColorScheme extends IntensityAwareColorScheme implements TerminalEmulatorColorSchemeInterface
{
    /**
     * @param  IntensityAwareColorScheme<TColorPalette>  $colorSets
     * @param  ColorInterface                            $background
     * @param  ColorInterface                            $foreground
     * @param  ColorSchemeAppearance                     $appearance
     * @param  ColorInterface|null                       $accent
     */
    public function __construct(
        public readonly IntensityAwareColorScheme $colorSets,
        public readonly ColorInterface $background,
        public readonly ColorInterface $foreground,
        public readonly ColorSchemeAppearance $appearance,
        public readonly ?ColorInterface $accent = null,
    ) {
        parent::__construct($colorSets->normal, $colorSets->bright, $colorSets->dim);

        $this['background'] = $background;
        $this['foreground'] = $foreground;

        if (null !== $accent) {
            $this['accent'] = $accent;
        }
    }
}
