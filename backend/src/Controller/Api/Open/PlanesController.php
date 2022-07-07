<?php


namespace App\Controller\Api\Open;


use App\Entity\Pilot;
use App\Entity\Plane;
use App\Entity\Server;
use App\Entity\Tour;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/api/open/planes")
 */
class PlanesController extends AbstractController
{
    /**
     * @Rest\Get("/", name="api.open.planes.all")
     * @param SerializerInterface $serializer
     * @return JsonResponse
     * @throws ExceptionInterface
     */
    public function all(SerializerInterface $serializer)
    {
        $planes = $this->getDoctrine()->getRepository(Plane::class)->findAll();
        return $this->json($serializer->normalize($planes, 'json', ['groups' => ['api_open_servers']]));
    }

    /**
     * @Rest\Get("/helicopters", name="api.open.planes.helicopters")
     * @param SerializerInterface $serializer
     * @return JsonResponse
     * @throws ExceptionInterface
     */
    public function helicopters(SerializerInterface $serializer): JsonResponse
    {
        $planes = $this->getDoctrine()->getRepository(Plane::class)->findBy(['helicopter' => true]);
        return $this->json($serializer->normalize($planes, 'json', ['groups' => ['api_open_servers']]));
    }


}