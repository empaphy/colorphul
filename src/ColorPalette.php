<?php

declare(strict_types=1);

namespace Empaphy\Colorphul;

use ArrayObject;
use matthieumastadenis\couleur\ColorInterface;

/**
 * @template TColorName of string
 * @extends ArrayObject<TColorName, ColorInterface>
 * @implements ColorPaletteInterface<TColorName>
 */
abstract class ColorPalette extends ArrayObject implements ColorPaletteInterface
{
    /**
     * @param  ColorPaletteInterface<TColorName>|array<TColorName, ColorInterface>  $colors
     */
    public function __construct(ColorPaletteInterface|array $colors)
    {
        parent::__construct($colors, parent::ARRAY_AS_PROPS);
    }
}
