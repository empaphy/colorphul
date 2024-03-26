<?php

declare(strict_types=1);

namespace Empaphy\Colorphul\Tests;

use Empaphy\Colorphul\Wcag;
use LogicException;
use matthieumastadenis\couleur\colors\Hsl;
use PHPUnit\Framework\TestCase;

class WcagTest extends TestCase
{
    public function testColorContrastRatio(): void
    {
        $contrastRatio = Wcag::colorContrastRatio(
            new Hsl(0, 0, 0),
            new Hsl(0, 0, 0),
        );
        $this->assertEquals(1, $contrastRatio);

        $contrastRatio = Wcag::colorContrastRatio(
            new Hsl(0, 0, 100),
            new Hsl(0, 0, 0),
        );
        $this->assertEquals(21, $contrastRatio);

        $contrastRatio = Wcag::colorContrastRatio(
            new Hsl(0, 0, 0),
            new Hsl(0, 0, 100),
        );
        $this->assertEquals(21, $contrastRatio);
    }

    public function testContrastRatio(): void
    {
        $contrastRatio = Wcag::contrastRatio(0, 0);
        $this->assertEquals(1, $contrastRatio);

        $contrastRatio = Wcag::contrastRatio(1, 0);
        $this->assertEquals(21, $contrastRatio);

        $this->expectException(LogicException::class);
        Wcag::contrastRatio(0.9, 1);
    }

    public function testReverseDarker(): void
    {
        $luminance = Wcag::reverseDarker(21, 1);
        $this->assertEquals(0, $luminance);
    }

    public function testReverseLighter(): void
    {
        $luminance = Wcag::reverseLighter(21, 0);
        $this->assertEquals(1, $luminance);
    }
}
