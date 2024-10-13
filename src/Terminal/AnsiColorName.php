<?php

declare(strict_types=1);

namespace Empaphy\Colorphul\Terminal;

enum AnsiColorName: string
{
    case Black   = AnsiColorNames::BLACK;
    case Red     = AnsiColorNames::RED;
    case Green   = AnsiColorNames::GREEN;
    case Yellow  = AnsiColorNames::YELLOW;
    case Blue    = AnsiColorNames::BLUE;
    case Magenta = AnsiColorNames::MAGENTA;
    case Cyan    = AnsiColorNames::CYAN;
    case White   = AnsiColorNames::WHITE;
}
