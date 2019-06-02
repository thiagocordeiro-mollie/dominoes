<?php declare(strict_types=1);

namespace App\Domino\Entity;

use App\Domino\Service\RemovableTileStock;

class Player
{
    /** @var string */
    private $name;

    /** @var RemovableTileStock */
    private $stock;

    public function __construct(string $name, RemovableTileStock $stock)
    {
        $this->name = $name;
        $this->stock = $stock;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getTile(?int $left, ?int $right): ?Tile
    {
        if (is_null($left) && is_null($right)) {
            return $this->stock->popNext();
        }

        return $this->stock->popByFace($left, $right);
    }

    public function addTile(Tile $tile): void
    {
        $this->stock->append($tile);
    }

    public function isWinner(): bool
    {
        return $this->stock->isEmpty();
    }
}
