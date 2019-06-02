<?php declare(strict_types=1);

namespace App\Tests\Domino\Entity;

use App\Domino\Entity\Tile;
use PHPUnit\Framework\TestCase;

class TileTest extends TestCase
{
    public function testShouldFlipFaces(): void
    {
        $tile = new Tile(3, 5);
        $tile->flip();

        $this->assertEquals(new Tile(5, 3), $tile);
    }

    /**
     * @dataProvider connectableDataset
     */
    public function testCanBeConnected(int $left, int $right, bool $expected): void
    {
        $tile = new Tile(1, 6);

        $result = $tile->canBeConnected($left, $right);

        $this->assertEquals($expected, $result);
    }

    public function connectableDataset()
    {
        return [
            'is connectable by left' => ['left' => 1, 'right' => 4, 'expected' => true],
            'is connectable by left flipped' => ['left' => 4, 'right' => 1, 'expected' => true],
            'is connectable by right' => ['left' => 2, 'right' => 6, 'expected' => true],
            'is connectable by right flipped' => ['left' => 6, 'right' => 2, 'expected' => true],
            'is not connectable' => ['left' => 3, 'right' => 4, 'expected' => false],
        ];
    }

    /**
     * @dataProvider countPointsDataset
     */
    public function testCountPoints(Tile $tile, int $expected): void
    {
        $this->assertEquals($expected, $tile->points());
    }

    public function countPointsDataset(): array
    {
        return [
            '0 points' => ['tile' => new Tile(0, 0), 'points' => 0],
            '1 point' => ['tile' => new Tile(1, 0), 'points' => 1],
            '2 points' => ['tile' => new Tile(1, 1), 'points' => 2],
            '4 points' => ['tile' => new Tile(1, 3), 'points' => 4],
            '8 points' => ['tile' => new Tile(6, 2), 'points' => 8],
        ];
    }
}
