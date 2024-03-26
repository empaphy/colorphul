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
        public ColorInterface $black,
        public ColorInterface $red,
        public ColorInterface $green,
        public ColorInterface $yellow,
        public ColorInterface $blue,
        public ColorInterface $magenta,
        public ColorInterface $cyan,
        public ColorInterface $white,
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
