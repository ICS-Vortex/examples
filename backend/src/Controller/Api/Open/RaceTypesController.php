<?php


namespace App\Controller\Api\Open;


use App\Entity\RaceType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/api/open/race-types")
 */
class RaceTypesController extends AbstractController
{
    /**
     * @Route("/list")
     */
    public function list(SerializerInterface $serializer): JsonResponse
    {
        $em = $this->getDoctrine();
        $list = $em->getRepository(RaceType::class)->findBy([], ['position' => 'ASC']);
        return $this->json($serializer->normalize($list, 'json', ['groups' => 'api_race_types']));
    }

}