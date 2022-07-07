<?php

namespace App\Controller\Api\Open;

use App\Entity\Slide;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/open/slides")
 */
class SlidesController extends AbstractController
{
    /** @Route("/list", name="api.open.slides.list", schemes={"%secure%"}, options={"expose"=true}, methods={"GET"}) */
    public function list() : JsonResponse
    {
        return $this->json(
            $this->getDoctrine()->getRepository(Slide::class)->findBy([],['orderNumber' => 'ASC'])
        );
    }
}
