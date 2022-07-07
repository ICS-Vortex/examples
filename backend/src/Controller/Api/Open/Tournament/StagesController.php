<?php

namespace App\Controller\Api\Open\Tournament;

use App\Entity\Pilot;
use App\Entity\TournamentStage;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/open/tournament/stages")
 */
class StagesController extends AbstractController
{
    /** @Rest\Get("/{stage}/pilot/{pilot}", name="api.open.tournament.stages.pilot") */
    public function pilot(TournamentStage $stage, Pilot $pilot): JsonResponse
    {
        if (empty($pilot) || empty($stage)) {
            return $this->json([], 404);
        }

        return $this->json($this->getDoctrine()->getRepository(TournamentStage::class)->getPilotInfo($stage, $pilot));
    }
}
