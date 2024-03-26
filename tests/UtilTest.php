<?php

declare(strict_types=1);

namespace Empaphy\Colorphul\Tests;

use Empaphy\Colorphul\Util;
use matthieumastadenis\couleur\colors\OkLab;
use PHPUnit\Framework\TestCase;

class UtilTest extends TestCase
{
    public function testClipChroma(): void
    {
        // Color very much outside the sRGB gamut.
        $color = new OkLab(75, 0, 0.37);
        $this->assertFalse(Util::colorWithinSrgbGamut(new OkLab(75, 0, 0.37)));

        $clipped = Util::clipChroma($color);
        $this->assertTrue(Util::colorWithinSrgbGamut($clipped));
    }

    public function testColorWithinSrgbGamut(): void
    {
        // Colors very much outside the sRGB gamut.
        $this->assertFalse(Util::colorWithinSrgbGamut(new OkLab(75, 0, 0.37)));
        $this->assertFalse(Util::colorWithinSrgbGamut(new OkLab(75, 0, -0.37)));
        $this->assertFalse(Util::colorWithinSrgbGamut(new OkLab(75, 0.37, 0)));
        $this->assertFalse(Util::colorWithinSrgbGamut(new OkLab(75, -0.37, 0)));

        // A color very much inside the sRGB gamut.
        $this->assertTrue(Util::colorWithinSrgbGamut(new OkLab(75, 0, 0.1)));
        $this->assertTrue(Util::colorWithinSrgbGamut(new OkLab(75, 0, -0.1)));
        $this->assertTrue(Util::colorWithinSrgbGamut(new OkLab(75, 0.1, 0)));
        $this->assertTrue(Util::colorWithinSrgbGamut(new OkLab(75, -0.1, 0)));
    }

    public function testFloatExceedsPrecision(): void
    {
        $this->assertTrue(Util::floatExceedsPrecision(0.0000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000001, 99));
        $this->assertTrue(Util::floatExceedsPrecision(0.0000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000001, 100));
        $this->assertFalse(Util::floatExceedsPrecision(0.0000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000001, 101));
        $this->assertTrue(Util::floatExceedsPrecision(0.000000000000001, 15));
        $this->assertTrue(Util::floatExceedsPrecision(0.999999999999999, 15));
        $this->assertTrue(Util::floatExceedsPrecision(-0.000000000000001, 15));
        $this->assertTrue(Util::floatExceedsPrecision(-0.999999999999999, 15));

        $this->assertTrue(Util::floatExceedsPrecision(0.1234, 3));
        $this->assertTrue(Util::floatExceedsPrecision(0.1234, 4));
        $this->assertFalse(Util::floatExceedsPrecision(0.1234, 5));

        $this->assertTrue(Util::floatExceedsPrecision(0.0001, 3));
        $this->assertTrue(Util::floatExceedsPrecision(0.0001, 4));
        $this->assertFalse(Util::floatExceedsPrecision(0.0001, 5));
        $this->assertTrue(Util::floatExceedsPrecision(0.9999, 3));
        $this->assertTrue(Util::floatExceedsPrecision(0.9999, 4));
        $this->assertFalse(Util::floatExceedsPrecision(0.9999, 5));

        $this->assertTrue(Util::floatExceedsPrecision(-0.1234, 3));
        $this->assertTrue(Util::floatExceedsPrecision(-0.1234, 4));
        $this->assertFalse(Util::floatExceedsPrecision(-0.1234, 5));
        $this->assertTrue(Util::floatExceedsPrecision(-0.0001, 3));
        $this->assertTrue(Util::floatExceedsPrecision(-0.0001, 4));
        $this->assertFalse(Util::floatExceedsPrecision(-0.0001, 5));
        $this->assertTrue(Util::floatExceedsPrecision(-0.9999, 3));
        $this->assertTrue(Util::floatExceedsPrecision(-0.9999, 4));
        $this->assertFalse(Util::floatExceedsPrecision(-0.9999, 5));
    }
}
