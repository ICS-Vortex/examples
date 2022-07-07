<?php

namespace App\Command\Statistics;

use App\Entity\JsonMessage;
use App\Entity\Server;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ClearStatisticsCommand extends Command
{
    protected static $defaultName = 'app:clear-statistics';
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        parent::__construct();
        $this->manager = $manager;
    }

    protected function configure()
    {
        $this
            ->setDescription('Clearing all statistics for all servers');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $servers = $this->manager->getRepository(Server::class)->findAll();
        $jsonMessagesTable = $this->manager->getClassMetadata(JsonMessage::class)->getTableName();
        $count = 0;
        foreach ($servers as $server) {
            $query = 'DELETE FROM ' . $jsonMessagesTable . ' WHERE server_id = ' . $server->getId();
            $this->manager->getConnection()->executeQuery($query);

            $exec = $this->manager->getRepository(Server::class)->clearStatistics($server);
            if ($exec) {
                $count++;
                $io->writeln('Stats cleared for server ' . $server);
            } else {
                $io->error('Failed to clear stats for server ' . $server);
            }
        }
        $io->success($count . ' servers are cleared.');

        return Command::SUCCESS;
    }
}
