<?php

namespace App\Controller\Api\Open;

use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/open/qualifications")
 */
class QualificationsController extends AbstractController
{
    /**
     * @Rest\Get("/", name="api.open.planes.all")
     */
    public function info()
    {

    }
}
