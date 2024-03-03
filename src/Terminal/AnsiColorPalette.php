<?php

declare(strict_types=1);

namespace Empaphy\Colorphul\Terminal;

use Empaphy\Colorphul\ColorPalette;
use matthieumastadenis\couleur\ColorInterface;

/**
 * @template-extends ColorPalette<value-of<AnsiColorName>>
 */
class AnsiColorPalette extends ColorPalette implements AnsiColorPaletteInterface
{
    public function __construct(
        ColorInterface $black,
        ColorInterface $red,
        ColorInterface $green,
        ColorInterface $yellow,
        ColorInterface $blue,
        ColorInterface $magenta,
        ColorInterface $cyan,
        ColorInterface $white
    ) {
        parent::__construct([
            AnsiColorName::Black->value => $black,
            AnsiColorName::Red->value => $red,
            AnsiColorName::Green->value => $green,
            AnsiColorName::Yellow->value => $yellow,
            AnsiColorName::Blue->value => $blue,
            AnsiColorName::Magenta->value => $magenta,
            AnsiColorName::Cyan->value => $cyan,
            AnsiColorName::White->value => $white,
        ]);
    }
}
