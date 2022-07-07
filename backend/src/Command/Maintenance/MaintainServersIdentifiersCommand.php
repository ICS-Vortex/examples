<?php

namespace App\Command\Maintenance;

use App\Entity\Server;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class MaintainServersIdentifiersCommand extends Command
{
    protected static $defaultName = 'app:maintain-servers-identifiers';
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct();

        $this->em = $em;
    }

    protected function configure() : void
    {
        $this
            ->setDescription('Checks all servers and adds IDs')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $servers = $this->em->getRepository(Server::class)->findAll();
        $io->title('Found '.count($servers). ' servers...');
        foreach ($servers as $server) {
            $io->note('Checking server '.$server->getName().' ...');
            if (empty($server->getIdentifier())) {
                $io->text('Server ID code is empty. Generating new identifier...');
                $identifier = $server->generateIdentifier();
                if (empty($identifier)) {
                    $io->error('Failed to generate server ID. Skipping...');
                    continue;
                }

                $server->setIdentifier($identifier);
                $this->em->persist($server);
                $this->em->flush();
                $io->text('Server ID updated. Continue...');
                continue;
            }
            $io->text('Server ID is not empty, skipping...');
        }
        $io->success('Done.');

        return 0;
    }
}
