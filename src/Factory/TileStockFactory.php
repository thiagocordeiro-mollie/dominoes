<?php declare(strict_types=1);

namespace App\Factory;

use App\Domino\Entity\Tile;
use App\Domino\Service\RemovableTileStock;
use App\Repository\RuntimeStock;

class TileStockFactory
{
    /**
     * @param Tile[| $tiles
     */
    public function create(array $tiles): RemovableTileStock
    {
        $stock = new RuntimeStock();
        $stock->setTiles($tiles);

        return $stock;
    }
}
