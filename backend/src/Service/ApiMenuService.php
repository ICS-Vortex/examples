<?php

namespace App\Service;

use Doctrine\ORM\EntityManager;

class ApiMenuService
{
    /**
     * @var EntityManager
     */
    private $em;

    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }

    public function getMenuItems(){
        $em = $this->em;

        $categories = $em->getRepository('App:ApiDocCategory')->findBy(array(
            'active' => true,
        ), array(
            'position' => 'ASC'
        ));

        return $categories;
    }
}