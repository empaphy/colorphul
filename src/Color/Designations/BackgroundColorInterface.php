<?php

declare(strict_types=1);

namespace Empaphy\Colorphul\Color\Designations;

use Empaphy\Colorphul\Color\DesignatedColorInterface;

/**
 * @template TColor of \matthieumastadenis\couleur\ColorInterface
 * @extends DesignatedColorInterface<\Empaphy\Colorphul\Color\ColorDesignation::Background, TColor>
 */
interface BackgroundColorInterface extends DesignatedColorInterface {}
