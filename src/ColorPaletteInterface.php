<?php

declare(strict_types=1);

namespace Empaphy\Colorphul;

use ArrayAccess;
use Countable;
use IteratorAggregate;
use matthieumastadenis\couleur\ColorInterface;

/**
 * Represents a set of {@see ColorInterface color}s, each assigned a unique index.
 *
 * @template TIndex of string
 * @template TColor of ColorInterface
 * @template-extends IteratorAggregate<TIndex, TColor>
 * @template-extends ArrayAccess<TIndex, TColor>
 */
interface ColorPaletteInterface extends IteratorAggregate, ArrayAccess, Countable {}
