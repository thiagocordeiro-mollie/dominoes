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

    public function getLeftFace(): ?int
    {
        $first = $this->board->getFirst();

        if (!$first) {
            return null;
        }

        return $first->getLeft();
    }

    public function getRightFace(): ?int
    {
        $last = $this->board->getLast();

        if (!$last) {
            return null;
        }

        return $last->getRight();
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

    public function place(Tile $tile): void
    {
        if ($this->board->isEmpty()) {
            $this->board->append($tile);
            return;
        }

        $left = $this->getLeftFace();
        $right = $this->getRightFace();

        if ($tile->connectsOnRight($right)) {
            $this->board->append($tile);
            return;
        }

        if (!$tile->connectsOnLeft($left)) {
            throw new Exception('Unable to place the tile');
        }

        $this->board->prepend($tile);
    }

    public function getBoardStack():array {
        return $this->board->getAll();
    }
}
