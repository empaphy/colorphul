<?php

declare(strict_types=1);

namespace Empaphy\Colorphul\Terminal;

class IntensityAwareColorScheme extends AnsiColorPalette
{
    public function __construct(
        public readonly AnsiColorPalette $normal,
        public readonly AnsiColorPalette $bright,
        public readonly ?AnsiColorPalette $dim = null,
    ) {
        parent::__construct(...$normal);
    }
}
