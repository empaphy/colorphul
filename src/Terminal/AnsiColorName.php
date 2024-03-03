<?php

declare(strict_types=1);

namespace Empaphy\Colorphul\Terminal;

enum AnsiColorName: string
{
    case Black   = 'black';
    case Red     = 'red';
    case Green   = 'green';
    case Yellow  = 'yellow';
    case Blue    = 'blue';
    case Magenta = 'magenta';
    case Cyan    = 'cyan';
    case White   = 'white';
}
