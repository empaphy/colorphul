<?php

declare(strict_types=1);

namespace Empaphy\Colorphul\Terminal;

use Empaphy\Colorphul\ColorSchemeAppearance;
use matthieumastadenis\couleur\ColorInterface;

/**
 * Represents a color scheme for a terminal emulator.
 *
 * @template TColorPalette of AnsiColorSchemeInterface
 *
 * @property-read IntensityAwareColorScheme<TColorPalette> $colorSet   Provide color palettes of different intensities.
 * @property-read ColorInterface                           $background The terminal background color.
 * @property-read ColorInterface                           $foreground The terminal foreground text color.
 * @property-read ColorSchemeAppearance                    $appearance The appearance of the terminal. (light or dark)
 * @property-read ColorInterface|null                      $accent     An accent color. Used to highlighting selections.
 */
interface TerminalEmulatorColorSchemeInterface {}
