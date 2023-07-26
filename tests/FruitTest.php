<?php

namespace App\Tests;

use App\Entity\Map;
use App\Entity\Snake;
use App\Entity\Fruit;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

class FruitTest extends TestCase
{

    /** 
     * @dataProvider fruitPositions()
     */
    public function testPosition(int $x, int $y)
    {
        $fruit = new Fruit($x, $y);
        $this->assertSame([$x, $y], [$fruit->getX(), $fruit->getY()]);
    }

    public function fruitPositions()
    {
        yield [2, 2];
        yield [2, 8];
        yield [0, 0];
        yield [5, 9];
        yield [9, 9];
    }

}
