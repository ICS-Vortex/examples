<?php


namespace App\Controller\Api\Open;


use App\Entity\Dogfight;
use App\Entity\Elo;
use App\Entity\Pilot;
use App\Entity\Plane;
use App\Entity\Server;
use App\Entity\Sortie;
use App\Entity\Tour;
use App\Helper\Helper;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Exception;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/api/open/pilots")
 */
class PilotsController extends AbstractController
{
    /**
     * @param Request $request
     * @param Server|null $server
     * @Route("/ranking/{server}", name="api.open.pilots.ranking")
     * @return JsonResponse
     */
    public function ranking(Request $request, Server $server = null)
    {
        if ($server === null) {
            return $this->json([
                'status' => 1,
                'ranking' => []
            ], 404);
        }
        $em = $this->getDoctrine();
        $tour = $this->getTourById($request->query->getInt('tour', 0));
        $data = $em->getRepository(Pilot::class)->getPilotsRankingInfo($server, $tour);
        return $this->json([
            'status' => 0,
            'ranking' => $data
        ]);
    }

    /**
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param Pilot|null $pilot
     * @return JsonResponse
     * @throws DBALException
     * @throws ExceptionInterface
     * @Route("/{pilot}", name="api.open.pilots.get", methods={"GET"})
     */
    public function pilot(Request $request, SerializerInterface $serializer, Pilot $pilot = null): JsonResponse
    {
        if ($pilot === null) {
            return $this->json([
                'status' => 1,
                'pilot' => null
            ], 404);
        }
        $em = $this->getDoctrine()->getManager();
        $server = $em->getRepository(Server::class)->findOneBy([
            'id' => $request->get('server', 0),
        ]);
        $pilot->setViews($pilot->getViews() + 1);
        $em->persist($pilot);
        $em->flush();
        $tour = $em->getRepository(Tour::class)->findOneBy([
            'id' => (int) $request->get('tour', 0),
        ]);
        $flightTime = $em->getRepository(Sortie::class)->getPilotFlightTime($pilot, $server, $tour);
        $general = $em->getRepository(Pilot::class)->getGeneralInfo($pilot);
        return $this->json([
            'pilot' => $serializer->normalize($pilot, 'json', ['groups' => ['api_profile']]),
            'server' => $serializer->normalize($server, 'json', ['groups' => 'api_open_servers']),
            'tour' => $tour,
            'data' => $general,
            'flightTime' => $flightTime,
        ]);
    }

    /**
     * @param Request $request
     * @param Pilot|null $pilot
     * @return JsonResponse
     * @Route("/{pilot}/planes-stats", name="api.open.pilots.planes_stats", methods={"GET"})
     */
    public function planesStats(Request $request, Pilot $pilot = null): JsonResponse
    {
        if ($pilot === null) {
            return $this->json([
                'status' => 1,
                'message' => 'Not found'
            ], 404);
        }
        $em = $this->getDoctrine()->getManager();
        $server = $em->getRepository(Server::class)->findOneBy([
            'id' => $request->get('server', 0),
        ]);
        $tour = $this->getTourById($request->query->getInt('tour', 0));

        $pilot->setViews($pilot->getViews() + 1);
        $em->persist($pilot);
        $em->flush();
        $planeStats = $em->getRepository(Plane::class)->getPlanesInfoForPilot($pilot, $server, $tour);
        return $this->json($planeStats);
    }


    /**
     * @param Request $request
     * @param Pilot|null $pilot
     * @return JsonResponse
     * @Route("/{pilot}/general", name="api.open.pilots.general", methods={"GET"})
     */
    public function general(Request $request, Pilot $pilot = null): JsonResponse
    {
        $em = $this->getDoctrine()->getManager();
        $server = $em->getRepository(Server::class)->findOneBy([
            'id' => $request->get('server', 0),
        ]);
        if (empty($pilot) || empty($server)) {
            return $this->json([], 404);
        }

        $general = $this->getDoctrine()->getRepository(Pilot::class)->getPilotGeneralInfo($pilot, $server);
        return $this->json($general);
    }

    /**
     * @param Request $request
     * @param Pilot|null $pilot
     * @return JsonResponse
     * @Route("/{pilot}/a2a-by-planes", name="api.open.pilots.a2a_by_planes", methods={"GET"})
     */
    public function a2aByPlanes(Request $request, Pilot $pilot = null): JsonResponse
    {
        $em = $this->getDoctrine()->getManager();
        $server = $em->getRepository(Server::class)->findOneBy([
            'id' => $request->get('server', 0),
        ]);
        if (empty($pilot) || empty($server)) {
            return $this->json([], 404);
        }

        $general = $this->getDoctrine()->getRepository(Pilot::class)->getPilotA2AInfoByPlanes($pilot, $server);
        return $this->json($general);
    }


    /**
     * @param Request $request
     * @param Pilot|null $pilot
     * @return JsonResponse
     * @Route("/{pilot}/a2g-by-planes", name="api.open.pilots.a2g_by_planes", methods={"GET"})
     */
    public function a2gByPlanes(Request $request, Pilot $pilot = null): JsonResponse
    {
        $em = $this->getDoctrine()->getManager();
        $server = $em->getRepository(Server::class)->findOneBy([
            'id' => $request->get('server', 0),
        ]);
        if (empty($pilot) || empty($server)) {
            return $this->json([], 404);
        }

        $general = $this->getDoctrine()->getRepository(Pilot::class)->getPilotA2GInfoByPlanes($pilot, $server);
        return $this->json($general);
    }

    /**
     * @param Request $request
     * @param Pilot|null $pilot
     * @return JsonResponse
     * @Route("/{pilot}/flight-data-by-planes", name="api.open.pilots.flight_data_by_planes", methods={"GET"})
     */
    public function flightDataByPlanes(Request $request, Pilot $pilot = null): JsonResponse
    {
        $em = $this->getDoctrine()->getManager();
        $server = $em->getRepository(Server::class)->findOneBy([
            'id' => $request->get('server', 0),
        ]);
        if (empty($pilot) || empty($server)) {
            return $this->json([], 404);
        }

        $general = $this->getDoctrine()->getRepository(Pilot::class)->getFlightDataByPlanes($pilot, $server);
        return $this->json($general);
    }

    /**
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param Pilot|null $pilot
     * @return JsonResponse
     * @Route("/{pilot}/sorties", name="api.open.pilots.sorties", methods={"GET"})
     */
    public function sorties(Request $request, SerializerInterface $serializer, Pilot $pilot = null): JsonResponse
    {
        if ($pilot === null) {
            return $this->json([], 404);
        }
        $em = $this->getDoctrine()->getManager();
        $server = $em->getRepository(Server::class)->findOneBy([
            'id' => $request->get('server', 0),
        ]);

        $tour = $em->getRepository(Tour::class)->findOneBy([
            'id' => $request->get('tour', 0),
        ]);

        $limit = $request->get('limit', 5);

        $result = [];
        $flights = $em->getRepository(Sortie::class)->getLastFlights($pilot, $server, $tour, $limit);
        /** @var Sortie $flight */
        foreach ($flights as $flight) {
            $result[] = [
                'server' => $flight->getServer()->getName(),
                'startedAt' => $flight->getStartFlight()->format('Y-m-d H:i:s'),
                'takeoffFrom' => $flight->getTakeoffFrom()->getTitle(),
                'plane' => $flight->getPlane()->getName(),
                'landedAt' => ($flight->getLandingAt() !== null) ? $flight->getLandingAt()->getTitle() : '',
                'status' => $flight->getStatus(),
                'duration' => Helper::calculateFlightTime($flight->getTotalTime()),
            ];
        }
        return $this->json($result);
    }

    /**
     * @param Request $request
     * @param Pilot|null $pilot
     * @return JsonResponse
     * @Route("/{pilot}/dogfights", name="api.open.pilots.dogfights", methods={"GET"})
     */
    public function dogfights(Request $request, Pilot $pilot = null): JsonResponse
    {
        if ($pilot === null) {
            return $this->json([], 404);
        }
        $em = $this->getDoctrine()->getManager();
        $server = null;
        $tour = null;
        if (!empty($request->get('server', null))) {
            $server = $em->getRepository(Server::class)->findOneBy([
                'id' => intval($request->get('server', 0)),
            ]);
        }
        if (!empty($request->get('tour', null))) {
            $tour = $em->getRepository(Tour::class)->findOneBy([
                'id' => intval($request->get('tour', 0)),
            ]);
        }

        $data = $em->getRepository(Dogfight::class)->getPilotBattles($pilot, $server, $tour);
        return $this->json($data);
    }

    /**
     * @param Pilot|null $pilot
     * @return JsonResponse
     * @Rest\Get("/{pilot}/elo", name="api.open.pilots.elo")
     */
    public function elo(Pilot $pilot = null): JsonResponse
    {
        if ($pilot === null) {
            return $this->json([], 404);
        }
        $em = $this->getDoctrine()->getManager();
        $lastTours = $em->getRepository(Tour::class)->findBy([], ['id' => 'DESC'], 5);
        $data = $em->getRepository(Elo::class)->findLastElosBySides($pilot, $lastTours);
        return $this->json($data);
    }


    /**
     * @param Pilot|null $pilot
     * @return JsonResponse
     * @throws \Doctrine\DBAL\Driver\Exception
     * @throws Exception
     * @Rest\Get("/{pilot}/airkills", name="api.open.pilots.airkills")
     */
    public function airKills(Pilot $pilot = null): JsonResponse
    {
        if ($pilot === null) {
            return $this->json([], 404);
        }

        $data = $this->getDoctrine()->getRepository(Dogfight::class)->findLastAirWins($pilot);
        return $this->json($data);
    }

    /**
     * @param null $id
     * @return Tour|null
     */
    private function getTourById($id = null): ?Tour
    {
        $em = $this->getDoctrine()->getManager();
        /**@var $tour Tour */
        $tour = null;
        if (empty($id)) {
            $tour = $em->getRepository(Tour::class)->getCurrentTour();
        } else {
            $tour = $em->getRepository(Tour::class)->findOneBy(array(
                'id' => $id,
            ));
            if (empty($tour)) {
                $tour = $em->getRepository(Tour::class)->getCurrentTour();
            }
        }

        return $tour;
    }

}