<?php

/**
 * SAPC APCA - Advanced Perceptual Contrast Algorithm.
 *
 * Functions to parse color values and determine Lc contrast.
 *
 * This is a 1:1 port to PHP of the original JavaScript version that can be
 * found here: https://github.com/Myndex/apca-w3/blob/master/src/apca-w3.js
 *
 * @version   Beta 0.1.9 W3 • contrast function only
 * @dist      W3 • Revision date: July 3, 2022
 * @copyright 2019-2022 Andrew Somers
 * @license   https://www.w3.org/copyright/software-license-2015  W3 LICENSE
 * @link      https://github.com/Myndex/SAPC-APCA  For ISSUES or DISCUSSIONS.
 *
 * FORWARD CONTRAST USAGE:
 *
 *     $Lc = APCAcontrast( sRGBtoY( $TEXTcolor ) , sRGBtoY( $BACKGNDcolor ) );
 *
 * Where the colors are sent as a Couleur RGB class.
 *
 * Retrieving an array of font sizes for the contrast:
 *
 *     $fontArray = fontLookupAPCA($Lc);
 *
 * Live Demonstrator at https://www.myndex.com/APCA/
 *
 * @preserve
 *
 * @noinspection AutoloadingIssuesInspection
 * @noinspection MissingOrEmptyGroupStatementInspection
 * @noinspection PhpIllegalPsrClassPathInspection
 * @noinspection PowerOperatorCanBeUsedInspection
 * @noinspection StaticClosureCanBeUsedInspection
 * @noinspection UnknownInspectionInspection
 */

declare(strict_types=1);

////////////////////////////////////////////////////////////////////////////////
/////
/////                  SAPC Method and APCA Algorithm
/////   W3 Licensed Version: https://github.com/Myndex/apca-w3
/////   GITHUB MAIN REPO: https://github.com/Myndex/SAPC-APCA
/////   DEVELOPER SITE: https://git.myndex.com/
/////
/////   Acknowledgments and Thanks To:
/////   • This project references the research & work of M.Fairchild, R.W.Hunt,
/////     Drs. Bailey/Lovie-Kitchin, G.Legge, A.Arditi, M.Stone, C.Poynton,
/////     L.Arend, M.Luo, E.Burns, R.Blackwell, P.Barton, M.Brettel, and many
/////     others — see refs at https://www.myndex.com/WEB/WCAG_CE17polarity
/////   • Bruce Bailey of USAccessBoard for his encouragement, ideas, & feedback
/////   • Chris Lilly of W3C for continued review, examination, & oversight
/////   • Chris Loiselle of Oracle for getting us back on track in a pandemic
/////   • The many volunteer test subjects for participating in the studies.
/////   • The many early adopters, beta testers, and code/issue contributors
/////   • Principal research conducted at Myndex by A.Somers.
/////
////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////
/////
/////   *****  SAPC BLOCK  *****
/////
/////   For Evaluations, refer to this as: SAPC-8, 0.0.98G-series constant 4g
/////            SAPC • S-LUV Advanced Predictive Color
/////
/////   SIMPLE VERSION — Only the basic APCA contrast predictor.
/////
/////   Included Extensions & Model Features in this file:
/////       • SAPC-8 Core Contrast (Base APCA, non-clinical use only)
/////       • G series constants, group "G-4g" using a 2.4 monitor exponent
/////       • sRGB to Y, parses numeric sRGB color to luminance
/////       • SoftToe black level soft clamp and flare compensation.
/////
/////
////////////////////////////////////////////////////////////////////////////////
/////
/////               DISCLAIMER AND LIMITATIONS OF USE
/////     APCA is an embodiment of certain suprathreshold contrast
/////     prediction technologies and it is licensed to the W3 on a
/////     limited basis for use in certain specific accessibility
/////     guidelines for web content only. APCA may be used for
/////     predicting colors for web content use without royalty.
/////
/////     However, Any such license excludes other use cases
/////     not related to web content. Prohibited uses include
/////     medical, clinical evaluation, human safety related,
/////     aerospace, transportation, military applications,
/////     and uses which are not specific to web based content
/////     presented on self-illuminated displays or devices.
/////
////////////////////////////////////////////////////////////////////////////////

//////////   APCA 0.1.9  G 4g USAGE  ///////////////////////////////////////////
///
///  The API for "APCA 0.1.9" is trivially simple.
///  Send text and background sRGB numeric values to the sRGBtoY() function,
///  and send the resulting text-Y and background-Y to the APCAcontrast function
///  it returns a signed float with the numeric Lc contrast result.
///
///  The two inputs are TEXT color and BACKGROUND color in that order.
///  Each must be a numeric NOT a string, as this simple version has
///  no string parsing utilities. EXAMPLE:
///  ________________________________________________________________________
///
///     $txtColor = new HexRgb('12', '34', '56'); // color of the text
///     $bgColor  = new HexRgb('ab', 'cd', 'ef'); // color for the background
///
///     $contrastLc = APCAcontrast( sRGBtoY($txtColor) , sRGBtoY($bgColor) );
///  ________________________________________________________________________
///
///                  **********   QUICK START   **********
///
///  Each color must be a 24bit color (8 bit per channel) as a single integer
///  (or 0x) sRGB encoded color, i.e. White is either the integer 16777216 or
///  the hex 0xffffff. A float is returned with a positive or negative value.
///  Negative values mean light text and a dark background, positive values
///  mean dark text and a light background. 60.0, or -60.0 is a contrast
///  "sort of like" the old WCAG 2's 4.5:1. NOTE: the total range is now less
///  than ± 110, so output can be rounded to a signed INT but DO NOT output
///  an absolute value - light text on dark BG should return a negative number.
///
///     *****  IMPORTANT: Do Not Mix Up Text and Background inputs.  *****
///     ****************   APCA is polarity sensitive!   *****************
///
////////////////////////////////////////////////////////////////////////////////

namespace Empaphy\Colorphul\apca_w3;

/////  DEPENDENCIES  /////

use matthieumastadenis\couleur\ColorInterface;
use matthieumastadenis\couleur\colors\P3;
use matthieumastadenis\couleur\colors\Rgb;


/**
 * Module Scope Class Containing Constants
 *
 * APCA   0.0.98G - 4g - W3 Compatible Constants
 */
class SA98G
{
    /**
     * 2.4 exponent for emulating actual monitor perception.
     */
    public const mainTRC = 2.4;

    // For reverseAPCA
    public static function getMainTRCencode(): float { return 1 / self::mainTRC; }

    // sRGB coefficients
    public const sRco = 0.2126729;
    public const sGco = 0.7151522;
    public const sBco = 0.0721750;

    // G-4g constants for use with 2.4 exponent
    public const normBG  = 0.56;
    public const normTXT = 0.57;
    public const revTXT  = 0.62;
    public const revBG   = 0.65;

    // G-4g Clamps and Scalers
    public const blkThrs     = 0.022;
    public const blkClmp     = 1.414;
    public const scaleBoW    = 1.14;
    public const scaleWoB    = 1.14;
    public const loBoWoffset = 0.027;
    public const loWoBoffset = 0.027;
    public const deltaYmin   = 0.0005;
    public const loClip      = 0.1;

    ///// MAGIC NUMBERS for UNCLAMP, for use with 0.022 & 1.414 /////
    // Magic Numbers for reverseAPCA
    public const mFactor    = 1.94685544331710;
    public static function getMFactInv(): float { return 1 / self::mFactor; }
    public const mOffsetIn  = 0.03873938165714010;
    public const mExpAdj    = 0.2833433964208690;
    public static function getMExp(): float { return self::mExpAdj / self::blkClmp; }
    public const mOffsetOut = 0.3128657958707580;
}


//////////////////////////////////////////////////////////////////////////////
//////////  APCA CALCULATION FUNCTIONS \/////////////////////////////////////

/**
 * Send linear Y (luminance) for text and background.
 *
 * IMPORTANT: Do not swap text and background luminance, polarity is important.
 *
 * @param  float  $txtY    Linear Y (luminance) for text.
 *                         Must be between 0.0-1.0.
 * @param  float  $bgY     Linear Y (luminance) for background.
 *                         Must be between 0.0-1.0.
 * @param  int    $places  Number of decimal places to return. Default `-1`
 *                         returns a signed float, `0` returns a rounded string,
 *                         and `1` or more returns a string with that many
 *                         places.
 */
function APCAcontrast(float $txtY, float $bgY, int $places = -1): float|string
{
    $icp = [0.0, 1.1];  // input range clamp / input error check

    if(INF === $txtY || INF === $bgY || min($txtY, $bgY) < $icp[0] ||
                                        max($txtY, $bgY) > $icp[1]) {
        return 0.0;  // return zero on error
    }

    /** @var  float  $SAPC            For raw SAPC values.
     *  @var  float  $outputContrast  For weighted final values. */

    /** Alternate Polarity Indicator. N normal R reverse */
    $polCat = 'BoW';

    // TUTORIAL

    // Use Y for text and BG, and soft clamp black,
    // return 0 for very close luminances, determine
    // polarity, and calculate SAPC raw contrast
    // Then scale for easy to remember levels.

    // Note that reverse contrast (white text on black)
    // intentionally returns a negative number
    // Proper polarity is important!

    //////////   BLACK SOFT CLAMP   ////////////////////////////////////////

    // Soft clamps Y for either color if it is near black.
    $txtY = ($txtY > SA98G::blkThrs) ? $txtY :
        $txtY + pow(SA98G::blkThrs - $txtY, SA98G::blkClmp);
    $bgY = ($bgY > SA98G::blkThrs) ? $bgY
        : $bgY + pow(SA98G::blkThrs - $bgY, SA98G::blkClmp);

    // Return 0 Early for extremely low ∆Y
    if (abs($bgY - $txtY) < SA98G::deltaYmin ) { return 0.0; }


    //////////   APCA/SAPC CONTRAST - LOW CLIP (W3 LICENSE)  ///////////////

    if ($bgY > $txtY ) {  // For normal polarity, black text on white (BoW)

        // Calculate the SAPC contrast value and scale
        $SAPC = ( pow($bgY, SA98G::normBG) -
                  pow($txtY, SA98G::normTXT) ) * SA98G::scaleBoW;

        // Low Contrast smooth rollout to prevent polarity reversal
        // and also a low-clip for very low contrasts
        $outputContrast = ($SAPC < SA98G::loClip) ? 0.0 :
            $SAPC - SA98G::loBoWoffset;

    } else {
        // For reverse polarity, light text on dark (WoB)
        // WoB should always return negative value.
        $polCat = 'WoB';

        $SAPC = ( pow($bgY, SA98G::revBG) -
                  pow($txtY, SA98G::revTXT) ) * SA98G::scaleWoB;

        $outputContrast = ($SAPC > -SA98G::loClip) ? 0.0
            : $SAPC + SA98G::loWoBoffset;
    }

    // return Lc (lightness contrast) as a signed numeric value
    // Round to the nearest whole number as string is optional.
    // Rounded can be a signed INT as output will be within ± 127
    // places = -1 returns signed float, 1 or more set that many places
    // 0 returns rounded string, uses BoW or WoB instead of minus sign

    if ($places < 0 ) {  // Default (-1) number out, all others are strings
        return $outputContrast * 100.0;
    }

    if ($places === 0 ) {
        return round(abs($outputContrast) * 100.0)
            . '<sub>' . $polCat . '</sub>';
    }

    return (string) round($outputContrast * 100.0, $places);
} // End APCAcontrast()

/**
 * ƒ  reverseAPCA()
 *
 * @phpstan-type KnownType = 'bg'|'background'|'txt'|'text'
 * @phpstan-type ReturnAs  = 'hex'|'color'|'Y'|'y'
 *
 * @param  float      $contrast   abs contrast must be > 9
 * @param  float      $knownY
 * @param  KnownType  $knownType
 * @param  ReturnAs   $returnAs
 * @return string|Rgb|float|false
 *
 * @noinspection PhpUnused*@deprecated SOON
 */
function reverseAPCA(
    float  $contrast  = 0,
    float  $knownY    = 1.0,
    string $knownType = 'bg',
    string $returnAs  = 'hex'
): string|Rgb|float|false {
    if (abs($contrast) < 9) { return false; } // abs contrast must be > 9

    /** @var float $unknownY
     *  @var float $knownExp
     *  @var float $unknownExp */

    /////   APCA   0.0.98G - 4g - W3 Compatible Constants   ////////////////////

    $scale  = $contrast > 0 ? SA98G::scaleBoW    :  SA98G::scaleWoB;
    $offset = $contrast > 0 ? SA98G::loBoWoffset : -SA98G::loWoBoffset;


    $contrast = ($contrast * 0.01 + $offset) / $scale;

    // Soft clamps Y if it is near black.
    $knownY = ($knownY > SA98G::blkThrs) ? $knownY :
        $knownY + pow(SA98G::blkThrs - $knownY, SA98G::blkClmp);

     // set the known and unknown exponents
    if ($knownType === 'bg' || $knownType === 'background') {
        $knownExp   = $contrast > 0 ? SA98G::normBG  : SA98G::revBG;
        $unknownExp = $contrast > 0 ? SA98G::normTXT : SA98G::revTXT;
        $unknownY   = pow(pow($knownY, $knownExp) - $contrast, 1 / $unknownExp);
        if (INF === $unknownY) return false;
    } else if ($knownType === 'txt' || $knownType === 'text') {
        $knownExp   = $contrast > 0 ? SA98G::normTXT : SA98G::revTXT;
        $unknownExp = $contrast > 0 ? SA98G::normBG : SA98G::revBG;
        $unknownY   = pow($contrast + pow($knownY, $knownExp), 1 / $unknownExp);
        if (INF === $unknownY) return false;
    } else { return false; } // return false on error

    if ($unknownY > 1.06 || $unknownY < 0) {
        return false; // return false on overflow
    }

    // unclamp
    $unknownY = ($unknownY > SA98G::blkThrs) ? $unknownY : (pow(
            (($unknownY + SA98G::mOffsetIn) * SA98G::mFactor), SA98G::getMExp()
        ) * SA98G::getMFactInv()) - SA98G::mOffsetOut;

    $unknownY = max(min($unknownY, 1.0), 0.0);

    switch ($returnAs) {
        case 'hex':
            $hexB = str_pad((string) (
            round(pow($unknownY, SA98G::getMainTRCencode()) * 255)
            ), 2, '0', STR_PAD_LEFT);

            return '#' . $hexB . $hexB . $hexB;

        case 'color':
            $colorB = round(pow($unknownY, SA98G::getMainTRCencode()) * 255);
            return new Rgb($colorB, $colorB, $colorB);

        case 'Y':
        case 'y':
            return max(0.0, $unknownY);

        default:
            return false;
    }
}

/**
 * @param  ColorInterface  $textColor
 * @param  ColorInterface  $bgColor
 * @param  int             $places     Number of decimal places to return.
 *                                     Default `-1` returns a signed float, `0`
 *                                     returns a rounded string, and `1` or more
 *                                     returns a string with that many places.
 * @param  bool            $round
 * @return float|string
 *
 * @noinspection PhpUnused
 */
function calcAPCA(
    ColorInterface $textColor,
    ColorInterface $bgColor,
    int            $places = -1,
    bool           $round = true,
): float|string {
	$bgClr = $bgColor->toRgb();
	$txClr = $textColor->toRgb();
	$hasAlpha = $txClr->opacity !== 255.0;

	if ($hasAlpha) { $txClr = alphaBlend($txClr, $bgClr, $round); }

	$lc = APCAcontrast(sRGBtoY($txClr), sRGBtoY($bgClr), $places);

    return $lc;
} // End calcAPCA()

/**
 * CONTRAST * FONT WEIGHT & SIZE
 *
 * Font size interpolations. Here the chart was re-ordered to put
 * the main contrast levels each on one line, instead of font size per line.
 * First column is LC value, then each following column is font size by weight
 *
 * G G G G G G  Public Beta 0.1.7 (G) • MAY 28 2022
 *
 * Lc values under 70 should have Lc 15 ADDED if used for body text
 * All font sizes are in px and reference font is Barlow
 *
 * 999: prohibited - too low contrast
 * 777: NON TEXT at this minimum weight stroke
 * 666 - this is for spot text, not fluent-Things like copyright or placeholder.
 * 5xx - minimum font at this weight for content, 5xx % 500 for font-size
 * 4xx - minimum font at this weight for any purpose, 4xx % 400 for font-size
 *
 * MAIN FONT SIZE LOOKUP
 *
 * @param  int|float  $contrast
 * @param  int        $places
 * @return array{0: float, 1: float, 2: float, 3: float, 4: float,
 *               5: float, 6: float, 7: float, 8: float, 9: float}
 *
 * @noinspection PhpUnused
 */
function fontLookupAPCA(int|float $contrast, int $places = 2): array
{
    /**
     * ASCENDING SORTED  Public Beta 0.1.7 (G) • MAY 28 2022
     *
     * Lc 45 * 0.2 = 9 which is the index for the row for Lc 45
     *
     * MAIN FONT LOOKUP May 28 2022 EXPANDED
     * Sorted by Lc Value
     * First row is standard weights 100-900
     * First column is font size in px
     * All other values are the Lc contrast
     * 999 = too low. 777 = non-text and spot text only
     */
    $fontMatrixAscend = [
        ['Lc', 100,  200,   300,   400,   500,     600,   700,    800,  900],
        [   0, 999,  999,   999,   999,   999,     999,   999,    999,  999],
        [  10, 999,  999,   999,   999,   999,     999,   999,    999,  999],
        [  15, 777,  777,   777,   777,   777,     777,   777,    777,  777],
        [  20, 777,  777,   777,   777,   777,     777,   777,    777,  777],
        [  25, 777,  777,   777,   120,   120,     108,    96,     96,   96],
        [  30, 777,  777,   120,   108,   108,     96,     72,     72,   72],
        [  35, 777,  120,   108,    96,    72,     60,     48,     48,   48],
        [  40, 120,  108,    96,    60,    48,     42,     32,     32,   32],
        [  45, 108,   96,    72,    42,    32,     28,     24,     24,   24],
        [  50,  96,   72,    60,    32,    28,     24,     21,     21,   21],
        [  55,  80,   60,    48,    28,    24,     21,     18,     18,   18],
        [  60,  72,   48,    42,    24,    21,     18,     16,     16,   18],
        [  65,  68,   46,    32,    21.75, 19,     17,     15,     16,   18],
        [  70,  64,   44,    28,    19.5,  18,     16,     14.5,   16,   18],
        [  75,  60,   42,    24,    18,    16,     15,     14,     16,   18],
        [  80,  56,   38.25, 23,    17.25, 15.81,  14.81,  14,     16,   18],
        [  85,  52,   34.5,  22,    16.5,  15.625, 14.625, 14,     16,   18],
        [  90,  48,   32,    21,    16,    15.5,   14.5,   14,     16,   18],
        [  95,  45,   28,    19.5,  15.5,  15,     14,     13.5,   16,   18],
        [ 100,  42,   26.5,  18.5,  15,    14.5,   13.5,   13,     16,   18],
        [ 105,  39,   25,    18,    14.5,  14,     13,     12,     16,   18],
        [ 110,  36,   24,    18,    14,    13,     12,     11,     16,   18],
        [ 115,  34.5, 22.5,  17.25, 12.5,  11.875, 11.25,  10.625, 14.5, 16.5],
        [ 120,  33,   21,    16.5,  11,    10.75,  10.5,   10.25,  13,   15],
        [ 125,  32,   20,    16,    10,    10,     10,     10,     12,   14],
    ];

    /**
     * ASCENDING SORTED  Public Beta 0.1.7 (G) • MAY 28 2022
     *
     * DELTA - MAIN FONT LOOKUP May 28 2022 EXPANDED
     * EXPANDED  Sorted by Lc Value ••  DELTA
     * The pre-calculated deltas of the above array
     */
    $fontDeltaAscend = [
        ['∆Lc', 100,   200,  300,  400,   500,   600,   700, 800, 900],
        [    0,   0,     0,    0,    0,     0,     0,     0,   0,   0],
        [   10,   0,     0,    0,    0,     0,     0,     0,   0,   0],
        [   15,   0,     0,    0,    0,     0,     0,     0,   0,   0],
        [   20,   0,     0,    0,    0,     0,     0,     0,   0,   0],
        [   25,   0,     0,    0,   12,    12,    12,    24,  24,  24],
        [   30,   0,     0,   12,   12,    36,    36,    24,  24,  24],
        [   35,   0,    12,   12,   36,    24,    18,    16,  16,  16],
        [   40,   12,   12,   24,   18,    16,    14,     8,   8,   8],
        [   45,   12,   24,   12,   10,     4,     4,     3,   3,   3],
        [   50,   16,   12,   12,    4,     4,     3,     3,   3,   3],
        [   55,   8,    12,    6,    4,     3,     3,     2,   2,   0],
        [   60,   4,     2,   10, 2.25,     2,     1,     1,   0,   0],
        [   65,   4,     2,    4, 2.25,     1,     1,   0.5,   0,   0],
        [   70,   4,     2,    4,  1.5,     2,     1,   0.5,   0,   0],
        [   75,   4,  3.75,    1, 0.75, 0.188, 0.188,     0,   0,   0],
        [   80,   4,  3.75,    1, 0.75, 0.188, 0.188,     0,   0,   0],
        [   85,   4,   2.5,    1,  0.5, 0.125, 0.125,     0,   0,   0],
        [   90,   3,     4,  1.5,  0.5,   0.5,   0.5,   0.5,   0,   0],
        [   95,   3,   1.5,    1,  0.5,   0.5,   0.5,   0.5,   0,   0],
        [  100,   3,   1.5,  0.5,  0.5,   0.5,   0.5,     1,   0,   0],
        [  105,   3,     1,    0,  0.5,     1,     1,     1,   0,   0],
        [  110,   1.5, 1.5, 0.75,  1.5, 1.125,  0.75, 0.375, 1.5, 1.5],
        [  115,   1.5, 1.5, 0.75,  1.5, 1.125,  0.75, 0.375, 1.5, 1.5],
        [  120,   1,     1,  0.5,    1,  0.75,   0.5,  0.25,   1,   1],
        [  125,   0,     0,    0,    0,     0,     0,     0,   0,   0],
    ];

    /**
     * APCA CONTRAST FONT LOOKUP TABLES
     *
     * @copyright 2022 by Myndex Research and Andrew Somers. All Rights Reserved
     * Public Beta 0.1.7 (G) • MAY 28 2022
     * For the following arrays, the Y axis is contrastArrayLen
     * The two x axis are weightArrayLen and scoreArrayLen
     * MAY 28 2022
     */
    $weightArray    = [0, 100, 200, 300, 400, 500, 600, 700, 800, 900];
    $weightArrayLen = count($weightArray); // X axis

    $returnArray    = [round($contrast, $places), 0, 0, 0, 0, 0, 0, 0, 0, 0];
    //$returnArrayLen = count($returnArray); // X axis


    //$contrastArrayAscend = ['lc', 0, 10, 15, 20, 25, 30, 35, 40, 45, 50,
    //    55, 60, 65, 70, 75, 80, 85, 90, 95, 100, 105, 110, 115, 120, 125,];
    //$contrastArrayLenAsc = count($contrastArrayAscend); // Y azis

    //// Lc 45 * 0.2 = 9, and 9 is the index for the row for Lc 45

    //$tempFont = 777;
    $contrast = abs($contrast); // Polarity unneeded for LUT
    $factor = 0.2; // 1/5 as LUT is in increments of 5
    // LUT row... n|0 is bw floor
    $index = ($contrast === 0) ? 1 : ($contrast * $factor) | 0 ;
    $w = 0;
    // scoreAdj interpolates the needed font side per the Lc
    $scoreAdj = ($contrast - $fontMatrixAscend[$index][$w]) * $factor;

    $w++; // determines column in font matrix LUT


    /////////  Font and Score Interpolation  \/////////////////////////////////

    // populate returnArray with interpolated values

    for (; $w < $weightArrayLen; $w++) {

        $tempFont = $fontMatrixAscend[$index][$w];

        if ($tempFont > 400) { // declares a specific minimum for the weight.
            $returnArray[$w] = $tempFont;
        } else if ($contrast < 14.5 ) {
            $returnArray[$w] = 999; //  999 = do not use for anything
        } else if ($contrast < 29.5 ) {
            $returnArray[$w] = 777; // 777 =  non-text only
        } else {
            // INTERPOLATION OF FONT SIZE
            // sets level for 0.5px size increments of smaller fonts
            // Note bitwise (n|0) instead of floor
            $delta = $fontDeltaAscend[$index][$w] * $scoreAdj;
            ($tempFont > 24) ?
                $returnArray[$w] = round($tempFont - $delta) :
                $returnArray[$w] = $tempFont - ((2.0 * $delta) | 0) * 0.5;
            // (n|0) is bitwise floor
        }
    }
    /////////  End Interpolation  ////////////////////////////////////////////

    return $returnArray;
} // end fontLookupAPCA


//////////////////////////////////////////////////////////////////////////////
//////////  LUMINANCE CONVERTERS  |//////////////////////////////////////////

/**
 * send sRGB 8bpc (0xFFFFFF) or string.
 *
 * NOTE: Currently expects 0-255
 *
 * @param  ColorInterface  $rgb
 * @return float
 */
function sRGBtoY(ColorInterface $rgb): float
{
    /////   APCA   0.0.98G - 4g - W3 Compatible Constants   ////////////////////
    /*
    $mainTRC = 2.4; // 2.4 exponent emulates actual monitor perception

    $sRco = 0.2126729;
    $sGco = 0.7151522;
    $sBco = 0.0721750; // sRGB coefficients
    */
    // Future:
    // 0.2126478133913640	0.7151791475336150	0.0721730390750208
    // Derived from:
    // xW	yW	K	xR	yR	xG	yG	xB	yB
    // 0.312720	0.329030	6504	0.640	0.330	0.300	0.600	0.150	0.060

    // linearize r, g, or b then apply coefficients
    // and sum then return the resulting luminance

    $rgb = $rgb->toRgb();

    $simpleExp = static function (float|int $chan): float {
        $exp = pow($chan / 255.0, SA98G::mainTRC);
        return $exp;
    };

    $r = SA98G::sRco * $simpleExp($rgb->red);
    $g = SA98G::sGco * $simpleExp($rgb->green);
    $b = SA98G::sBco * $simpleExp($rgb->blue);

    return $r + $g + $b;
} // End sRGBtoY()

/**
 * NOTE: Currently Apple has the tuple as 0.0 to 1.0, NOT 255
 *
 * @param  \matthieumastadenis\couleur\colors\P3  $rgb
 * @return float
 *
 * @noinspection PhpUnused
 */
function displayP3toY(P3 $rgb): float
{
    /////   APCA   0.0.98G - 4g - W3 Compatible Constants   ////////////////////

    $mainTRC = 2.4; // 2.4 exponent emulates actual monitor perception
                    // Pending evaluation, because, Apple...

    $sRco = 0.2289829594805780;
    $sGco = 0.6917492625852380;
    $sBco = 0.0792677779341829; // displayP3 coefficients

    // Derived from:
    // xW	yW	K	xR	yR	xG	yG	xB	yB
    // 0.312720	0.329030	6504	0.680	0.320	0.265	0.690	0.150	0.060

    // linearize r, g, or b then apply coefficients
    // and sum then return the resulting luminance

    $simpleExp = static function (float|int $chan) use ($mainTRC): float {
        return pow($chan, $mainTRC);
    };

    return $sRco * $simpleExp($rgb->red) +
           $sGco * $simpleExp($rgb->green) +
           $sBco * $simpleExp($rgb->blue);
} // End displayP3toY()

/**
 * @param  Rgb  $rgb
 * @return float
 *
 * @noinspection PhpUnused
 */
function adobeRGBtoY(Rgb $rgb): float {

    /////   APCA   0.0.98G - 4g - W3 Compatible Constants   ////////////////////

    $mainTRC = 2.35; // 2.35 exponent emulates actual monitor perception
                     // Pending evaluation...

    $sRco = 0.2973550227113810;
    $sGco = 0.6273727497145280;
    $sBco = 0.0752722275740913; // adobeRGB coefficients

    // Derived from:
    // xW	yW	K	xR	yR	xG	yG	xB	yB
    // 0.312720	0.329030	6504	0.640	0.330	0.210	0.710	0.150	0.060

    // linearize r, g, or b then apply coefficients
    // and sum then return the resulting luminance

    $simpleExp = function ($chan) use ($mainTRC) {
        return pow($chan / 255.0, $mainTRC);
    };

    return $sRco * $simpleExp($rgb[0]) +
           $sGco * $simpleExp($rgb[1]) +
           $sBco * $simpleExp($rgb[2]);
} // End adobeRGBtoY()


////////////////////////////////////////////////////////////////////////////
//////////  UTILITIES  \///////////////////////////////////////////////////

/**
 * Blends using gamma encoded space (standard).
 *
 * @param  Rgb   $rgbaFG  RGBa for text/icon. Allows opacity of 0 to 255.
 * @param  Rgb   $rgbBG   RGB for background.
 * @param  bool  $round   Rounded 0-255 or set round=false for number 0.0-255.0.
 * @return Rgb
 */
function alphaBlend(Rgb $rgbaFG, Rgb $rgbBG, bool $round = true): Rgb
{
    throw new \RuntimeException('Not (properly) implemented yet');

	// clamp opacity 0-255
    $rgbaFG->change(opacity: max(min($rgbaFG->opacity, 255), 0));
	$compBlend = 255 - $rgbaFG->opacity; // TODO; not correct
    $rgbOut = [0, 0, 0, 255]; // or just use rgbBG to retain other elements?

	for ($i=0; $i < 3; $i++) {
		$rgbOut[$i] = $rgbBG[$i] * $compBlend + $rgbaFG[$i] * $rgbaFG[3];
		if ($round) $rgbOut[$i] = min(round($rgbOut[$i]), 255);
	}

    return new Rgb(
        red: $rgbBG->red * $compBlend + $rgbaFG->red * $rgbaFG->opacity, // TODO; not correct
        green: $rgbBG->green * $compBlend + $rgbaFG->green * $rgbaFG->opacity, // TODO; not correct
        blue: $rgbBG->blue * $compBlend + $rgbaFG->blue * $rgbaFG->opacity, // TODO; not correct
    );
} // End alphaBlend()
