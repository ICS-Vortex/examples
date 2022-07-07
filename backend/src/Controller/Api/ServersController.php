<?php


namespace App\Controller\Api;

use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class ServersController
 * @package App\Controller\Api
 * @Route("/api/servers")
 */
class ServersController extends AbstractController
{
    /**
     * @Rest\Get("/list", name="api.servers.list")
     * @param SerializerInterface $serializer
     * @return JsonResponse
     * @throws ExceptionInterface
     */
    public function list(SerializerInterface $serializer): JsonResponse
    {
        return $this->json($serializer->normalize($this->getUser()->getServers(), 'json', ['groups' => ['server']]));
    }
}