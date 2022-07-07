<?php

namespace App\Service;

use App\Entity\Slide;
use Doctrine\ORM\EntityManagerInterface;

class SlidesService
{
    private $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    public function getSlides()
    {
        return $this->em->getRepository(Slide::class)->findBy([], ['ord' => 'ASC']);
    }


}