<?php declare(strict_types=1);

namespace App\Domino;

use App\Domino\Entity\MatchBoard;
use App\Domino\Entity\Player;
use Exception;

class Match
{
    /** @var MatchBoard */
    private $matchBoard;

    /** @var null|Player */
    private $winner = null;

    public function __construct(MatchBoard $board)
    {
        $this->matchBoard = $board;
    }

    public function play(): string
    {
        $max = 0;
        while (is_null($this->winner)) {
            $player = $this->matchBoard->getPayerInTurn();
            $left = $this->matchBoard->getLeftFace();
            $right = $this->matchBoard->getRightFace();

            $tile = $player->getTile($left, $right);

            while (is_null($tile)) {
                $given = $this->matchBoard->giveTileToPlayer($player);
                if (!$given) {
                    break;
                }

                $tile = $player->getTile($left, $right);
            }

            if (!$tile) {
                continue;
            }

            $this->matchBoard->place($tile);

            if ($player->isWinner()) {
                $this->winner = $player;
            }
        }

        return '';
    }
}
