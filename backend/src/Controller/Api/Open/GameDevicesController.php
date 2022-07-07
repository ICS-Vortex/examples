<?php

namespace App\Controller\Api\Open;

use App\Entity\GameDevice;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/api/open/game-devices")
 */
class GameDevicesController extends AbstractController
{
    /**
     * @Rest\Get("/list")
     * @param SerializerInterface $serializer
     * @return JsonResponse
     * @throws ExceptionInterface
     */
    public function list(SerializerInterface $serializer): JsonResponse
    {
        $devices = $this->getDoctrine()->getRepository(GameDevice::class)->findAll();
        return $this->json($serializer->normalize($devices, 'json', ['groups' => ['api_game_devices']]));
    }
}
