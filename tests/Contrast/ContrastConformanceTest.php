<?php

declare(strict_types=1);

namespace Empaphy\Colorphul\Tests\Contrast;

use Empaphy\Colorphul\Color\ColorDesignation;
use Empaphy\Colorphul\Color\Designations\BackgroundColor;
use Empaphy\Colorphul\Color\Designations\TextColor;
use matthieumastadenis\couleur\ColorSpace;
use PHPUnit\Framework\TestCase;

class ContrastConformanceTest extends TestCase
{
    public function testReverse(): void
    {
        $coordinate = 'lightness';

        foreach ([ColorSpace::Hsl, ColorSpace::OkLch] as $colorSpace) {
            $conformance = new FakeContrastConformance($colorSpace, $coordinate);
            $colorClass = $colorSpace->value;

            $black = new $colorClass(...[$coordinate => 0]);
            $gray = new $colorClass(...[$coordinate => 50]);
            $white = new $colorClass(...[$coordinate => 100]);

            $reverseBackground = $conformance->reverse(new TextColor($gray), -25, 5, $colorSpace, $coordinate);
            $this->assertEquals(ColorDesignation::Background, $reverseBackground->designation);
            $this->assertEquals((new $colorClass(...[$coordinate => 25]))->{$coordinate}, $reverseBackground->color->to($colorSpace)->{$coordinate});

            $reverseText = $conformance->reverse(new BackgroundColor($gray), -25, 5, $colorSpace, $coordinate);
            $this->assertEquals(ColorDesignation::Text, $reverseText->designation);
            $this->assertEquals((new $colorClass(...[$coordinate => 75]))->{$coordinate}, $reverseText->color->to($colorSpace)->{$coordinate});
        }
    }

}
