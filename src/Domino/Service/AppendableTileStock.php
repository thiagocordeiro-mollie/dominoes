<?php declare(strict_types=1);

namespace App\Domino\Service;

use App\Domino\Entity\Tile;

interface AppendableTileStock
{
    public function append(Tile $tile): void;

    public function prepend(Tile $tile): void;

    public function getFirst(): ?Tile;

    public function getLast(): ?Tile;

    /**
     * @return Tile[]
     */
    public function getAll(): array;

    public function isEmpty(): bool;
}
