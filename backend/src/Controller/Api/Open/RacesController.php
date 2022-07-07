<?php


namespace App\Controller\Api\Open;


use App\Entity\Race;
use App\Entity\RaceType;
use App\Entity\Server;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/open/races/{server}")
 */
class RacesController extends AbstractController
{
    /**
     * @Route("/list")
     * @param Request $request
     * @param Server|null $server
     * @return JsonResponse
     */
    public function list(Request $request, Server $server = null): JsonResponse
    {
        if (empty($server)) {
            return $this->json([], 404);
        }
        $type = $request->get('type', null);
        return $this->json($this->getDoctrine()->getRepository(Race::class)->getRaces($server, intval($type)));
    }
}