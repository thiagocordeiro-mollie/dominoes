<?php declare(strict_types=1);

namespace App\Domino\Entity;

class Tile
{
    /** @var int */
    private $left;

    /** @var int */
    private $right;

    public function __construct(int $left, int $right)
    {
        $this->left = $left;
        $this->right = $right;
    }

    public function getLeft(): int
    {
        return $this->left;
    }

    public function getRight(): int
    {
        return $this->right;
    }

    public function flip(): void
    {
        $left = $this->left;
        $this->left = $this->right;
        $this->right = $left;
    }

    public function canBeConnected(int $left, int $right): bool
    {
        if ($this->connectsOnRight($right)) {
            return true;
        }

        if ($this->connectsOnLeft($left)) {
            return true;
        }

        return false;
    }

    public function connectsOnRight(int $right): bool
    {
        return $this->connectable($right, $this->left, $this->right);
    }

    public function connectsOnLeft(int $left): bool
    {
        return $this->connectable($left, $this->right, $this->left);
    }

    private function connectable(int $boardSide, int $currentSide, int $flippedSide): bool
    {
        if ($boardSide === $currentSide) {
            return true;
        }

        if ($boardSide === $flippedSide) {
            $this->flip();

            return true;
        }

        return false;
    }
}
