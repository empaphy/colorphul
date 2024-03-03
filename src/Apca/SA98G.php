<?php

declare(strict_types=1);

namespace Empaphy\Colorphul\Apca;

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
    public static function getMainTRCencode(): float
    {
        return 1 / self::mainTRC;
    }

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
    public const mFactor = 1.94685544331710;

    public static function getMFactInv(): float
    {
        return 1 / self::mFactor;
    }

    public const mOffsetIn = 0.03873938165714010;
    public const mExpAdj   = 0.2833433964208690;

    public static function getMExp(): float
    {
        return self::mExpAdj / self::blkClmp;
    }

    public const mOffsetOut = 0.3128657958707580;
}
