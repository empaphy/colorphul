<?php

declare(strict_types=1);

namespace Empaphy\Colorphul\Terminal;

use Empaphy\Colorphul\ColorScheme;
use matthieumastadenis\couleur\ColorInterface;

/**
 * @template TIndex of value-of<AnsiColorName>
 * @template TColor of ColorInterface
 *
 * @extends ColorScheme<TIndex, TColor>
 * @implements AnsiColorSchemeInterface<TIndex, TColor>
 */
class AnsiColorScheme extends ColorScheme implements AnsiColorSchemeInterface
{
    /**
     * @param  TColor  $black
     * @param  TColor  $red
     * @param  TColor  $green
     * @param  TColor  $yellow
     * @param  TColor  $blue
     * @param  TColor  $magenta
     * @param  TColor  $cyan
     * @param  TColor  $white

     * @noinspection PhpDocSignatureInspection
     */
    public function __construct(
        public readonly ColorInterface $black,
        public readonly ColorInterface $red,
        public readonly ColorInterface $green,
        public readonly ColorInterface $yellow,
        public readonly ColorInterface $blue,
        public readonly ColorInterface $magenta,
        public readonly ColorInterface $cyan,
        public readonly ColorInterface $white,
    ) {
        parent::__construct([
            AnsiColorName::Black->value   => $black,
            AnsiColorName::Red->value     => $red,
            AnsiColorName::Green->value   => $green,
            AnsiColorName::Yellow->value  => $yellow,
            AnsiColorName::Blue->value    => $blue,
            AnsiColorName::Magenta->value => $magenta,
            AnsiColorName::Cyan->value    => $cyan,
            AnsiColorName::White->value   => $white,
        ]);
    }
}
