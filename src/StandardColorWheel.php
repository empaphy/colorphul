<?php

/**
 * @noinspection PhpUnused
 * @noinspection UnknownInspectionInspection
 */

declare(strict_types=1);

namespace Empaphy\Colorphul;

use matthieumastadenis\couleur\ColorInterface;
use matthieumastadenis\couleur\colors\OkLch;

class StandardColorWheel
{
    private const HUE_RED        = 'red';
    private const HUE_ORANGE     = 'orange';
    private const HUE_YELLOW     = 'yellow';
    private const HUE_CHARTREUSE = 'chartreuse';
    private const HUE_GREEN      = 'green';
    private const HUE_SPRING     = 'spring';
    private const HUE_CYAN       = 'cyan';
    private const HUE_AZURE      = 'azure';
    private const HUE_BLUE       = 'blue';
    private const HUE_VIOLET     = 'violet';
    private const HUE_MAGENTA    = 'magenta';
    private const HUE_ROSE       = 'rose';

    private const HUES = [
        self::HUE_RED,
        self::HUE_ORANGE,
        self::HUE_YELLOW,
        self::HUE_CHARTREUSE,
        self::HUE_GREEN,
        self::HUE_SPRING,
        self::HUE_CYAN,
        self::HUE_AZURE,
        self::HUE_BLUE,
        self::HUE_VIOLET,
        self::HUE_MAGENTA,
        self::HUE_ROSE,
    ];

    public readonly ColorInterface $red;
    public readonly ColorInterface $orange;
    public readonly ColorInterface $yellow;
    public readonly ColorInterface $chartreuse;
    public readonly ColorInterface $green;
    public readonly ColorInterface $spring;
    public readonly ColorInterface $cyan;
    public readonly ColorInterface $azure;
    public readonly ColorInterface $blue;
    public readonly ColorInterface $violet;
    public readonly ColorInterface $magenta;
    public readonly ColorInterface $rose;

    /**
     * @param  float  $lightness  The lightness value.
     * @param  float  $chroma     The chroma value.
     * @param  float  $hueOffset  The hue offset.
     */
    public function __construct(
        public    readonly float $lightness,
        public    readonly float $chroma,
        protected readonly float $hueOffset,
    ){
        foreach (self::HUES as $index => $hue) {
            $this->{$hue} = $this->getColor($index);
        }
    }

    /**
     * Returns the hue value for the given index.
     *
     * @param  int  $index  The index of the hue.
     * @return float The hue value in degrees.
     */
    public function getHue(int $index): float
    {
        $hueCount = count(self::HUES);

        $index %= $hueCount;
        $degreesPerHue = 360 / $hueCount;

        return $this->hueOffset + $degreesPerHue * $index;
    }

    /**
     * Returns a ColorInterface object based on the given index.
     *
     * @param  int  $index  The index value.
     * @return ColorInterface The ColorInterface object representing the color.
     */
    public function getColor(int $index): ColorInterface
    {
        return new OkLch($this->lightness, $this->chroma, $this->getHue($index));
    }
}
