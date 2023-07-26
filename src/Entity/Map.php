<?php

namespace App\Entity;

use Exception;
use RuntimeException;
use SebastianBergmann\Environment\Runtime;

class Map
{
    private int $score = 0;

    public function __construct(
        public readonly int $size = 10,
        private Snake $snake = new Snake(),
        private ?Fruit $fruit = null,
    ) {
        $this->fruit = $fruit ?? $this->getRandomFruit();
    }

    public function getRandomFruit(): Fruit
    {
        foreach ($this->getSnake()->getSnakeBlocks() as $block) {
            $blocks[$block->getX()][$block->getY()] = $block;
        }

        $fruit = new Fruit(rand(0, $this->size - 1), rand(0, $this->size - 1));
        while (isset($blocks[$fruit->getX()][$fruit->getY()])) {
            $fruit = new Fruit(rand(0, $this->size - 1), rand(0, $this->size - 1));
        }

        return $fruit;
    }

    public function turn()
    {
        if (!$this->gameOver()) {
            $direction = $this->getSnake()->getDirection();
            $this->move($direction);
            if ($this->isEating() === false) {
                $this->getSnake()->removeTail();
            }
            $this->getSnake()->setBlocksPositions();
        }
    }

    public function gameOver(): bool
    {
        foreach ($this->getSnake()->getBlocks() as $block) {
            if (
                $this->getSnake()->getHead()->getX() === $block->getX() &&
                $this->getSnake()->getHead()->getY() === $block->getY() &&
                $this->getSnake()->getHead() !== $block
            ) {
                return true;
            }
        }

        return false;
    }

    public function move(string $direction)
    {
        $this->getSnake()->checkPossibleDirection($direction);

        $x = $this->getSnake()->getHead()->getX() + Snake::DIRECTIONS[$direction][0];
        $y = $this->getSnake()->getHead()->getY() + Snake::DIRECTIONS[$direction][1];
        $x <= $this->size - 1 ?: $x = 0;
        $y >= 0 ?: $y = $this->size - 1;
        $x >= 0 ?: $x = $this->size - 1;;
        $y <= $this->size - 1 ?: $y = 0;
        $this->getSnake()->move($x, $y);

        $this->getSnake()->setDirection($direction);
    }

    public function isEating(): bool
    {
        if (
            $this->getSnake()->getHead()->getX() === $this->getFruit()->getX() &&
            $this->getSnake()->getHead()->getY() === $this->getFruit()->getY()
        ) {
            $this->setFruit($this->getRandomFruit());
            $this->score++;
            return true;
        }

        return false;
    }

    /**
     * Get the value of snake
     */
    public function getSnake(): Snake
    {
        return $this->snake;
    }

    /**
     * Set the value of snake
     */
    public function setSnake(Snake $snake): self
    {
        $this->snake = $snake;

        return $this;
    }

    /**
     * Set the value of fruit
     */
    public function setFruit(Fruit $fruit): self
    {
        $this->fruit = $fruit;

        return $this;
    }

    /**
     * Get the value of fruit
     */
    public function getFruit(): Fruit
    {
        return $this->fruit;
    }

    /**
     * Get the value of score
     */
    public function getScore(): int
    {
        return $this->score;
    }
}
