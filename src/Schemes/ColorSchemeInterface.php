<?php

declare(strict_types=1);

namespace Empaphy\Colorphul\Schemes;

use Empaphy\Colorphul\Terminal\AnsiColorSchemeInterface;
use Empaphy\Colorphul\Terminal\TerminalEmulatorColorScheme;

interface ColorSchemeInterface
{
    /**
     * @return TerminalEmulatorColorScheme<AnsiColorSchemeInterface>
     */
    public function getTerminalEmulatorColorPalette(): TerminalEmulatorColorScheme;
}
