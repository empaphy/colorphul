<?php

declare(strict_types=1);

namespace Empaphy\Colorphul\Terminal;

use Empaphy\Colorphul\ColorPaletteInterface;
use matthieumastadenis\couleur\ColorInterface;

/**
 * @property ColorInterface $black
 * @property ColorInterface $red
 * @property ColorInterface $green
 * @property ColorInterface $yellow
 * @property ColorInterface $blue
 * @property ColorInterface $magenta
 * @property ColorInterface $cyan
 * @property ColorInterface $white
 *
 * @template TColor of ColorInterface
 * @extends ColorPaletteInterface<value-of<AnsiColorName>, TColor>
 */
interface AnsiColorPaletteInterface extends ColorPaletteInterface {}
