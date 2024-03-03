<?php

declare(strict_types=1);

namespace Empaphy\Colorphul\ColorWheel;

use ArrayAccess;
use Countable;
use Iterator;
use matthieumastadenis\couleur\ColorInterface;

/**
 * @template-extends Iterator<value-of<StandardColorWheelHue>, ColorInterface>
 * @template-extends ArrayAccess<value-of<StandardColorWheelHue>, ColorInterface>
 */
interface StandardColorWheelInterface extends Iterator, ArrayAccess, Countable {}
