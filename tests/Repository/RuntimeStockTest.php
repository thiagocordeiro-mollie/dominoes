<?php declare(strict_types=1);

namespace App\Tests\Repository;

use App\Domino\Entity\Tile;
use App\Repository\RuntimeStock;
use DateTime;
use PHPUnit\Framework\TestCase;
use TypeError;

class RuntimeStockTest extends TestCase
{
    public function testGivenNonTileObjectThenThrowTypeError(): void
    {
        $stock = new RuntimeStock();

        $this->expectException(TypeError::class);

        $stock->setTiles([new DateTime()]);
    }

    public function testGivenTilesGetLastFromStack(): void
    {
        $stock = new RuntimeStock();
        $stock->setTiles([new Tile(1, 2), new Tile(3, 4), new Tile(5, 6)]);

        $next = $stock->popNext();

        $this->assertEquals(new Tile(5, 6), $next);
    }

    public function testWhenGettingNextThenRemoveItemFromStack(): void
    {
        $stock = new RuntimeStock();
        $stock->setTiles([new Tile(1, 2), new Tile(3, 4), new Tile(5, 6)]);

        $stock->popNext();

        $this->assertCount(2, $stock->getAll());
    }

    public function testGivenBoardFacesThenGetFromStackWhenItHasMatchingFace(): void
    {
        $stock = new RuntimeStock();
        $stock->setTiles([new Tile(1, 5), new Tile(3, 3), new Tile(5, 6)]);

        $tile = $stock->popByFace(1, 2);

        $this->assertEquals(new Tile(5, 1), $tile);
    }

    public function testGivenBoardFacesThenGetNullFromStackWhenItHasNoMatchingFace(): void
    {
        $stock = new RuntimeStock();
        $stock->setTiles([new Tile(1, 5), new Tile(3, 3), new Tile(5, 6)]);

        $tile = $stock->popByFace(2, 2);

        $this->assertNull($tile);
    }

    public function testGetRandom(): void
    {
        $stock = new RuntimeStock();
        $stock->setTiles([new Tile(1, 5), new Tile(3, 3), new Tile(5, 6)]);

        $tiles = $stock->popMany(2);

        $this->assertCount(2, $tiles);
    }

    public function testGivenTilesThenGetFirst(): void
    {
        $stock = new RuntimeStock();
        $stock->setTiles([new Tile(1, 2), new Tile(2, 6), new Tile(6, 6), new Tile(6, 3), new Tile(3, 0)]);

        $first = $stock->getFirst();

        $this->assertEquals(new Tile(1, 2), $first);
    }

    public function testGivenTilesThenGetLast(): void
    {
        $stock = new RuntimeStock();
        $stock->setTiles([new Tile(1, 2), new Tile(2, 6), new Tile(6, 6), new Tile(6, 3), new Tile(3, 0)]);

        $last = $stock->getLast();

        $this->assertEquals(new Tile(3, 0), $last);
    }

    public function testGivenOnlyOneTileThenFirstAndLastMustBeTheSame(): void
    {
        $stock = new RuntimeStock();
        $stock->setTiles([new Tile(1, 2)]);

        $first = $stock->getFirst();
        $last = $stock->getLast();

        $this->assertEquals($first, $last);
    }

    public function testWhenTileIsFoundOnStackByBoardFaceThenRemoveFromStack(): void
    {
        $stock = new RuntimeStock();
        $stock->setTiles([new Tile(1, 2), new Tile(1, 3), new Tile(2, 3), new Tile(3, 3), new Tile(6, 5)]);

        $stock->popByFace(6, 4);

        $this->assertCount(4, $stock->getAll());
    }
}
