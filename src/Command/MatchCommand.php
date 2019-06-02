<?php declare(strict_types=1);

namespace App\Command;

use App\Domino\Entity\MatchBoard;
use App\Domino\Entity\Player;
use App\Domino\Match;
use App\Factory\TileFactory;
use App\Factory\TileStockFactory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MatchCommand extends Command
{
    /** @var TileStockFactory */
    private $stockFactory;

    /** @var TileFactory */
    private $tileFactory;

    public function __construct(TileStockFactory $stockFactory, TileFactory $tileFactory)
    {
        parent::__construct('dominoes:play');

        $this->stockFactory = $stockFactory;
        $this->tileFactory = $tileFactory;
    }

    protected function execute(InputInterface $input, OutputInterface $output): ?int
    {
        $boardStock = $this->stockFactory->create($this->tileFactory->create());
        $alice = new Player('Alice', $this->stockFactory->create($boardStock->popMany(7)));
        $bob = new Player('Bob', $this->stockFactory->create($boardStock->popMany(7)));
        $board = new MatchBoard([$alice, $bob], $boardStock, $this->stockFactory->create([]));
        $match = new Match($board);

        $events = $match->play();

        foreach ($events as $event) {
            $output->writeln((string) $event);
        }

        return null;
    }
}
