<?php

namespace App\Tests;

use App\Entity\Fruit;
use App\Entity\Map;
use App\Entity\Snake;
use App\Entity\SnakeBlock;
use PHPUnit\Framework\TestCase;

class MapTest extends TestCase
{
    private Snake $snake;
    private Map $map;
    private Fruit $fruit;

    public function setUp(): void
    {
        $this->snake = new Snake();

        $this->fruit = new Fruit(8, 7);

        $this->map = new Map(10, $this->snake, $this->fruit);
    }

    public function testMapSize(): void
    {
        $this->assertSame(10, $this->map->size);
    }

    public function testTurn()
    {
        $this->assertSame(5, $this->snake->getHead()->getX());
        $this->map->turn();
        $this->assertSame(6, $this->snake->getHead()->getX());
    }

    public function testMoves()
    {
        $this->assertSame(5, $this->snake->getHead()->getX());
        $this->map->turn();
        $this->snake->setDirection(Snake::BOTTOM);
        $this->map->turn();
        $this->map->turn();

        $this->assertSame(6, $this->snake->getHead()->getX());
        $this->assertSame(7, $this->snake->getHead()->getY());
    }

    public function testOutMapRight()
    {
        $this->map->turn();
        $this->map->turn();
        $this->map->turn();
        $this->map->turn();
        $this->assertSame(9, $this->snake->getHead()->getX());
        $this->map->turn();
        $this->assertSame(0, $this->snake->getHead()->getX());
    }

    public function testOutMapLeft()
    {
        $this->snake->setDirection('T');
        $this->map->turn();
        $this->map->turn();
        $this->map->turn();
        $this->map->turn();
        $this->map->turn();
        $this->assertSame(0, $this->snake->getHead()->getY());
        $this->map->turn();
        $this->assertSame(9, $this->snake->getHead()->getY());
    }

    public function testOutMapBottom()
    {
        $this->snake->setDirection('B');
        $this->map->turn();
        $this->map->turn();
        $this->map->turn();
        $this->map->turn();
        $this->assertSame(9, $this->snake->getHead()->getY());
        $this->map->turn();
        $this->assertSame(0, $this->snake->getHead()->getY());
    }

    public function testFruitOnMap()
    {
        $this->assertSame(8, $this->map->getFruit()->getX());
        $this->assertSame(7, $this->map->getFruit()->getY());
    }

    public function testFruitNotOnSnake()
    {
        $snakeBlocks[] = new SnakeBlock(0,0);
        $snakeBlocks[] = new SnakeBlock(0,1);
        $snakeBlocks[] = new SnakeBlock(1,0);

        $snake = new Snake($snakeBlocks);
        $map = new Map(2, $snake);

        $map->getRandomFruit();
        $this->assertSame([1,1], [$map->getFruit()->getX(), $map->getFruit()->getY()]);
        $map->getRandomFruit();
        $this->assertSame([1,1], [$map->getFruit()->getX(), $map->getFruit()->getY()]);
        $map->getRandomFruit();
        $this->assertSame([1,1], [$map->getFruit()->getX(), $map->getFruit()->getY()]);
    }

    public function testSnakeEatFruit()
    {     
        $this->assertCount(1, $this->snake->getBlocks());

        $fruit = new Fruit(7, 5);
        $this->map->setFruit($fruit);
        $this->map->turn();
        $this->map->turn();

        $this->assertNotNull($this->map->getFruit());
        $this->assertCount(2, $this->map->getSnake()->getBlocks());
    }

    public function testGameOver()
    {
        $this->assertFalse($this->map->gameOver());
        $block = new SnakeBlock(4,5);
        $block2 = new SnakeBlock(3,5);
        $block3 = new SnakeBlock(2,5);
        $block4 = new SnakeBlock(1,5);
        $this->map->getSnake()->addBlock($block)->addBlock($block2)->addBlock($block3)->addBlock($block4);
        $this->assertCount(5, $this->snake->getBlocks());
        $this->map->turn();
        $this->map->getSnake()->setDirection('B');
        $this->map->turn();
        $this->map->getSnake()->setDirection('L');
        $this->map->turn();
        $this->map->getSnake()->setDirection('T');
        $this->map->turn();
        $this->assertTrue($this->map->gameOver());
    }

    public function testScore() 
    {
        $this->assertSame(0, $this->map->getScore());

        $fruit = new Fruit(6, 5);
        $this->map->setFruit($fruit);
        $this->map->turn();
        $this->assertSame(1, $this->map->getScore());
        $fruit = new Fruit(8, 5);
        $this->map->setFruit($fruit);
        $this->map->turn();
        $this->map->turn();
        $this->assertSame(2, $this->map->getScore());
    }
}
