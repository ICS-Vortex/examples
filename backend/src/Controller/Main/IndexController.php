<?php

namespace App\Controller\Main;

use App\Service\SlidesService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/")
 * @param SlidesService $slidesService
 * @return Response
 */
class IndexController extends AbstractController
{
    /**
     * @Route("/", name="main.index.index")
     * @param SlidesService $slidesService
     * @return Response
     */
    public function index(SlidesService $slidesService) : Response
    {
        if ($this->getParameter('environment') === 'dev') {
            phpinfo();
        }
        return new Response();
    }
}
