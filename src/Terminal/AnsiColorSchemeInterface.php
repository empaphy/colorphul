<?php

declare(strict_types=1);

namespace Empaphy\Colorphul\Terminal;

use Empaphy\Colorphul\ColorScheme;
use Empaphy\Colorphul\ColorSchemeInterface;
use matthieumastadenis\couleur\ColorInterface;

/**
 * @template TNames of string
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
 * @extends ColorSchemeInterface<TNames, TColor>
 *
 * @phpstan-require-extends ColorScheme<TNames, TColor>
 */
interface AnsiColorSchemeInterface extends ColorSchemeInterface {}
