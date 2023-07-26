<?php

namespace App\Entity;

use App\Enum\BlockOrientation;
use LogicException;

class SnakeBlock
{

    private string $position = 'H';

    public function __construct(private int $x, private int $y)
    {
    }



    /**
     * Get the value of x
     */
    public function getX(): int
    {
        return $this->x;
    }

    /**
     * Set the value of x
     */
    public function setX(int $x): self
    {
        $this->x = $x;

        return $this;
    }



    /**
     * Get the value of y
     */
    public function getY(): int
    {
        return $this->y;
    }

    /**
     * Set the value of y
     */
    public function setY(int $y): self
    {
        $this->y = $y;

        return $this;
    }


    /**
     * Get the value of position
     */
    public function getPosition(): string
    {
        return $this->position;
    }

    /**
     * Set the value of position
     */
    public function setPosition(string $position): self
    {
        if (!in_array(BlockOrientation::tryFrom($position), BlockOrientation::cases())) {
            throw new LogicException('Wrong block position');
        }
        
        $this->position = $position;

        return $this;
    }


}
