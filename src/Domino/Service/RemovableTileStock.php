<?php declare(strict_types=1);

namespace App\Domino\Service;

use App\Domino\Entity\Tile;

interface RemovableTileStock extends AppendableTileStock
{
    public function popByFace(int $left, int $right): ?Tile;

    public function popNext(): ?Tile;

    /**
     * @return Tile[]
     */
    public function popMany(int $amount): array;
}
