<?php

declare(strict_types=1);

namespace Empaphy\Colorphul\Color;

use matthieumastadenis\couleur\ColorInterface;

/**
 * @template TDesignation of ColorDesignation
 * @template TColor       of ColorInterface
 *
 * @property-read TDesignation $designation
 * @property      TColor       $color
 *
 * @phpstan-require-extends DesignatedColor<TDesignation, TColor>
 */
interface DesignatedColorInterface {}
