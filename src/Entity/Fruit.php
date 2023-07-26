<?php

namespace App\Entity;

class Fruit
{
    public function __construct(
        private int $x,
        private int $y,
    ) {
    }

    /**
     * Get the value of x
     */
    public function getX(): int
    {
        return $this->x;
    }

    /**
     * Get the value of y
     */
    public function getY(): int
    {
        return $this->y;
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
     * Set the value of y
     */
    public function setY(int $y): self
    {
        $this->y = $y;

        return $this;
    }
}
