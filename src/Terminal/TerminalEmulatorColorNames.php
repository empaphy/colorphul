<?php

declare(strict_types=1);

namespace Empaphy\Colorphul\Terminal;

interface TerminalEmulatorColorNames extends IntensityAwareColorNames
{
    public const BACKGROUND = 'background';
    public const FOREGROUND = 'foreground';
    public const ACCENT     = 'accent';
}
