<?php

namespace App\Tests;

use App\Entity\Map;
use App\Entity\Snake;
use App\Entity\SnakeBlock;
use PHPUnit\Framework\TestCase;

class BlockSnakeTest extends TestCase
{
    private Snake $snake;
    private Map $map;

    public function setUp(): void
    {
        $this->snake = new Snake();
        $block = new SnakeBlock(4, 5);
        $block2 = new SnakeBlock(3, 5);
        $this->snake->addBlock($block)->addBlock($block2);

        $this->map = new Map(10, $this->snake);
    }

    public function testHorizontal(): void
    {
        $blocks = $this->snake->getSnakeBlocks();

        $this->assertSame('H', $blocks[1]->getPosition());
    }

    public function testTopRight(): void
    {
        $this->map->getSnake()->setDirection('T');
        $this->map->turn();
        $blocks = $this->map->getSnake()->getSnakeBlocks();

        $this->assertSame('RT', $blocks[1]->getPosition());
    }

    public function testBlockPositions(): void
    {
        $this->assertSame('H', $this->map->getSnake()->getSnakeBlocks()[1]->getPosition());

        $this->map->getSnake()->setDirection('T');
        $this->map->turn();
        $this->assertSame('RT', $this->map->getSnake()->getSnakeBlocks()[1]->getPosition());
        $this->map->turn();
        $this->assertSame('V', $this->map->getSnake()->getSnakeBlocks()[1]->getPosition());
        $this->map->turn();
        $this->assertSame('V', $this->map->getSnake()->getSnakeBlocks()[1]->getPosition());
        $this->map->getSnake()->setDirection('L');
        $this->map->turn();
        $this->assertSame('TL', $this->map->getSnake()->getSnakeBlocks()[1]->getPosition());
        $this->map->turn();
        $this->assertSame('H', $this->map->getSnake()->getSnakeBlocks()[1]->getPosition());
        $this->map->turn();
        $this->assertSame('H', $this->map->getSnake()->getSnakeBlocks()[1]->getPosition());
        $this->map->getSnake()->setDirection('B');
        $this->map->turn();
        $this->assertSame('LB', $this->map->getSnake()->getSnakeBlocks()[1]->getPosition());
        $this->map->getSnake()->setDirection('R');
        $this->map->turn();
        $this->assertSame('BR', $this->map->getSnake()->getSnakeBlocks()[1]->getPosition());
    }

    public function testPreviousPosition()
    {  
        $this->assertSame('R', $this->map->getSnake()->blockPosition(1, -1));
    }

    public function testBlockPositionsChangeMapSize(): void
    {
        $this->map->turn();
        $this->map->turn();
        $this->map->turn();
        $this->map->turn();
        $this->map->turn();
        $this->assertSame('H', $this->map->getSnake()->getSnakeBlocks()[1]->getPosition());
        $this->assertSame(0, $this->map->getSnake()->getHead()->getX());
        $this->map->getSnake()->setDirection('T');
        $this->map->turn();
        $this->assertSame('RT', $this->map->getSnake()->getSnakeBlocks()[1]->getPosition());
    }
    public function testBlockPositionsChangeMapSizeBottom(): void
    {
        $this->map->getSnake()->setDirection('T');
        $this->map->turn();
        $this->map->turn();
        $this->map->turn();
        $this->map->turn();
        $this->map->turn();
        $this->map->turn();
        $this->assertSame('V', $this->map->getSnake()->getSnakeBlocks()[1]->getPosition());
        $this->assertSame(0, $this->map->getSnake()->getSnakeBlocks()[1]->getY());
        $this->assertSame(9, $this->map->getSnake()->getHead()->getY());
        $this->map->getSnake()->setDirection('L');
        $this->map->turn();
        $this->assertSame('TL', $this->map->getSnake()->getSnakeBlocks()[1]->getPosition());
    }

}
