<?php

declare(strict_types=1);

namespace Empaphy\Colorphul\Tests\utils;

use PHPUnit\Framework\TestCase;
use function Empaphy\Colorphul\utils\wcag\contrast_ratio;
use function Empaphy\Colorphul\utils\wcag\linear;
use function Empaphy\Colorphul\utils\wcag\relative_luminance;
use function Empaphy\Colorphul\utils\wcag\srgb;

class WcagTest extends TestCase
{
    public function test_contrast_ratio(): void
    {
        $this->assertEquals(1, contrast_ratio(0.0, 0.0));
        $this->assertEquals(1, contrast_ratio(1.0, 1.0));

        $this->assertEquals(21, contrast_ratio(1.0, 0.0));
    }

    public function test_relative_luminance(): void
    {
        $this->assertEquals(0.0, relative_luminance(0, 0, 0));
        $this->assertEquals(1.0, relative_luminance(255, 255, 255));

        $this->assertEquals(
            0.2126 * (10 / 255 / 12.92) +
            0.7152 * (10 / 255 / 12.92) +
            0.0722 * (10 / 255 / 12.92),
            relative_luminance(10, 10, 10)
        );

        $this->assertEquals(
            0.2126 * (((11 / 255 + 0.055) / 1.055) ** 2.4) +
            0.7152 * (((11 / 255 + 0.055) / 1.055) ** 2.4) +
            0.0722 * (((11 / 255 + 0.055) / 1.055) ** 2.4),
            relative_luminance(11, 11, 11)
        );
    }

    public function test_srgb(): void
    {
        for ($i = 0; $i <= 255; $i++) {
            $this->assertEquals($i / 255, srgb($i));
        }
    }

    public function test_linear(): void
    {
        $this->assertEquals(0.0 / 12.92, linear(0.0));
        $this->assertEquals(0.04044 / 12.92, linear(0.04044));
        $this->assertEquals(0.04045 / 12.92, linear(0.04045));

        $this->assertEquals(((0.04046 + 0.055) / 1.055) ** 2.4, linear(0.04046));
        $this->assertEquals(((1.0 + 0.055) / 1.055) ** 2.4, linear(1.0));
    }
}
