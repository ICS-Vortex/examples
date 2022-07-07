<?php

namespace App\Controller\Api\Open;


use App\Entity\Tour;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/api/open/tours")
 */
class ToursController extends AbstractController
{
    /**
     * @Route("/list", name="api.open.tours.list")
     */
    public function list(SerializerInterface $serializer): JsonResponse
    {
        $tours = $this->getDoctrine()->getRepository(Tour::class)->findAll();
        return $this->json($serializer->normalize($tours, 'json', ['groups' => ['api_tour']]));
    }
}