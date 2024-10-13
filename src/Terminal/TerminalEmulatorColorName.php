<?php

/**
 * @noinspection PhpClassConstantAccessedViaChildClassInspection
 */

declare(strict_types=1);

namespace Empaphy\Colorphul\Terminal;

enum TerminalEmulatorColorName: string
{
    case Black   = TerminalEmulatorColorNames::BLACK;
    case Red     = TerminalEmulatorColorNames::RED;
    case Green   = TerminalEmulatorColorNames::GREEN;
    case Yellow  = TerminalEmulatorColorNames::YELLOW;
    case Blue    = TerminalEmulatorColorNames::BLUE;
    case Magenta = TerminalEmulatorColorNames::MAGENTA;
    case Cyan    = TerminalEmulatorColorNames::CYAN;
    case White   = TerminalEmulatorColorNames::WHITE;

    case BrightBlack   = TerminalEmulatorColorNames::BRIGHT_BLACK;
    case BrightRed     = TerminalEmulatorColorNames::BRIGHT_RED;
    case BrightGreen   = TerminalEmulatorColorNames::BRIGHT_GREEN;
    case BrightYellow  = TerminalEmulatorColorNames::BRIGHT_YELLOW;
    case BrightBlue    = TerminalEmulatorColorNames::BRIGHT_BLUE;
    case BrightMagenta = TerminalEmulatorColorNames::BRIGHT_MAGENTA;
    case BrightCyan    = TerminalEmulatorColorNames::BRIGHT_CYAN;
    case BrightWhite   = TerminalEmulatorColorNames::BRIGHT_WHITE;

    case DimBlack   = TerminalEmulatorColorNames::DIM_BLACK;
    case DimRed     = TerminalEmulatorColorNames::DIM_RED;
    case DimGreen   = TerminalEmulatorColorNames::DIM_GREEN;
    case DimYellow  = TerminalEmulatorColorNames::DIM_YELLOW;
    case DimBlue    = TerminalEmulatorColorNames::DIM_BLUE;
    case DimMagenta = TerminalEmulatorColorNames::DIM_MAGENTA;
    case DimCyan    = TerminalEmulatorColorNames::DIM_CYAN;
    case DimWhite   = TerminalEmulatorColorNames::DIM_WHITE;

    case Background = TerminalEmulatorColorNames::BACKGROUND;
    case Foreground = TerminalEmulatorColorNames::FOREGROUND;

    case Accent = TerminalEmulatorColorNames::ACCENT;
}
