<?php

declare(strict_types=1);

namespace Empaphy\Colorphul\Color\Designations;

use Empaphy\Colorphul\Color\DesignatedColorInterface;

/**
 * @template TColor of \matthieumastadenis\couleur\ColorInterface
 * @extends DesignatedColorInterface<\Empaphy\Colorphul\Color\ColorDesignation::Text, TColor>
 */
interface TextColorInterface extends DesignatedColorInterface {}
