<?php

declare(strict_types=1);

namespace Empaphy\Colorphul\Color;

enum ColorDesignation
{
    /**
     * Background color.
     */
    case Background;

    /**
     * Text color.
     */
    case Text;

    /**
     * @return ($this is self::Background ? self::Text : self::Background)
     */
    public function opposite(): self
    {
        return self::oppositeOf($this);
    }

    /**
     * @param  ColorDesignation  $designation
     * @return ($designation is self::Background ? self::Text : self::Background)
     */
    public static function oppositeOf(self $designation): self
    {
        return match ($designation) {
            self::Background => self::Text,
            self::Text => self::Background,
        };
    }
}
