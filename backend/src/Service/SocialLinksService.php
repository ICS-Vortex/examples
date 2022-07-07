<?php

namespace App\Service;

use App\Entity\SocialLink;
use Doctrine\ORM\EntityManager;

class SocialLinksService
{
    private $em;

    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * @return array
     */
    public function getLinks() : array
    {
        $em = $this->em;

        return $em->getRepository(SocialLink::class)->findAll();
    }
}