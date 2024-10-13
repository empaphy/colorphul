<?php

declare(strict_types=1);

namespace Empaphy\Colorphul;

use ArrayObject;
use matthieumastadenis\couleur\ColorInterface;

/**
 * A color scheme is a set of available colors referenced by index.
 *
 * @template TIndex of string
 * @template TColor of ColorInterface
 *
 * @extends ArrayObject<TIndex, TColor>
 * @implements ColorSchemeInterface<TIndex, TColor>
 */
abstract class ColorScheme extends ArrayObject implements ColorSchemeInterface
{
    /**
     * @param  iterable<TIndex, TColor>  $colors
     */
    public function __construct(iterable $colors)
    {
        parent::__construct($colors, parent::ARRAY_AS_PROPS);
    }
}
