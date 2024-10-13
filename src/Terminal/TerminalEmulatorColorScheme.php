<?php

declare(strict_types=1);

namespace Empaphy\Colorphul\Terminal;

use Empaphy\Colorphul\ColorScheme;
use Empaphy\Colorphul\ColorSchemeAppearance;
use matthieumastadenis\couleur\ColorInterface;

/**
 * A color scheme that can be used for Terminal Emulators.
 *
 * @template TColor of ColorInterface
 *
 * @extends ColorScheme<value-of<TerminalEmulatorColorName>, TColor>
 * @implements TerminalEmulatorColorSchemeInterface<value-of<TerminalEmulatorColorName>, TColor>
 */
class TerminalEmulatorColorScheme extends ColorScheme implements TerminalEmulatorColorSchemeInterface
{
    /**
     * @param  IntensityAwareColorSchemeInterface<value-of<IntensityAwareColorName>, TColor>   $colors
     * @param  TColor                 $background
     * @param  TColor                 $foreground
     * @param  ColorSchemeAppearance  $appearance
     * @param  TColor|null            $accent
     *
     * @noinspection PhpDocSignatureInspection
     */
    public function __construct(
        public readonly IntensityAwareColorSchemeInterface $colors,
        public readonly ColorInterface $background,
        public readonly ColorInterface $foreground,
        public readonly ColorSchemeAppearance $appearance,
        public readonly ?ColorInterface $accent = null,
    ) {
        parent::__construct([...$colors]);

        $this['background'] = $background;
        $this['foreground'] = $foreground;

        if (null !== $accent) {
            $this['accent'] = $accent;
        }
    }
}
