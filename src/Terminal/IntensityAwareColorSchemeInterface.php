<?php

declare(strict_types=1);

namespace Empaphy\Colorphul\Terminal;

use Empaphy\Colorphul\ColorScheme;
use matthieumastadenis\couleur\ColorInterface;

/**
 * @template TNames of string
 * @template TColor of ColorInterface
 *
 * @property ColorInterface $black_bright
 * @property ColorInterface $red_bright
 * @property ColorInterface $green_bright
 * @property ColorInterface $yellow_bright
 * @property ColorInterface $blue_bright
 * @property ColorInterface $magenta_bright
 * @property ColorInterface $cyan_bright
 * @property ColorInterface $white_bright
 *
 * @property ColorInterface $black_dim
 * @property ColorInterface $red_dim
 * @property ColorInterface $green_dim
 * @property ColorInterface $yellow_dim
 * @property ColorInterface $blue_dim
 * @property ColorInterface $magenta_dim
 * @property ColorInterface $cyan_dim
 * @property ColorInterface $white_dim
 *
 * @extends AnsiColorSchemeInterface<TNames, TColor>
 *
 * @phpstan-require-extends ColorScheme<TNames, TColor>
 */
interface IntensityAwareColorSchemeInterface extends AnsiColorSchemeInterface {}
