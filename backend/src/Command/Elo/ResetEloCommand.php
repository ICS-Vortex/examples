<?php

namespace App\Command\Elo;

use App\Entity\Elo;
use App\Entity\Log;
use App\Repository\CommandQueueRepository;
use App\Repository\EloRepository;
use App\Repository\LogRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ResetEloCommand extends Command
{
    const COMMAND_NAME = CommandQueueRepository::RESET_ELO;
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
        $output->writeln("<info>Resetting Elo...</info>");
        /** @var EloRepository $eloRepo */
        $eloRepo = $em->getRepository(Elo::class);
        if ($eloRepo->resetElo()) {
            $output->writeln("<info>Elo cleared.</info>");
        } else {
            $output->writeln("<error>Failed to reset Elo...</error>");
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
