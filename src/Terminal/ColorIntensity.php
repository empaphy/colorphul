<?php

declare(strict_types=1);

namespace Empaphy\Colorphul\Terminal;

enum ColorIntensity: string
{
    public const INTENSITY_NORMAL = 'normal';
    public const INTENSITY_BRIGHT = 'bright';
    public const INTENSITY_DIM = 'dim';

    case Normal = self::INTENSITY_NORMAL;
    case Bright = self::INTENSITY_BRIGHT;
    case Dim = self::INTENSITY_DIM;
}
