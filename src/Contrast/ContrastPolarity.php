<?php

declare(strict_types=1);

namespace Empaphy\Colorphul\Contrast;

enum ContrastPolarity: int
{
    public const Normal = self::DarkOnLight;
    public const Reverse = self::LightOnDark;

    case DarkOnLight = +1;
    case LightOnDark = -1;
    case None = 0;

    /**
     * Returns the Polarity for a given (normalized) contrast.
     */
    public static function for(float $contrast): self
    {
        return match ($contrast <=> 0) {
            1 => self::LightOnDark,
            0 => self::None,
            -1 => self::DarkOnLight,
        };
    }

    public function isReverse(): bool
    {
        return $this === self::Reverse;
    }
}
