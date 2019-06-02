<?php declare(strict_types=1);

namespace App\Tests\Domino\Entity;

use App\Domino\Entity\Player;
use App\Domino\Service\RemovableTileStock;
use PHPUnit\Framework\TestCase;

class PlayerTest extends TestCase
{
    public function testWhenGetPlayerTileWithoutBoardFacesThenReturnFirst(): void
    {
        $stock = $this->createMock(RemovableTileStock::class);
        $player = new Player('A', $stock);

        $stock->expects($this->once())->method('popNext');

        $player->getTile(null, null);
    }

    public function testWhenGetPlayerTileWithBoardFacesThenTryToFind(): void
    {
        $stock = $this->createMock(RemovableTileStock::class);
        $player = new Player('A', $stock);

        $stock->expects($this->once())->method('popByFace');

        $player->getTile(1, 2);
    }

    public function testShouldNoBeWinnerWhenTileStockIsNotEmpty(): void
    {
        $stock = $this->createMock(RemovableTileStock::class);
        $player = new Player('A', $stock);

        $stock->method('isEmpty')->willReturn(false);

        $this->assertFalse($player->isWinner());
    }

    public function testShouldBeWinnerWhenTileStockIsEmpty(): void
    {
        $stock = $this->createMock(RemovableTileStock::class);
        $player = new Player('A', $stock);

        $stock->method('isEmpty')->willReturn(true);

        $this->assertTrue($player->isWinner());
    }

    public function testShouldCountUserPoints(): void
    {
        $stock = $this->createMock(RemovableTileStock::class);
        $player = new Player('A', $stock);

        $stock->expects($this->once())->method('sumPoints');

        $player->getTilePoints();
    }
}
