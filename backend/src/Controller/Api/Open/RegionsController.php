<?php

namespace App\Controller\Api\Open;

use App\Entity\Location\Region;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/api/open/regions")
 */
class RegionsController extends AbstractController
{
    /**
     * @Route("/list")
     * @param SerializerInterface $serializer
     * @return JsonResponse
     * @throws ExceptionInterface
     */
    public function list(SerializerInterface $serializer): JsonResponse
    {
        $regions = $this->getDoctrine()->getRepository(Region::class)->findAll();
        return $this->json($serializer->normalize($regions, 'json', ['groups' => ['api_tournaments']]));
    }
}
