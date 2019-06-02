<?php declare(strict_types=1);

namespace App\Tests\Domino\Entity;

use App\Domino\Entity\MatchBoard;
use App\Domino\Entity\Player;
use App\Domino\Entity\Tile;
use App\Domino\Service\AppendableTileStock;
use App\Domino\Service\RemovableTileStock;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class MatchBoardTest extends TestCase
{
    /** @var RemovableTileStock|MockObject */
    private $boardStock;

    /** @var MatchBoard */
    private $matchBoard;

    protected function setUp(): void
    {
        $this->boardStock = $this->createMock(AppendableTileStock::class);

        $stock = $this->createMock(RemovableTileStock::class);
        $players = [
            new Player('A', $this->createMock(RemovableTileStock::class)),
            new Player('B', $this->createMock(RemovableTileStock::class)),
        ];

        $this->matchBoard = new MatchBoard($players, $stock, $this->boardStock);
    }

    /**
     * @dataProvider playerInTurnDataset
     */
    public function testGetPlayerInTurn(int $round, string $name): void
    {
        while (--$round > 0) {
            $this->matchBoard->getPayerInTurn();
        }

        $player = $this->matchBoard->getPayerInTurn();

        $this->assertEquals($name, $player->getName());
    }

    public function playerInTurnDataset(): array
    {
        return [
            'Round 1 => Player A' => ['round' => 1, 'name' => 'A'],
            'Round 2 => Player B' => ['round' => 2, 'name' => 'B'],
            'Round 3 => Player A' => ['round' => 3, 'name' => 'A'],
            'Round 4 => Player B' => ['round' => 4, 'name' => 'B'],
            'Round 5 => Player A' => ['round' => 5, 'name' => 'A'],
            'Round 6 => Player B' => ['round' => 6, 'name' => 'B'],
        ];
    }

    public function testGivenPlayedTilesThenReturnLeftFace(): void
    {
        $this->boardStock->method('getFirst')->willReturn(new Tile(1, 3));

        $face = $this->matchBoard->getLeftFace();

        $this->assertEquals(1, $face);
    }

    public function testGivenPlayedTilesThenReturnRightFace(): void
    {
        $this->boardStock->method('getLast')->willReturn(new Tile(2, 6));

        $face = $this->matchBoard->getRightFace();

        $this->assertEquals(6, $face);
    }

    public function testGivenATileAndBoardIsEmptyThenAppendOnBoard(): void
    {
        $this->boardStock->method('isEmpty')->willReturn(true);

        $this->boardStock->expects($this->once())->method('append');

        $this->matchBoard->place(new Tile(1, 2));
    }

    public function testGivenATileMatchedByLeftThenAppendOnStock(): void
    {
        $this->boardStock->method('getFirst')->willReturn(new Tile(1, 4));
        $this->boardStock->method('getLast')->willReturn(new Tile(5, 5));

        $this->boardStock->expects($this->once())->method('prepend');

        $this->matchBoard->place(new Tile(1, 2));
    }

    public function testGivenATileMatchedByRightThenAppendOnStock(): void
    {
        $this->boardStock->method('getFirst')->willReturn(new Tile(5, 5));
        $this->boardStock->method('getLast')->willReturn(new Tile(1, 4));

        $this->boardStock->expects($this->once())->method('append');

        $this->matchBoard->place(new Tile(2, 4));
    }

    public function testGivenATileMatchedByRightThenFlipAppendOnStock(): void
    {
        $this->boardStock->method('getFirst')->willReturn(new Tile(5, 5));
        $this->boardStock->method('getLast')->willReturn(new Tile(1, 4));

        $this->boardStock->expects($this->once())->method('append');

        $this->matchBoard->place(new Tile(4, 2));
    }
}
