<?php

declare(strict_types=1);

namespace Empaphy\Colorphul\Themes\Example;

use Empaphy\Colorphul\Terminal\TerminalEmulatorColorScheme;

class ExampleThemeTemplate
{
    public function __construct(
        public readonly string $themeName,
        public readonly TerminalEmulatorColorScheme $colorScheme,
    ) {
        // Nothing to see here.
    }

    public function render(): void
    {
        require __DIR__ . '/template.phtml';
    }
}
