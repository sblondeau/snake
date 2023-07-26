<?php

namespace App\Tests;

use App\Entity\Map;
use App\Entity\Snake;
use App\Entity\SnakeBlock;
use Exception;
use LogicException;
use PHPUnit\Framework\TestCase;

class SnakeTest extends TestCase
{
    private Snake $snake;
    private Map $map;

    public function setUp(): void
    {
        $this->snake = new Snake();
        $this->map = new Map(10, $this->snake);
    }

    public function testInitPosition(): void
    {
        $this->assertSame(5, $this->snake->getHead()->getX());
        $this->assertSame(5, $this->snake->getHead()->getY());
    }

    public function testSnakeInitBlocks(): void
    {
        $snake = new Snake();
        $this->assertCount(1, $this->snake->getBlocks());
    }

    public function testSnakeBlocks(): void
    {
        $snakeBlock = new SnakeBlock(x: 4, y: 5);
        $this->snake->addBlock($snakeBlock);
        $this->assertCount(2, $this->snake->getBlocks());
    }

    public function testTryAddSnakeBlocksExistingBlock(): void
    {
        $this->expectException(LogicException::class);
        $snakeBlock = new SnakeBlock(x: 4, y: 5);
        $this->snake->addBlock($snakeBlock);
        $this->snake->addBlock($snakeBlock);
    }

    public function testMoveSnakeHeadRight()
    {
        $this->map->move(Snake::RIGHT);
        $this->assertSame(6, $this->snake->getHead()->getX());        
        $this->map->move(Snake::RIGHT);
        $this->assertSame(7, $this->snake->getHead()->getX());
    }

    public function testMoveSnakeBlocksRight()
    {
        $snakeBlock = new SnakeBlock(x: 4, y: 5);
        $this->snake->addBlock($snakeBlock);

        $this->map->move(Snake::RIGHT);
        $this->assertSame(6, $this->snake->getBlocks()[0]->getX());        
        $this->assertSame(5, $this->snake->getBlocks()[1]->getX());        
    }

    public function testGetDirection()
    {
        $this->assertSame(Snake::RIGHT, $this->snake->getDirection());        
    }

    public function testSetDirection()
    {
        $this->snake->setDirection(Snake::LEFT);
        $this->assertSame(Snake::LEFT, $this->snake->getDirection());        
        $this->snake->setDirection(Snake::TOP);
        $this->assertSame(Snake::TOP, $this->snake->getDirection());        
        $this->snake->setDirection(Snake::RIGHT);
        $this->assertSame(Snake::RIGHT, $this->snake->getDirection());        
        $this->snake->setDirection(Snake::BOTTOM);
        $this->assertSame(Snake::BOTTOM, $this->snake->getDirection());        
    }

    public function testSetSnakeDirectionLeft()
    {
        $this->expectException(Exception::class);
        $this->map->move(Snake::LEFT);
    } 

    /** 
     * @dataProvider inverseDirections()
     */
    public function testSetSnakeDirections($direction, $inverseDirection)
    {
        $this->expectException(Exception::class);
        $this->snake->setDirection($direction);

        $this->map->move($inverseDirection);
    } 
    
    public function inverseDirections()
    {
        yield ['R', 'L'];
        yield ['L', 'R'];
        yield ['B', 'T'];
        yield ['T', 'B'];
    }

    public function testMoveSnakeLeft()
    {
        $snakeBlock = new SnakeBlock(x: 6, y: 5);
        $this->snake->addBlock($snakeBlock);
        $this->snake->setDirection(Snake::LEFT);

        $this->map->move(Snake::LEFT);

        $this->assertSame(4, $this->snake->getBlocks()[0]->getX());        
        $this->assertSame(5, $this->snake->getBlocks()[1]->getX());             
    }    

    public function testMoveSnakeLeftTop()
    {
        $this->snake->setDirection(Snake::TOP);

        $this->map->move(Snake::TOP);

        $this->assertSame(5, $this->snake->getBlocks()[0]->getX());        
        $this->assertSame(4, $this->snake->getBlocks()[0]->getY());        
    }
    public function testMoveSnakeLeftBottom()
    {
        $this->snake->setDirection(Snake::BOTTOM);

        $this->map->move(Snake::BOTTOM);

        $this->assertSame(5, $this->snake->getBlocks()[0]->getX());        
        $this->assertSame(6, $this->snake->getBlocks()[0]->getY());        
    }

    public function testMoveSnakeBlockTop()
    {
        $snakeBlock = new SnakeBlock(x: 6, y: 5);
        $this->snake->addBlock($snakeBlock);

        $this->map->move(Snake::TOP);

        $this->assertSame(5, $this->snake->getBlocks()[0]->getX());        
        $this->assertSame(4, $this->snake->getBlocks()[0]->getY());  
        $this->assertSame(5, $this->snake->getBlocks()[1]->getX());        
        $this->assertSame(5, $this->snake->getBlocks()[1]->getY());  
    }

    public function testGetTail()
    {
        $snakeBlock = new SnakeBlock(x: 4, y: 5);
        $this->snake->addBlock($snakeBlock);
        $this->map->turn();
        $tail = $this->map->getSnake()->getTail();
        $this->assertSame(5, $tail->getX());
        $this->assertSame(5, $tail->getY());
    }
}
