<?php

namespace App\Service;

use App\Entity\Tour;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

class TourService
{
    /**
     * @var EntityManager
     */
    private $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    public function getCurrentSeason()
    {
        return $this->em->getRepository(Tour::class)->getCurrentSeason();
    }

    public function getSeasons()
    {
        return $this->em->getRepository(Tour::class)->findBy(array(), array('id' => 'DESC'));
    }
}