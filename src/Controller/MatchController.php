<?php declare(strict_types=1);

namespace App\Controller;

use App\Domino\Entity\MatchBoard;
use App\Domino\Entity\Player;
use App\Domino\Match;
use App\Factory\TileFactory;
use App\Factory\TileStockFactory;
use Symfony\Component\HttpFoundation\JsonResponse;

class MatchController
{
    /** @var TileStockFactory */
    private $stockFactory;

    /** @var TileFactory */
    private $tileFactory;

    public function __construct(TileStockFactory $stockFactory, TileFactory $tileFactory)
    {
        $this->stockFactory = $stockFactory;
        $this->tileFactory = $tileFactory;
    }

    public function __invoke(): JsonResponse
    {
        $boardStock = $this->stockFactory->create($this->tileFactory->create());

        $alice = new Player('Alice', $this->stockFactory->create($boardStock->popMany(7)));
        $bob = new Player('Bob', $this->stockFactory->create($boardStock->popMany(7)));

        $board = new MatchBoard([$alice, $bob], $boardStock, $this->stockFactory->create([]));

        $match = new Match($board);

        $events = $match->play();

        return new JsonResponse($events);
    }
}
