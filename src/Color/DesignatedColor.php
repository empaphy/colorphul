<?php

/**
 * @noinspection PhpDocSignatureInspection
 */

declare(strict_types=1);

namespace Empaphy\Colorphul\Color;

use Empaphy\Colorphul\Color\Designations\BackgroundColor;
use Empaphy\Colorphul\Color\Designations\BackgroundColorInterface;
use Empaphy\Colorphul\Color\Designations\TextColor;
use Empaphy\Colorphul\Color\Designations\TextColorInterface;
use matthieumastadenis\couleur\ColorInterface;
use matthieumastadenis\couleur\ColorSpace;
use RangeException;

/**
 * @template TDesignation of ColorDesignation
 * @template TColor       of ColorInterface
 * @template-implements DesignatedColorInterface<TDesignation, TColor>
 */
abstract class DesignatedColor implements DesignatedColorInterface
{
    /**
     * @param  TDesignation  $designation
     * @param  TColor        $color
     */
    public function __construct(
        public readonly ColorDesignation $designation,
        public ColorInterface $color,
    ) {}

    /**
     * Calls `$callable` with the Color designated as {@see ColorDesignation::Text text} as the first argument, and the
     * Color designated as {@see ColorDesignation::Background background} as the second.
     *
     * @template C1 of ColorInterface
     * @template C2 of ColorInterface
     * @template R
     *
     * @param  DesignatedColorInterface<ColorDesignation, C1>  $designatedColor1
     * @param  DesignatedColorInterface<ColorDesignation, C2>  $designatedColor2
     * @param  callable(TextColorInterface<(
     *             $designatedColor1 is TextColorInterface<C1> ? C1 : C2
     *         )> $text, BackgroundColorInterface<(
     *             $designatedColor1 is BackgroundColorInterface<C1> ? C1 : C2
     *         )> $background): R  $callable
     * @return R
     *
     * @throws RangeException if both {@see DesignatedColorInterface::designation designations} are the same.
     */
    public static function designate(
        DesignatedColorInterface $designatedColor1,
        DesignatedColorInterface $designatedColor2,
        callable $callable,
    ): mixed {
        if ($designatedColor1 instanceof TextColorInterface && $designatedColor2 instanceof BackgroundColorInterface) {
            return $callable($designatedColor1, $designatedColor2);
        }

        if ($designatedColor1 instanceof BackgroundColorInterface && $designatedColor2 instanceof TextColorInterface) {
            return $callable($designatedColor2, $designatedColor1);
        }

        throw new RangeException('Designated colors must have different designations.');
    }

    /**
     * Sorts the given colors from brightest to darkest.
     *
     * @param  DesignatedColorInterface<ColorDesignation, ColorInterface>[]  $colors
     * @return DesignatedColorInterface<ColorDesignation, ColorInterface>[]
     */
    public static function sort(array $colors, ColorSpace $colorSpace = null, string $coordinate = 'lightness'): array
    {
        usort(
            $colors,
            static function (DesignatedColorInterface $a, DesignatedColorInterface $b) use ($colorSpace, $coordinate) {
                return $b->color->to($colorSpace)->{$coordinate} <=> $a->color->to($colorSpace)->{$coordinate};
            }
        );

        return $colors;
    }

    /**
     * Constructs a {@see DesignatedColor} for the given
     * {@see ColorDesignation $designation} and {@see ColorInterface $color}.
     *
     * @template D of ColorDesignation
     * @template C of ColorInterface
     *
     * @param  D  $designation
     * @param  C  $color
     * @return (D is ColorDesignation::Text ? TextColorInterface<C> : BackgroundColorInterface<C>)
     */
    public static function forDesignation(
        ColorDesignation $designation,
        ColorInterface $color,
    ): TextColorInterface | BackgroundColorInterface {
        return match ($designation) {
            ColorDesignation::Background => new BackgroundColor($color),
            ColorDesignation::Text       => new TextColor($color),
        };
    }
}
