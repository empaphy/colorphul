<?php

declare(strict_types=1);

namespace Empaphy\Colorphul\Terminal;

use Empaphy\Colorphul\ColorPalette;
use matthieumastadenis\couleur\ColorInterface;

/**
 * A color scheme that supports different intensities for each color.
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
 * @template TColorPalette of AnsiColorPaletteInterface
 * @extends ColorPalette<string, ColorInterface>
 */
class IntensityAwareColorScheme extends ColorPalette
{
    /**
     * @param  TColorPalette       $normal
     * @param  TColorPalette       $bright
     * @param  TColorPalette|null  $dim
     *
     * @noinspection PhpDocSignatureInspection
     * @noinspection UnknownInspectionInspection
     */
    public function __construct(
        public readonly AnsiColorPaletteInterface $normal,
        public readonly AnsiColorPaletteInterface $bright,
        public readonly ?AnsiColorPaletteInterface $dim = null,
    ) {
        parent::__construct([...$normal]);

        foreach ($bright as $name => $color) {
            $this["{$name}_bright"] = $color;
        }

        foreach ($dim ?? [] as $name => $color) {
            $this["${name}_dim"] = $color;
        }
    }
}
