<?php

declare(strict_types=1);

namespace Empaphy\Colorphul\Terminal;

use Empaphy\Colorphul\ColorSchemeInterface;
use matthieumastadenis\couleur\ColorInterface;

/**
 * @template TColor of ColorInterface
 *
 * @property ColorInterface $black
 * @property ColorInterface $red
 * @property ColorInterface $green
 * @property ColorInterface $yellow
 * @property ColorInterface $blue
 * @property ColorInterface $magenta
 * @property ColorInterface $cyan
 * @property ColorInterface $white
 *
 * @extends ColorSchemeInterface<value-of<AnsiColorName>, TColor>
 *
 * @phpstan-require-extends AnsiColorScheme
 */
interface AnsiColorSchemeInterface extends ColorSchemeInterface {}
