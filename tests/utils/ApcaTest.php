<?php

declare(strict_types=1);

namespace Empaphy\Colorphul\Tests\utils;

use Empaphy\Colorphul\Contrast\Apca;
use matthieumastadenis\couleur\colors\Rgb;
use PHPUnit\Framework\TestCase;
use function Empaphy\Colorphul\utils\apca\cL;
use function Empaphy\Colorphul\utils\apca\lightness_contrast;
use function Empaphy\Colorphul\utils\apca\sc;
use function Empaphy\Colorphul\utils\apca\sY;
use const Empaphy\Colorphul\utils\apca\CLAMP;
use const Empaphy\Colorphul\utils\apca\CLIP;
use const Empaphy\Colorphul\utils\apca\OFFSET;
use const Empaphy\Colorphul\utils\apca\THRESHOLD;

class ApcaTest extends TestCase
{
    public function test_lightness_contrast(): void
    {
        $this->assertEquals(0, lightness_contrast(new Rgb(0, 0, 0), new Rgb(0, 0, 0)));
        $this->assertEquals(0, lightness_contrast(new Rgb(0, 0, 0), new Rgb(0, 0, 0)));
        $this->assertEquals(0, lightness_contrast(new Rgb(0, 0, 0), new Rgb(0, 0, 0)));
        $this->assertEquals(Apca::MAX_NORMAL, lightness_contrast(new Rgb(0, 0, 0), new Rgb(255, 255, 255)));
        $this->assertEquals(Apca::MAX_REVERSE, lightness_contrast(new Rgb(255, 255, 255), new Rgb(0, 0, 0)));
        $this->assertEquals(0, lightness_contrast(new Rgb(0, 0, 0), new Rgb(57, 57, 57)));
        $this->assertEquals(0, lightness_contrast(new Rgb(60, 60, 60), new Rgb(0, 0, 0)));
        $this->assertGreaterThan(0, lightness_contrast(new Rgb(0, 0, 0), new Rgb(58, 58, 58)));
        $this->assertLessThan(0, lightness_contrast(new Rgb(61, 61, 61), new Rgb(0, 0, 0)));
    }

    public function test_cL(): void
    {
        $this->assertEquals(0.0, cL(0.0));
        $this->assertEquals(0.0, cL(0.09));
        $this->assertEquals(0.0, cL(-0.09));
        $this->assertEquals((CLAMP - OFFSET) * 100.0, cL(CLAMP));
        $this->assertEquals((-CLAMP + OFFSET) * 100.0, cL(-CLAMP));
    }

    public function test_sc(): void
    {
        $this->assertEquals(0.0, sc(-1.0));
        $this->assertEquals(0.0 + THRESHOLD ** CLIP, sc(0.0));
        $this->assertEquals(0.001 + (THRESHOLD - 0.001) ** CLIP, sc(0.001));
        $this->assertEquals(0.021 + (THRESHOLD - 0.021) ** CLIP, sc(0.021));
        $this->assertEquals(THRESHOLD, sc(THRESHOLD));
    }

    public function test_sY(): void
    {
        $this->assertEquals(0.0, sY(0, 0, 0));
        $this->assertEquals(1.0000001, sY(255, 255, 255));
    }
}
