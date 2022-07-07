<?php

namespace App\Command\Maintenance;

use App\Entity\Pilot;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Uid\Uuid;

class FixPilotsCommand extends Command
{
    protected static $defaultName = 'app:maintenance-fix-pilots';
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
            ->setDescription('Fixes pilots without UCID')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $pilots = $this->manager->getRepository(Pilot::class)->findAll();

        $io->comment('Found '. count($pilots) . ' pilots in database');
        try {
            $fixed = 0;
            foreach ($pilots as $pilot) {
                $uuid = Uuid::v4();
                if (empty($pilot->getUcid())) {
                    $io->caution('Fixing user '.$pilot->getUsername());
                    $pilot->setUcid($uuid->toRfc4122());
                    $this->manager->persist($pilot);
                    $fixed++;
                }
            }
            $this->manager->flush();
            $io->success('Fixed '.$fixed. ' pilots');
            return Command::SUCCESS;
        }catch (\Exception $e) {
            $io->error($e->getMessage());
            $io->info($e->getTrace());
            return Command::FAILURE;
        }
    }
}
