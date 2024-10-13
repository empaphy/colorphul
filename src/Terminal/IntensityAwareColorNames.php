<?php

declare(strict_types=1);

namespace Empaphy\Colorphul\Terminal;

interface IntensityAwareColorNames extends AnsiColorNames
{
    public const BRIGHT_BLACK =  self::BLACK . '_' . ColorIntensity::INTENSITY_BRIGHT;
    public const BRIGHT_RED =  self::RED . '_' . ColorIntensity::INTENSITY_BRIGHT;
    public const BRIGHT_GREEN =  self::GREEN . '_' . ColorIntensity::INTENSITY_BRIGHT;
    public const BRIGHT_YELLOW =  self::YELLOW . '_' . ColorIntensity::INTENSITY_BRIGHT;
    public const BRIGHT_BLUE =  self::BLUE . '_' . ColorIntensity::INTENSITY_BRIGHT;
    public const BRIGHT_MAGENTA =  self::MAGENTA . '_' . ColorIntensity::INTENSITY_BRIGHT;
    public const BRIGHT_CYAN =  self::CYAN . '_' . ColorIntensity::INTENSITY_BRIGHT;
    public const BRIGHT_WHITE =  self::WHITE . '_' . ColorIntensity::INTENSITY_BRIGHT;

    public const DIM_BLACK =  self::BLACK . '_' . ColorIntensity::INTENSITY_DIM;
    public const DIM_RED =  self::RED . '_' . ColorIntensity::INTENSITY_DIM;
    public const DIM_GREEN =  self::GREEN . '_' . ColorIntensity::INTENSITY_DIM;
    public const DIM_YELLOW =  self::YELLOW . '_' . ColorIntensity::INTENSITY_DIM;
    public const DIM_BLUE =  self::BLUE . '_' . ColorIntensity::INTENSITY_DIM;
    public const DIM_MAGENTA =  self::MAGENTA . '_' . ColorIntensity::INTENSITY_DIM;
    public const DIM_CYAN =  self::CYAN . '_' . ColorIntensity::INTENSITY_DIM;
    public const DIM_WHITE =  self::WHITE . '_' . ColorIntensity::INTENSITY_DIM;
}
