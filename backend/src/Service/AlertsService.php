<?php

namespace App\Service;

use App\Entity\Pilot;
use Doctrine\ORM\EntityManager;

class AlertsService
{
    private $em;

    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }

    public function getAlerts(Pilot $admin){
        $alerts = $this->em->getRepository('App:Alert')->findBy(array(
            'seen' => false,
            'targetUser' => $admin
        ));

        return $alerts;
    }

}