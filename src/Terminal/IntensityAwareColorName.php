<?php

/**
 * @noinspection PhpClassConstantAccessedViaChildClassInspection
 */

declare(strict_types=1);

namespace Empaphy\Colorphul\Terminal;

enum IntensityAwareColorName: string
{
    case Black   = IntensityAwareColorNames::BLACK;
    case Red     = IntensityAwareColorNames::RED;
    case Green   = IntensityAwareColorNames::GREEN;
    case Yellow  = IntensityAwareColorNames::YELLOW;
    case Blue    = IntensityAwareColorNames::BLUE;
    case Magenta = IntensityAwareColorNames::MAGENTA;
    case Cyan    = IntensityAwareColorNames::CYAN;
    case White   = IntensityAwareColorNames::WHITE;

    case BrightBlack   = IntensityAwareColorNames::BRIGHT_BLACK;
    case BrightRed     = IntensityAwareColorNames::BRIGHT_RED;
    case BrightGreen   = IntensityAwareColorNames::BRIGHT_GREEN;
    case BrightYellow  = IntensityAwareColorNames::BRIGHT_YELLOW;
    case BrightBlue    = IntensityAwareColorNames::BRIGHT_BLUE;
    case BrightMagenta = IntensityAwareColorNames::BRIGHT_MAGENTA;
    case BrightCyan    = IntensityAwareColorNames::BRIGHT_CYAN;
    case BrightWhite   = IntensityAwareColorNames::BRIGHT_WHITE;

    case DimBlack   = IntensityAwareColorNames::DIM_BLACK;
    case DimRed     = IntensityAwareColorNames::DIM_RED;
    case DimGreen   = IntensityAwareColorNames::DIM_GREEN;
    case DimYellow  = IntensityAwareColorNames::DIM_YELLOW;
    case DimBlue    = IntensityAwareColorNames::DIM_BLUE;
    case DimMagenta = IntensityAwareColorNames::DIM_MAGENTA;
    case DimCyan    = IntensityAwareColorNames::DIM_CYAN;
    case DimWhite   = IntensityAwareColorNames::DIM_WHITE;
}
