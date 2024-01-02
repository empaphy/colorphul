<?php

declare(strict_types=1);

namespace Empaphy\Colorphul\Terminal;

use matthieumastadenis\couleur\ColorInterface;

readonly class IntensityAwareColorPallet extends TerminalColorPallet
{
    public ColorInterface $brightBlack;
    public ColorInterface $brightRed;
    public ColorInterface $brightGreen;
    public ColorInterface $brightYellow;
    public ColorInterface $brightBlue;
    public ColorInterface $brightMagenta;
    public ColorInterface $brightCyan;
    public ColorInterface $brightWhite;

    public function __construct(
        public TerminalColorPallet $normal,
        public TerminalColorPallet $bright,
    ) {
        parent::__construct(
            $normal->black,
            $normal->red,
            $normal->green,
            $normal->yellow,
            $normal->blue,
            $normal->magenta,
            $normal->cyan,
            $normal->white,
        );

        $this->brightBlack   = $bright->black;
        $this->brightRed     = $bright->red;
        $this->brightGreen   = $bright->green;
        $this->brightYellow  = $bright->yellow;
        $this->brightBlue    = $bright->blue;
        $this->brightMagenta = $bright->magenta;
        $this->brightCyan    = $bright->cyan;
        $this->brightWhite   = $bright->white;
    }
}
