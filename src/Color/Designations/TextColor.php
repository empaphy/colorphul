<?php

declare(strict_types=1);

namespace Empaphy\Colorphul\Color\Designations;

use Empaphy\Colorphul\Color\ColorDesignation;
use Empaphy\Colorphul\Color\DesignatedColor;
use matthieumastadenis\couleur\ColorInterface;

/**
 * @template TColor of ColorInterface
 * @extends DesignatedColor<ColorDesignation::Text, TColor>
 * @implements TextColorInterface<TColor>
 */
final class TextColor extends DesignatedColor implements TextColorInterface
{
    /**
     * @param  TColor  $color
     *
     * @noinspection PhpDocSignatureInspection
     */
    public function __construct(ColorInterface $color)
    {
        parent::__construct(ColorDesignation::Text, $color);
    }
}
