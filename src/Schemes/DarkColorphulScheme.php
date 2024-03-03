<?php

declare(strict_types=1);

namespace Empaphy\Colorphul\Schemes;

use Empaphy\Colorphul\ColorSchemeAppearance;

class DarkColorphulScheme extends ColorphulScheme
{
    public function __construct()
    {
        parent::__construct(ColorSchemeAppearance::Dark);
    }
}
