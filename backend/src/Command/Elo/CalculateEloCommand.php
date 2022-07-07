<?php

namespace App\Command\Elo;

use App\Entity\Dogfight;
use App\Entity\Elo;
use App\Repository\CommandQueueRepository;
use App\Repository\EloRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CalculateEloCommand extends Command
{
    const COMMAND_NAME = CommandQueueRepository::CALCULATE_ELO;
    const WARNING = 'WARNING';
    const INFO = 'INFO';
    const ERROR = 'ERROR';
    const DB_ERROR = 'DB_ERROR';
    const DEBUG = 'DEBUG';
    const RECORD = 'RECORD';
    /** @var EntityManager */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct();
        $this->em = $em;
    }

    protected function configure()
    {
        $this
            ->setName(self::COMMAND_NAME)
            ->setDescription('Calculates ELO ranking for dogfight')
            ->addOption(
                'id', null,
                InputOption::VALUE_REQUIRED | InputOption::VALUE_REQUIRED,
                'Identifier of the Dogfight record',
                null
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $em = $this->em;
        $io = new SymfonyStyle($input, $output);

        $id = $input->getOption('id');

        /** @var Dogfight $dogfight */
        $dogfight = $this->em->getRepository(Dogfight::class)->findOneBy([
            'id' => $id,
        ]);

        if (empty($dogfight)) {
            $io->error("Dogfight {$id} not found. Exiting...");
            return Command::FAILURE;
        }
        if (!$dogfight->isValidEloDogfight()) {
            $io->warning("Dogfight {$id} is not a valid ELO dogfight. Exiting...");
            return Command::FAILURE;
        }

//        if ($dogfight->isEloCalculated()) {
//            $io->warning("Dogfight {$id} ELO is already calculated. Exiting...");
//            return Command::SUCCESS;
//        }

        $planeGeneralElo = $em->getRepository(Elo::class)->calculateElo($dogfight, EloRepository::ELO_TYPE_PLANE_GENERAL);
        $planeTourElo = $em->getRepository(Elo::class)->calculateElo($dogfight, EloRepository::ELO_TYPE_PLANE_TOUR);
        $sideGeneralElo = $em->getRepository(Elo::class)->calculateElo($dogfight);
        $sideTourElo = $em->getRepository(Elo::class)->calculateElo($dogfight, EloRepository::ELO_TYPE_SIDE_TOUR);

        $dogfight->setEloCalculated(true);
        if ($planeGeneralElo) {
            $io->success('Plane General elo calculated');
        } else {
            $io->error('General Elo calculation failed');
        }

        if ($planeTourElo) {
            $io->success('Plane tour elo calculated');
        } else {
            $io->error("Plane Elo calculation failed");
        }

        if ($sideGeneralElo) {
            $io->success('Side general elo calculated');
        } else {
            $io->error("Side general Elo calculation failed");
        }

        if ($sideTourElo) {
            $io->success('Side tour elo calculated');
        } else {
            $io->error("Side tour Elo calculation failed.");
        }

        return Command::SUCCESS;
    }
}
