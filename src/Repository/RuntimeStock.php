<?php declare(strict_types=1);

namespace App\Repository;

use App\Domino\Entity\Tile;
use App\Domino\Service\RemovableTileStock;

class RuntimeStock implements RemovableTileStock
{
    /** @var Tile[] */
    private $tiles = [];

    public function setTiles(array $tiles): void
    {
        foreach ($tiles as $tile) {
            $this->append($tile);
        }
    }

    public function append(Tile $tile): void
    {
        array_push($this->tiles, $tile);
    }

    public function prepend(Tile $tile): void
    {
        array_unshift($this->tiles, $tile);
    }

    public function getFirst(): ?Tile
    {
        return $this->tiles[0] ?? null;
    }

    public function getLast(): ?Tile
    {
        $lastIndex = count($this->tiles) - 1;

        return $this->tiles[$lastIndex] ?? null;
    }

    /**
     * @inheritDoc
     */
    public function getAll(): array
    {
        return $this->tiles;
    }

    public function popByFace(int $left, int $right): ?Tile
    {
        $found = null;

        foreach ($this->tiles as $key => $tile) {
            if ($tile->canBeConnected($left, $right)) {
                $found = $tile;
                unset($this->tiles[$key]);
                break;
            }
        }

        return $found;
    }

    public function popNext(): ?Tile
    {
        $tile = array_pop($this->tiles);

        if (!is_a($tile, Tile::class)) {
            return null;
        }

        return $tile;
    }

    /**
     * @inheritDoc
     */
    public function popMany(int $amount): array
    {
        $tiles = [];

        while ($amount-- > 0) {
            $tiles[] = $this->popNext();
        }

        return $tiles;
    }

    public function isEmpty(): bool
    {
        return empty($this->tiles);
    }

    public function sumPoints(): int
    {
        $points = 0;

        foreach ($this->tiles as $tile) {
            $points += $tile->points();
        }

        return $points;
    }
}
