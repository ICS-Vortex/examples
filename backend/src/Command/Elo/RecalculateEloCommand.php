<?php

namespace App\Command\Elo;

use App\Entity\Dogfight;
use App\Entity\Elo;
use App\Entity\Log;
use App\Repository\CommandQueueRepository;
use App\Repository\LogRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RecalculateEloCommand extends Command
{
    const COMMAND_NAME = CommandQueueRepository::RECALCULATE_ELO;
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
            ->setDescription('Calculates ELO ranking for dogfight');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->em;
        /** @var Dogfight $dogfight */
        $dogfights = $this->em->getRepository(Dogfight::class)->findBy(['elo' => false], ['id' => 'ASC'], 10000);

        try {
            /** @var Dogfight $dogfight */
            foreach ($dogfights as $dogfight) {
                if (empty($dogfight->getTour())) {
                    $dogfight->setElo(true);
                    $em->persist($dogfight);
                    continue;
                }
                if ($dogfight->getFriendly()) {
                    $dogfight->setElo(true);
                    $em->persist($dogfight);
                    continue;
                }

                $totalCalculation = $em->getRepository(Elo::class)->calculateTotalElo($dogfight);
                $tourCalculation = $em->getRepository(Elo::class)->calculateTourElo($dogfight);
                if ($totalCalculation && $tourCalculation) {
                    $output->writeln("<info>Elo calculated for dogfight {$dogfight->getId()}.</info>");
                    $dogfight->setElo(true);
                    $em->persist($dogfight);
                } else {
                    $output->writeln("<error>Elo calculation failed for dogfight {$dogfight->getId()}...</error>");
                }
            }
            $em->flush();
            return;
        } catch (OptimisticLockException $e) {
            $output->writeln("<error>Elo calculation failed with message - {$e->getMessage()}. Exiting...</error>");
        } catch (ORMException $e) {
            $output->writeln("<error>Elo calculation failed with error - {$e->getMessage()}. Exiting...</error>");
        }
    }


    /**
     * @param $message
     * @param string $type
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    private function log($message, $type = LogRepository::TYPE_OK)
    {
        $em = $this->em;

        $log = new Log();
        $log->setType($type);
        $log->setMessage($message);

        $em->persist($log);
        $em->flush();
    }
}
