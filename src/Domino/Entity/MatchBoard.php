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
    private $board;

    public function __construct(array $players, RemovableTileStock $stock, AppendableTileStock $board)
    {
        $this->players = $players;
        $this->stock = $stock;
        $this->board = $board;
    }

    public function getPayerInTurn(): Player
    {
        $player = array_shift($this->players);

        $this->players[] = $player;

        return $player;
    }

    public function getFirst(): ?Tile
    {
        return $this->board->getFirst();
    }

    public function getLast(): ?Tile
    {
        return $this->board->getLast();
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

    public function place(Tile $tile): ?Tile
    {
        if ($this->board->isEmpty()) {
            $this->board->append($tile);
            return null;
        }

        $first = $this->getFirst();
        $last = $this->getLast();

        if ($tile->connectsOnRight($last->getRight())) {
            $this->board->append($tile);
            return $last;
        }

        if (!$tile->connectsOnLeft($first->getLeft())) {
            throw new Exception('Unable to place the tile');
        }

        $this->board->prepend($tile);

        return $first;
    }

    /**
     * @return Tile[]
     */
    public function getPlayed(): array
    {
        return $this->board->getAll();
    }
}
