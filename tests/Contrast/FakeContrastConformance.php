<?php

declare(strict_types=1);

namespace Empaphy\Colorphul\Tests\Contrast;

use Empaphy\Colorphul\Color\Designations\BackgroundColorInterface;
use Empaphy\Colorphul\Color\Designations\TextColorInterface;
use Empaphy\Colorphul\Contrast\ContrastConformance;
use Empaphy\Colorphul\Contrast\ContrastPolarity;
use matthieumastadenis\couleur\ColorSpace;

final class FakeContrastConformance extends ContrastConformance
{
    public static float $max = 100.0;
    public static float $min = 0.0;

    public function __construct(
        private readonly ColorSpace $colorSpace,
        private readonly string $lightnessProperty,
    ) {}

    public static function max(ContrastPolarity $polarity): float
    {
        return self::$max;
    }

    public static function min(ContrastPolarity $polarity): float
    {
        return 0;
    }

    public function contrast (TextColorInterface $text, BackgroundColorInterface $background): float {
        $textLightness = $text->color->to($this->colorSpace)->{$this->lightnessProperty};
        $backgroundLightness = $background->color->to($this->colorSpace)->{$this->lightnessProperty};

        return $backgroundLightness - $textLightness;
    }
}
