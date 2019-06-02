<?php declare(strict_types=1);

namespace App\Domino\Entity;

use App\Domino\Service\AppendableTileStock;
use App\Domino\Service\RemovableTileStock;
use Exception;

class MatchBoard
{
    /** @var Player[] */
    private $players = [];

    /** @var RemovableTileStock */
    private $stock;

    /** @var RemovableTileStock */
    private $boardStack;

    public function __construct(array $players, RemovableTileStock $stock, AppendableTileStock $board)
    {
        $this->players = $players;
        $this->stock = $stock;
        $this->boardStack = $board;
    }

    public function getPayerInTurn(): Player
    {
        $player = array_shift($this->players);

        $this->players[] = $player;

        return $player;
    }

    public function getFirstTile(): ?Tile
    {
        return $this->boardStack->getFirst();
    }

    public function getLastTile(): ?Tile
    {
        return $this->boardStack->getLast();
    }

    public function giveTileToPlayer(Player $player): bool
    {
        if ($this->stock->isEmpty()) {
            return false;
        }

        $next = $this->stock->popNext();

        if (!$next) {
            return false;
        }

        $player->addTile($next);

        return true;
    }

    public function placeTile(Tile $tile): ?Tile
    {
        if ($this->boardStack->isEmpty()) {
            $this->boardStack->append($tile);
            return null;
        }

        $first = $this->getFirstTile();
        $last = $this->getLastTile();

        if ($tile->connectsOnRight($last->getRight())) {
            $this->boardStack->append($tile);
            return $last;
        }

        if (!$tile->connectsOnLeft($first->getLeft())) {
            throw new Exception('Unable to place the tile');
        }

        $this->boardStack->prepend($tile);

        return $first;
    }

    /**
     * @return Tile[]
     */
    public function getPlayedTiles(): array
    {
        return $this->boardStack->getAll();
    }
}
