<?php


namespace App\Controller\Api\Open;


use App\Entity\MissionRegistry;
use App\Entity\Tournament;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/api/open/tournaments")
 */
class TournamentsController extends AbstractController
{
    /**
     * @Route("/list", name="api.open.tournaments.list", methods={"GET"}, options={"expose": true})
     */
    public function lists(SerializerInterface $serializer): JsonResponse
    {
        $tournament = $this->getDoctrine()->getRepository(Tournament::class)->findBy(['hidden' => false]);
        return $this->json($serializer->normalize($tournament, 'json', ['groups' => 'api_tournaments']));
    }

    /**
     * @Route("/current", name="api.open.tournaments.current", methods={"GET"})
     */
    public function current(SerializerInterface $serializer): JsonResponse
    {
        $tournament = $this->getDoctrine()->getRepository(Tournament::class)->findOneBy(['finished' => false, 'hidden' => false]);
        return $this->json($serializer->normalize($tournament, 'json', ['groups' => 'api_tournaments']));
    }

    /**
     * @Route("/{tournament}/info", name="api.open.tournaments.info", methods={"GET"})
     */
    public function info(SerializerInterface $serializer, Tournament $tournament = null): JsonResponse
    {
        if (empty($tournament)) {
            return $this->json([], 404);
        }
        if ($tournament->getHidden()) {
            return $this->json([], 404);
        }
        for ($i = 0; $i < $tournament->getServers()->count(); $i++) {
            $tournament->getServers()[$i]->setCurrentMissionRegistry(
                $this->getDoctrine()->getRepository(MissionRegistry::class)
                    ->getLastMissionRegistry($tournament->getServers()[$i])
            );
        }

        return $this->json($serializer->normalize($tournament, 'array', ['groups' => ['api_tournaments']] ));
    }
}
