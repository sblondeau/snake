<?php

namespace App\Entity;

use App\Entity\SnakeBlock;
use App\Enum\BlockOrientation;
use Exception;
use LogicException;

class Snake
{
    public const RIGHT = 'R';
    public const LEFT = 'L';
    public const TOP = 'T';
    public const BOTTOM = 'B';

    public const DIRECTIONS = [
        self::RIGHT => [1, 0],
        self::LEFT => [-1, 0],
        self::TOP => [0, -1],
        self::BOTTOM => [0, 1],
    ];

    public const INVERSE_DIRECTIONS = [
        self::RIGHT => self::LEFT,
        self::LEFT => self::RIGHT,
        self::TOP => self::BOTTOM,
        self::BOTTOM => self::TOP,
    ];

    public const VERTICAL = 'V';
    public const HORIZONTAL = 'H';

    public function __construct(
        private array $snakeBlocks = [],
        private string $direction = self::RIGHT
    ) {
        $this->snakeBlocks[] = new SnakeBlock(5, 5);
    }

    public function getHead(): SnakeBlock
    {
        return $this->snakeBlocks[0];
    }

    private function setHead(SnakeBlock $snakeBlock): void
    {
        array_unshift($this->snakeBlocks, $snakeBlock);
    }

    public function removeTail(): void
    {
        array_pop($this->snakeBlocks);
    }

    public function getBlocks(): array
    {
        return $this->snakeBlocks;
    }

    public function addBlock(SnakeBlock $snakeBlock): self
    {
        $this->isExistingBlock($snakeBlock);
        $this->snakeBlocks[] = $snakeBlock;

        return $this;
    }

    private function isExistingBlock($snakeBlock): void
    {
        $existingBlock = array_filter(
            $this->snakeBlocks,
            fn ($block) =>
            $block->getX() === $snakeBlock->getX() && $block->getY() === $snakeBlock->getY()
        );

        if (!empty($existingBlock)) {
            throw new LogicException(
                'Block already exists at position ' .
                    $snakeBlock->getX() . ', ' . $snakeBlock->getY()
            );
        }
    }

    public function move(int $x, int $y)
    {
        $newHead = new SnakeBlock($x, $y);

        $this->setHead($newHead);
    }

    public function setBlocksPositions()
    {
        for ($i = 1; $i < count($this->getSnakeBlocks()) - 1; $i++) {
            $currentBlock = $this->getSnakeBlocks()[$i];
 
            $currentBlock->setPosition(BlockOrientation::orientation($this->currentBlockPosition($i)));
        }
    }

    public function blockPosition($current, $target): string
    {
        $horizontal = $this->getSnakeBlocks()[$current]->getX() <=> $this->getSnakeBlocks()[$current + $target]->getX();
        $vertical = $this->getSnakeBlocks()[$current]->getY() <=> $this->getSnakeBlocks()[$current + $target]->getY();
        $horizontalDelta = $this->getSnakeBlocks()[$current]->getX() - $this->getSnakeBlocks()[$current + $target]->getX();
        $verticalDelta =  $this->getSnakeBlocks()[$current]->getY() - $this->getSnakeBlocks()[$current + $target]->getY();
        
        if (abs($horizontal) > abs($vertical)) {
            $direction = $horizontal > 0 && abs($horizontalDelta) === 1  || $horizontal < 0 && abs($horizontalDelta) > 1 ? self::LEFT : self::RIGHT;
        } elseif (abs($horizontal) < abs($vertical)) {
            $direction = $vertical > 0 && abs($verticalDelta) === 1 || $vertical< 0 && abs($verticalDelta) > 1 ? self::TOP : self::BOTTOM;
        }

        return $direction;
    }

    private function previousBlockPosition($pos): string
    {
        return $this->blockPosition($pos, -1);
    }

    private function nextBlockPosition($pos): string
    {
        return self::INVERSE_DIRECTIONS[$this->blockPosition($pos, 1)];
    }

    private function currentBlockPosition($i): BlockOrientation
    {
        $position = $this->nextBlockPosition($i) . $this->previousBlockPosition($i);

        return BlockOrientation::tryFrom($position);
    }

    public function checkPossibleDirection(string $direction)
    {
        if ($this->getDirection() === self::INVERSE_DIRECTIONS[$direction]) {
            throw new Exception('wrong direction');
        }
    }

    /**
     * Get the value of snakeBlocks
     */
    public function getSnakeBlocks(): array
    {
        return $this->snakeBlocks;
    }

    /**
     * Set the value of snakeBlocks
     */
    public function setSnakeBlocks(array $snakeBlocks): self
    {
        $this->snakeBlocks = $snakeBlocks;

        return $this;
    }

    /**
     * Get the value of direction
     */
    public function getDirection(): string
    {
        return $this->direction;
    }

    /**
     * Set the value of direction
     */
    public function setDirection(string $direction): self
    {
        $this->direction = $direction;

        return $this;
    }

    public function changeDirection(string $direction): self
    {
        $this->checkPossibleDirection($direction);
        $this->setDirection($direction);

        return $this;
    }

    public function getTail(): SnakeBlock
    {
        $blocks = $this->getSnakeBlocks();
        end($blocks);
        return current($blocks);
    }

    public function setTail(SnakeBlock $block): self
    {
        $this->snakeBlocks[] = $block;

        return $this;
    }
}
