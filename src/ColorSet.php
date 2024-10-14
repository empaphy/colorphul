<?php

/**
 * @noinspection PhpMultipleClassDeclarationsInspection
 */

declare(strict_types=1);

namespace Empaphy\Colorphul;

use ArrayObject;
use matthieumastadenis\couleur\ColorInterface;

/**
 * A set of colors referenced by index.
 *
 * @template TIndex of string
 * @template TColor of ColorInterface
 *
 * @extends ArrayObject<TIndex, TColor>
 * @implements ColorSetInterface<TIndex, TColor>
 */
abstract class ColorSet extends ArrayObject implements ColorSetInterface
{
    /**
     * @param  iterable<TIndex, TColor>  $colors
     */
    public function __construct(iterable $colors)
    {
        parent::__construct($colors, parent::ARRAY_AS_PROPS);
    }
}
