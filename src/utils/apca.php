<?php

declare(strict_types=1);

namespace Empaphy\Colorphul\utils\apca;

use matthieumastadenis\couleur\colors\Rgb;

/**
 * Tone Response Curve.
 *
 * This slightly higher exponent is added as part of pre-processing to relax the
 * exponents in the contrast determination stage, and the modeling of real-world
 * monitors and devices.
 */
const TRC = 2.4;

// sRGB coefficients
const cR = 0.2126729;
const cG = 0.7151522;
const cB = 0.0721750;

// Exponents
const EXP_NORMAL_BG = 0.56;
const EXP_NORMAL_TXT = 0.57;
const EXP_REVERSE_BG = 0.65;
const EXP_REVERSE_TXT = 0.62;

// Clamps & Scalers
const CLIP = 1.414;
const THRESHOLD = 0.022;
const SCALE_W = 1.14;
const OFFSET = 0.027;
const CLAMP = 0.1;

/**
 * @param  Rgb  $txt Color of the text, symbol, or object.
 * @param  Rgb  $bg  Color used for the adjacent background.
 * @return float Lightness Contrast (Lᶜ).
 */
function lightness_contrast(Rgb $txt, Rgb $bg): float
{
    return cl(sapc(
        sc(sY($txt->red, $txt->green, $txt->blue)),
        sc(sY($bg->red, $bg->green, $bg->blue))
    ));
}

/**
 * @param  float  $sapc  Perceptual lightness difference (Sapc).
 * @return float Lightness Contrast (Lᶜ).
 */
function cL(float $sapc): float
{
    // Clamp minimum contrast to 10%.
    if (abs($sapc) < CLAMP) {
        return 0.0;
    }

    if ($sapc > 0) {
        return ($sapc - OFFSET) * 100.0;
    }

    if ($sapc < 0) {
        return ($sapc + OFFSET) * 100.0;
    }

    return 0.0;
}

/**
 * Find perceptual lightness difference.
 *
 * The contrast calculation stage applies a different exponent to the
 * background, which drives local adaptation, and another to the stimuli (text).
 *
 * @param  float  $sYtxt  Luminance (Ys) derived from the color of the text,
 *                        symbol, or object.
 * @param  float  $sYbg   Luminance (Ys) derived from the color used for the
 *                        adjacent background.
 * @return float Perceptual lightness difference (Sapc).
 */
function sapc(float $txtY, float $bgY): float
{
    // Normal Polarity (dark text / light bg)
    if ($bgY > $txtY) {
        return ($bgY ** EXP_NORMAL_BG - $txtY ** EXP_NORMAL_TXT) * SCALE_W;
    }

    // Reverse Polarity (light text / dark bg)
    if ($bgY < $txtY) {
        return ($bgY ** EXP_REVERSE_BG - $txtY ** EXP_REVERSE_TXT) * SCALE_W;
    }

    return 0.0;
}

/**
 * Soft clip & clamp black levels.
 *
 * Applies a soft clamp at black for initial monitor modeling.
 */
function sc(float $cY): float
{
    if ($cY < 0.0) {
        return 0.0;
    }

    if ($cY < THRESHOLD) {
        return $cY + (THRESHOLD - $cY) ** CLIP;
    }

    return $cY;
}

/**
 * Estimate screen luminance using sRGB coefficients.
 *
 * On why this function omits the piecewise logic (`Csrgb ≤ 0.04045`) from the
 * sRGB OETF:
 *
 * > Technically, the input modules do not convert to a traditional calculated
 * > CIEXYZ luminance, and it is not intended to. They calculate a unique value
 * > "estimated screen luminance" denoted Ys. The first stage is a conversion to
 * > model of typical monitors in typical environmental settings. Included is
 * > consideration of surveys and case studies of monitors in real-world
 * > environments, and our own measurements and studies of various devices and
 * > displays.
 * >
 * > [...]
 * >
 * > The {@see sc() pre-processing stage} also includes a soft clamp at black
 * > for initial monitor modeling. As an interesting side note, the need for the
 * > soft clamp makes using the piecewise redundant, as that section near black
 * > is completely reshaped by the soft clamp to account for ambient flare and
 * > other psychophysical factors.
 * >
 * > The {@see sapc() contrast calculation stage} then applies a different
 * > exponent to the background, which drives local adaptation, and another to
 * > the stimuli (text).
 * >
 * > The piecewise sRGB TRC is not an emulation of actual monitor behavior,
 * > having been created to prevent infinite slope at 0 to facilitate digital
 * > processing, back in the days before math coprocessors were standard and
 * > computers relied on integer math, and RAM and other resources were
 * > precious. I.e. the dark ages. But the APCA is not about processing images
 * > and lossless round trips, where using the piecewise curve is of benefit.
 * > The object is emulating real world monitor behavior. It is useful to note
 * > that many ICC profiles (including those that ship with Adobe products) do
 * > not use the piecewise, and instead use the simple 2.2 gamma, which the
 * > piecewise was intended to emulate, and the IEC standard indicates the
 * > physical display EOTF being the simple gamma in four separate places in
 * > that document.
 * >
 * > The only thing in the APCA input stage that is really different is the
 * > {@see TRC exponent set to 2.4}. This slightly higher exponent is added as
 * > part of pre-processing to relax the exponents in the contrast determination
 * > stage, and the aforementioned modeling of real-world monitors and devices.
 * > The “extra” could be added separately before the contrast stage, but is
 * > combined for simplicity in the current implementation.
 * - {@link https://git.apcacontrast.com/documentation/regardingexponents}
 *
 * @param  int | float  $r  Red sRGB component.
 * @param  int | float  $g  Green sRGB component.
 * @param  int | float  $b  Blue sRGB component.
 * @return float The estimated screen luminance (Ys).
 */
function sY(int | float $r, int | float $g, int | float $b): float
{
    return (($r / 255.0) ** TRC) * cR
        + (($g / 255.0) ** TRC) * cG
        + (($b / 255.0) ** TRC) * cB;
}
