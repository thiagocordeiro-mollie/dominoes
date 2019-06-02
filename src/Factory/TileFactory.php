<?php declare(strict_types=1);

namespace App\Factory;

use App\Domino\Entity\Tile;

class TileFactory
{
    /**
     * @return Tile[]
     */
    public function create(): array
    {
        $stack = range(0, 6);
        $tiles = [];

        while (count($stack) > 0) {
            $left = array_shift($stack);
            $tiles[] = new Tile($left, $left);

            foreach ($stack as $right) {
                $tiles[] = new Tile($left, $right);
            }
        }

        shuffle($tiles);

        return $tiles;
    }
}
