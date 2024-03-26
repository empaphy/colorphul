<?php

declare(strict_types=1);

namespace Empaphy\Colorphul\Terminal;

use Empaphy\Colorphul\ColorPalette;

/**
 * @template-extends ColorPalette<string>
 */
class IntensityAwareColorScheme extends ColorPalette
{
    public function __construct(
        public readonly AnsiColorPalette $normal,
        public readonly AnsiColorPalette $bright,
        public readonly ?AnsiColorPalette $dim = null,
    ) {
        parent::__construct([
            ...$normal,
        ]);

        foreach ($bright as $name => $color) {
            $this['bright' . ucfirst($name)] = $color;
        }

        foreach ($dim ?? [] as $name => $color) {
            $this['bright' . ucfirst($name)] = $color;
        }
    }
}
