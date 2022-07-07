<?php

namespace App\Command\Tours;

use App\Entity\Tour;
use DateTime;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMException;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class StartTourCommand extends Command
{
    /**
     * @var EntityManager
     */
    private $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
        parent::__construct();
    }

    protected function configure() : void
    {
        $this
            ->setName('app:start-tour')
            ->setDescription('Starts new game tour')
        ;
    }

    /**
     * @return EntityManager
     */
    private function getManager() : EntityManager
    {
        return $this->em;
    }

    protected function execute(InputInterface $input, OutputInterface $output) : int
    {
        $io = new SymfonyStyle($input, $output);
        $em = $this->getManager();
        $start = date('Y-m-01 00:00:00');
        $end = date('Y-m-t 23:59:59');
        $prevNotFinishedTours = $em->getRepository(Tour::class)->findBy(['finished' => false]);
        foreach ($prevNotFinishedTours as $tour) {
            $tour->setFinished(true);
            try {
                $em->persist($tour);
                $em->flush();
            } catch (ORMException $e) {
                $io->error($e->getMessage());
                return Command::FAILURE;
            }
        }

        $io->success('All previous tours are closed successfully');

        try {
            $tour = $em->getRepository(Tour::class)->findOneBy(array(
                'start' => new DateTime($start),
                'end' => new DateTime($end),
            ));

            if (empty($tour)) {
                $tour = new Tour();
                $tour->setTitle(date('F, Y', strtotime($start)));
                $tour->setTitleEn(date('F, Y', strtotime($start)));
                $tour->setStart(new DateTime($start));
                $tour->setEnd(new DateTime($end));
                $tour->setFinished(false);
                $em->persist($tour);
            } else {
                $tour->setTitle(date('F, Y', strtotime($start)));
                $tour->setTitleEn(date('F, Y', strtotime($start)));
                $tour->setStart(new DateTime($start));
                $tour->setFinished(false);
                $tour->setEnd(new DateTime($end));
                $em->persist($tour);
            }
            $em->flush();
            $io->success('Tour started successfully.');
            return Command::SUCCESS;
        }catch (Exception $e){
            $io->error('Error while starting tour: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
