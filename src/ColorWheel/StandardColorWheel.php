<?php

/**
 * @noinspection PhpUnused
 * @noinspection UnknownInspectionInspection
 */

declare(strict_types=1);

namespace Empaphy\Colorphul\ColorWheel;

use matthieumastadenis\couleur\ColorInterface;
use matthieumastadenis\couleur\colors\OkLch;
use TypeError;

/**
 * A standard 12-hue color wheel.
 *
 * @property-read ColorInterface $red
 * @property-read ColorInterface $orange
 * @property-read ColorInterface $yellow
 * @property-read ColorInterface $chartreuse
 * @property-read ColorInterface $green
 * @property-read ColorInterface $spring
 * @property-read ColorInterface $cyan
 * @property-read ColorInterface $azure
 * @property-read ColorInterface $blue
 * @property-read ColorInterface $violet
 * @property-read ColorInterface $magenta
 * @property-read ColorInterface $rose
 */
class StandardColorWheel implements StandardColorWheelInterface
{
    public const HUE_INDEX_MAPPING = [
        0 => StandardColorWheelHue::Red,
        1 => StandardColorWheelHue::Orange,
        2 => StandardColorWheelHue::Yellow,
        3 => StandardColorWheelHue::Chartreuse,
        4 => StandardColorWheelHue::Green,
        5 => StandardColorWheelHue::Spring,
        6 => StandardColorWheelHue::Cyan,
        7 => StandardColorWheelHue::Azure,
        8 => StandardColorWheelHue::Blue,
        9 => StandardColorWheelHue::Violet,
        10 => StandardColorWheelHue::Magenta,
        11 => StandardColorWheelHue::Rose,
    ];

    /**
     * @var int
     */
    public int $position = 0;

    /**
     * @param  float  $lightness  The lightness value.
     * @param  float  $chroma     The chroma value.
     * @param  float  $hueOffset  The hue offset.
     */
    public function __construct(
        public    readonly float $lightness,
        public    readonly float $chroma,
        protected readonly float $hueOffset,
    ) {}

    /**
     * @param  value-of<StandardColorWheelHue>  $name
     * @return ColorInterface
     *
     * @throws TypeError If the given name is not a valid hue name as defined by {@see StandardColorWheelHue}.
     */
    public function __get(string $name): ColorInterface
    {
        return $this->offsetGet($name);
    }

    /**
     * Returns the hue value for the given index.
     *
     * @param  StandardColorWheelHue  $hue  The hue.
     * @return float The hue value in degrees.
     */
    public function getHue(StandardColorWheelHue $hue): float
    {
        $hueCount = count(StandardColorWheelHue::cases());
        $index    = array_search($hue, self::HUE_INDEX_MAPPING, true);

        $index %= $hueCount;
        $degreesPerHue = 360 / $hueCount;

        return $this->hueOffset + $degreesPerHue * $index;
    }

    public function current(): ColorInterface
    {
        return $this->offsetGet($this->key());
    }

    public function next(): void
    {
        $this->position++;
    }

    /**
     * @return value-of<StandardColorWheelHue>
     */
    public function key(): string
    {
        return self::HUE_INDEX_MAPPING[$this->position]->value;
    }

    public function valid(): bool
    {
        return isset(self::HUE_INDEX_MAPPING[$this->position]);
    }

    public function rewind(): void
    {
        $this->position = 0;
    }

    /**
     * @param  value-of<StandardColorWheelHue>  $offset
     * @return bool
     */
    public function offsetExists(mixed $offset): bool
    {
        return StandardColorWheelHue::tryFrom($offset) !== null;
    }

    /**
     * Returns a ColorInterface object based on the given index.
     *
     * @param  value-of<StandardColorWheelHue>  $offset  The index value.
     * @return ColorInterface The ColorInterface object representing the color.
     *
     * @throws TypeError If the given name is not a valid hue name as defined by {@see StandardColorWheelHue}.
     */
    public function offsetGet(mixed $offset): ColorInterface
    {
        return new OkLch($this->lightness, $this->chroma, $this->getHue(StandardColorWheelHue::from($offset)));
    }

    /**
     * @param  value-of<StandardColorWheelHue>  $offset  The index value.
     * @param  ColorInterface                   $value   The index value.
     * @noinspection PhpRedundantOptionalArgumentInspection
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        trigger_error('StandardColorWheel is a read-only Array object', E_USER_NOTICE);
    }

    /**
     * @noinspection PhpRedundantOptionalArgumentInspection
     */
    public function offsetUnset(mixed $offset): void
    {
        trigger_error('StandardColorWheel is a read-only Array object', E_USER_NOTICE);
    }

    public function count(): int
    {
        return count(StandardColorWheelHue::cases());
    }
}
