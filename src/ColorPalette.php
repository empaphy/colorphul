<?php

declare(strict_types=1);

namespace Empaphy\Colorphul;

use ArrayObject;
use matthieumastadenis\couleur\ColorInterface;

/**
 * A palette is the set of available colors from which an image can be made.
 *
 * Colors from a certain color space's color reproduction range are assigned an index, by which they can be referenced.
 *
 * @template TIndex of string
 * @template TColor of ColorInterface
 *
 * @extends ArrayObject<TIndex, TColor>
 * @implements ColorPaletteInterface<TIndex, TColor>
 */
abstract class ColorPalette extends ArrayObject implements ColorPaletteInterface
{
    /**
     * @param  ColorPaletteInterface<TIndex, TColor> | array<TIndex, TColor>  $colors
     */
    public function __construct(ColorPaletteInterface | array $colors)
    {
        parent::__construct($colors, parent::ARRAY_AS_PROPS);
    }
}
