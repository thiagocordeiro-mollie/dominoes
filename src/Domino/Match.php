<?php declare(strict_types=1);

namespace App\Domino;

use App\Domino\Entity\MatchBoard;
use App\Domino\Entity\Player;
use App\Domino\Entity\Tile;
use App\Domino\Value\Event;

class Match
{
    private const MSG_GAME_STARTED = 'Game starting with first tile: %s';
    private const MSG_GAME_ENDED = "\n\nGame ended without possible movements\n";
    private const MSG_PLAYER_PLACE_TILE = 'Alice plays %s to connect to tile %s on the board';
    private const MSG_PLAYER_WON = "\nPlayer %s has won!\n";
    private const MSG_BOARD_STATE = 'Board is now: %s';

    /** @var MatchBoard */
    private $matchBoard;

    /** @var null|Player */
    private $winner = null;

    private $events = [];

    public function __construct(MatchBoard $board)
    {
        $this->matchBoard = $board;
    }

    /**
     * @return Event[]
     */
    public function play(): array
    {
        while (is_null($this->winner)) {
            $player = $this->matchBoard->getPayerInTurn();
            $first = $this->matchBoard->getFirstTile();
            $last = $this->matchBoard->getLastTile();

            if (is_null($first) && is_null($last)) {
                $this->registerFirstPlacement($player);

                continue;
            }

            $tile = $this->getNextTile($player, $first->getLeft(), $last->getRight());

            if (!$tile) {
                $this->events[] = Event::error(static::MSG_GAME_ENDED);
                break;
            }

            $this->registerPlacement($tile);

            if ($player->isWinner()) {
                $this->registerWinner($player);
            }
        }

        return $this->events;
    }

    private function getBoardState(): string
    {
        return implode(' ', $this->matchBoard->getPlayedTiles());
    }

    private function getNextTile(Player $player, int $left, int $right): ?Tile
    {
        $tile = $player->getTile($left, $right);

        while (is_null($tile)) {
            $given = $this->matchBoard->giveTileToPlayer($player);
            if (!$given) {
                break;
            }

            $tile = $player->getTile($left, $right);
        }

        return $tile;
    }

    private function registerFirstPlacement(Player $player): void
    {
        $tile = $player->getTile(null, null);
        $this->matchBoard->placeTile($tile);
        $this->events[] = Event::message(sprintf(static::MSG_GAME_STARTED, $tile));
    }

    private function registerPlacement(?Tile $tile): void
    {
        $paired = $this->matchBoard->placeTile($tile);
        $this->events[] = Event::message(sprintf(static::MSG_PLAYER_PLACE_TILE, $tile, $paired));
        $this->events[] = Event::message(sprintf(static::MSG_BOARD_STATE, $this->getBoardState()));
    }

    private function registerWinner(Player $player): void
    {
        $this->winner = $player;
        $this->events[] = Event::success(sprintf(static::MSG_PLAYER_WON, $player->getName()));
    }
}
