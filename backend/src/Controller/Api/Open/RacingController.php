<?php

namespace App\Controller\Api\Open;

use App\Entity\Pilot;
use App\Entity\Plane;
use App\Entity\RaceRun;
use App\Entity\Tournament;
use App\Entity\TournamentStage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/api/open/racing")
 */
class RacingController extends AbstractController
{
    /**
     * @Route("/info", name="api.open.racing.info", methods={"GET"})
     */
    public function info(): JsonResponse
    {
        $em = $this->getDoctrine();

        $statisticsByPlanes = $em->getRepository(RaceRun::class)->getTimingByPlanes();
        return $this->json([
            'planes' => $statisticsByPlanes
        ]);
    }

    /**
     * @Route("/plane-data/{plane}", name="api.open.racing.data", methods={"GET"})
     */
    public function planeData(Request $request, Plane $plane = null): JsonResponse
    {
        if (empty($plane)) {
            return $this->json([], 404);
        }
        $tournament = $this->getDoctrine()->getRepository(Tournament::class)->find($request->get('tournament'));
        return $this->json($this->getDoctrine()->getRepository(RaceRun::class)->getPlaneTiming($plane, $tournament));
    }

    /**
     * @Route("/pilots-data", name="api.open.racing.pilots_data", methods={"GET"})
     */
    public function pilotsData(Request $request): JsonResponse
    {
        $tournament = $this->getDoctrine()->getRepository(Tournament::class)->find($request->get('tournament'));
        return $this->json($this->getDoctrine()->getRepository(RaceRun::class)->getPilotsTiming($tournament));
    }

    /**
     * @Route("/ranking", name="api.open.racing.ranking", methods={"GET"})
     */
    public function ranking(Request $request): JsonResponse
    {
        $tournament = $this->getDoctrine()->getRepository(Tournament::class)->find($request->get('tournament'));
        $stage = $this->getDoctrine()->getRepository(TournamentStage::class)->find($request->get('stage', 0));
        if (!empty($tournament) && !empty($stage)) {
            if (!$tournament->getStages()->contains($stage)) {
                return $this->json([]);
            }
        }
        return $this->json($this->getDoctrine()->getRepository(RaceRun::class)->getBestTiming($tournament, $stage, null));
    }

    /**
     * @Route("/pilot/{ucid}", name="api.open.racing.pilot", methods={"GET"})
     */
    public function pilot(SerializerInterface $serializer, ?string $ucid = null): JsonResponse
    {
        if (empty($ucid)) {
            return $this->json([], 404);
        }
        $pilot = $this->getDoctrine()->getRepository(Pilot::class)->getPilotByUcidPart($ucid);
        if (!empty($pilot)) {
            return $this->json($serializer->normalize($pilot, 'json', ['groups' => ['api_tournaments']]));
        }
        return $this->json([], 404);
    }


}
