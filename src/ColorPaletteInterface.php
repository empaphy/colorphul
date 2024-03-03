<?php

declare(strict_types=1);

namespace Empaphy\Colorphul;

use ArrayAccess;
use Countable;
use IteratorAggregate;
use matthieumastadenis\couleur\ColorInterface;

/**
 * @template TColorName of string
 * @template-extends IteratorAggregate<TColorName, ColorInterface>
 * @template-extends ArrayAccess<TColorName, ColorInterface>
 */
interface ColorPaletteInterface extends IteratorAggregate, ArrayAccess, Countable {}
