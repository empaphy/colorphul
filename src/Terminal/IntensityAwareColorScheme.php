<?php

declare(strict_types=1);

namespace Empaphy\Colorphul\Terminal;

use Empaphy\Colorphul\ColorScheme;
use matthieumastadenis\couleur\ColorInterface;


/**
 * A color scheme that supports different intensities for each color.
 *
 * @template TColor of ColorInterface
 *
 * @property ColorInterface $black
 * @property ColorInterface $red
 * @property ColorInterface $green
 * @property ColorInterface $yellow
 * @property ColorInterface $blue
 * @property ColorInterface $magenta
 * @property ColorInterface $cyan
 * @property ColorInterface $white
 *
 * @property ColorInterface $black_bright
 * @property ColorInterface $red_bright
 * @property ColorInterface $green_bright
 * @property ColorInterface $yellow_bright
 * @property ColorInterface $blue_bright
 * @property ColorInterface $magenta_bright
 * @property ColorInterface $cyan_bright
 * @property ColorInterface $white_bright
 *
 * @property ColorInterface $black_dim
 * @property ColorInterface $red_dim
 * @property ColorInterface $green_dim
 * @property ColorInterface $yellow_dim
 * @property ColorInterface $blue_dim
 * @property ColorInterface $magenta_dim
 * @property ColorInterface $cyan_dim
 * @property ColorInterface $white_dim
 *
 * @extends ColorScheme<value-of<IntensityAwareColorName>, TColor>
 * @implements AnsiColorSchemeInterface<value-of<IntensityAwareColorName>, TColor>
 * @implements IntensityAwareColorSchemeInterface<value-of<IntensityAwareColorName>, TColor>
 */
class IntensityAwareColorScheme extends ColorScheme implements AnsiColorSchemeInterface, IntensityAwareColorSchemeInterface
{
    /**
     * @param  AnsiColorSchemeInterface<value-of<AnsiColorName>, TColor>       $normal
     * @param  AnsiColorSchemeInterface<value-of<AnsiColorName>, TColor>       $bright
     * @param  AnsiColorSchemeInterface<value-of<AnsiColorName>, TColor>|null  $dim
     *
     * @noinspection UnknownInspectionInspection
     */
    public function __construct(
        public readonly AnsiColorSchemeInterface $normal,
        public readonly AnsiColorSchemeInterface $bright,
        public readonly ?AnsiColorSchemeInterface $dim = null,
    ) {
        parent::__construct([...$normal]);

        foreach ($bright as $name => $color) {
            $this["{$name}_" . ColorIntensity::Bright->value] = $color;
        }

        if (null !== $dim) {
            foreach ($dim as $name => $color) {
                $this["${name}_" . ColorIntensity::Dim->value] = $color;
            }
        }
    }
}
