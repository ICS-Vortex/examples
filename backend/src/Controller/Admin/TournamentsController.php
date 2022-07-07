<?php

namespace App\Controller\Admin;

use App\Entity\Tournament;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/admin/tournaments")
 */
class TournamentsController extends AbstractController
{
    /**
     * @Route("/list", name="api.tournaments.list", methods={"GET"}, options={"expose": true})
     */
    public function lists(SerializerInterface $serializer): JsonResponse
    {
        $tournament = $this->getDoctrine()->getRepository(Tournament::class)->findAll();
        return $this->json($serializer->normalize($tournament, 'json', ['groups' => 'api_tournaments']));
    }

    /**
     * @Route("/{tournament}/info", name="api.tournaments.info", methods={"GET"}, options={"expose": true})
     */
    public function info(SerializerInterface $serializer, Tournament $tournament = null): JsonResponse
    {
        if (empty($tournament)) {
            return $this->json([], 404);
        }
        $tournament = $this->getDoctrine()->getRepository(Tournament::class)->findAll();
        return $this->json($serializer->normalize($tournament, 'json', ['groups' => 'api_tournaments']));
    }

    public function stages()
    {

    }
}
