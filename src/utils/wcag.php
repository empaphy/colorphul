<?php

declare(strict_types=1);

namespace Empaphy\Colorphul\utils\wcag;

use DomainException;

/**
 * Calculate the contrast ratio between two relative luminance values.
 *
 * @param  float  $l1  The relative luminance of the lighter of the colors.
 * @param  float  $l2  The relative luminance of the darker of the colors.
 * @return float The contrast ratio. Contrast ratios can range from 1 to 21.
 *
 * @throws DomainException If the relative luminances are invalid.
 */
function contrast_ratio(float $l1, float $l2): float
{
    return ($l1 + 0.05) / ($l2 + 0.05);
}

/**
 * Returns the relative luminance of the given color.
 *
 * Relative luminance is the relative brightness of any point in a
 * colorspace, normalized to 0 for darkest black and 1 for lightest white.
 */
function relative_luminance(int $r8bit, int $g8bit, int $b8bit): float
{
    $r = linear(srgb($r8bit));
    $g = linear(srgb($g8bit));
    $b = linear(srgb($b8bit));

    return 0.2126 * $r + 0.7152 * $g + 0.0722 * $b;
}

function srgb(int $c8bit): float
{
    return $c8bit / 255;
}

function linear(float $cSrgb): float
{
    if ($cSrgb <= 0.04045) {
        $c = $cSrgb / 12.92;
    } else {
        $c = (($cSrgb + 0.055) / 1.055) ** 2.4;
    }

    return $c;
}
