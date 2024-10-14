<?php

declare(strict_types=1);

namespace Empaphy\Colorphul;

use ArrayAccess;
use Countable;
use matthieumastadenis\couleur\ColorInterface;
use Traversable;

/**
 * Represents a set of {@see ColorInterface color}s, each assigned a unique index.
 *
 * @template TIndex of array-key
 * @template TColor of ColorInterface
 *
 * @template-extends Traversable<TIndex, TColor>
 * @template-extends ArrayAccess<TIndex, TColor>
 */
interface ColorSetInterface extends Traversable, ArrayAccess, Countable {}
