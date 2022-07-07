<?php

namespace App\Command\Tours;

use App\Entity\CustomTourRequest;
use App\Entity\Tour;
use App\Repository\CustomTourRequestRepository;
use App\Repository\TourRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CheckTourRequestsCommand extends Command
{
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
            ->setName('app:check-tour-requests')
            ->setDescription('Checks preplanned tour requests');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $em = $this->getManager();

        /** @var TourRepository $seasonRepository */

        /** @var CustomTourRequestRepository $seasonsQueueRepository */
        $tourRequestSearch = $em->getRepository(CustomTourRequest::class)->getTourFromQueue();
        if (empty($tourRequestSearch)) {
            $io->comment('Preplanned tour not found. Exiting');
            return Command::SUCCESS;
        }

        /** @var CustomTourRequest $tourRequest */
        $tourRequest = reset($tourRequestSearch);
        $preplannedStart = strtotime($tourRequest->getStart()->format('Y-m-d H:i:s'));
        if (time() < $preplannedStart) {
            $io->success("Next tour will start at {$tourRequest->getStart()->format('Y-m-d H:i:s')}. Exiting");
            return Command::SUCCESS;
        }
        $start = date('Y-m-d H:i:s', strtotime($tourRequest->getStart()->format('Y-m-d H:i:s')));
        $currentSeason = $em->getRepository(Tour::class)->getCurrentTour();
        if (!empty($currentSeason)) {
            $currentSeason->setFinished(true);
            $currentSeason->setEnd(new \DateTime());
            try {
                $em->persist($currentSeason);
                $em->flush();
                $io->comment('Current tour data updated successfully');
            } catch (ORMException $e) {
                $io->error($e->getMessage());
            }
        }

        $io->note('Starting preplanned tour...');
        $newSeason = new Tour();
        $newSeason->setTitle($tourRequest->getTitle());
        $newSeason->setStart(new \DateTime($start));
        $newSeason->setTitleEn($tourRequest->getTitleEn());
        $newSeason->setFinished(false);
        $tourRequest->setStarted(true);

        try {
            $em->persist($newSeason);
            $em->persist($tourRequest);
            $em->flush();
            $io->note('Tour started successfully');
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $io->error('Error while starting new tour: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }

    /**
     * @return EntityManager
     */
    private function getManager()
    {
        return $this->em;
    }
}
