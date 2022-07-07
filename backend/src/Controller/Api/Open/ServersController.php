<?php

namespace App\Controller\Api\Open;

use App\Entity\Article;
use App\Entity\Ban;
use App\Entity\CurrentKill;
use App\Entity\Dogfight;
use App\Entity\Elo;
use App\Entity\Event;
use App\Entity\FeaturedVideo;
use App\Entity\Flight;
use App\Entity\Kill;
use App\Entity\MapUnit;
use App\Entity\MissionRegistry;
use App\Entity\Online;
use App\Entity\Pilot;
use App\Entity\Server;
use App\Entity\Sortie;
use App\Entity\Tour;
use Exception;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/api/open/servers")
 */
class ServersController extends AbstractController
{
    /**
     * @Route("/{server}", name="api.open.servers.server")
     * @param SerializerInterface $serializer
     * @param Server|null $server
     * @return JsonResponse
     * @throws ExceptionInterface
     */
    public function server(SerializerInterface $serializer, Server $server = null)
    {
        if (empty($server)) {
            return $this->json([], 404);
        }
        $articles = $this->getDoctrine()->getRepository(Article::class)->findBy(['server' => $server]);
        $videos = $this->getDoctrine()->getRepository(FeaturedVideo::class)->findBy(['server' => $server]);
        $missionRegistry = $this->getDoctrine()->getRepository(MissionRegistry::class)->getLastMissionRegistry($server);
        return $this->json([
            'server' => $serializer->normalize($server, 'json', ['groups' => ['api_open_servers']]),
            'missionRegistry' => $serializer->normalize($missionRegistry, 'json', ['groups' => ['api_mission']]),
            'articles' => $serializer->normalize($articles, 'json', ['groups' => 'api_articles']),
            'videos' => $serializer->normalize($videos, 'json', ['groups' => 'api_featured_video']),
        ]);
    }

    /**
     * @Route("/{server}/banlist", name="api.open.servers.banlist")
     * @param Server|null $server
     * @return JsonResponse
     */
    public function banlist(Server $server = null)
    {
        if (empty($server)) {
            return $this->json([], 404);
        }
        if ($server->showBanList() === false) {
            return $this->json([], 404);
        }
        $banlist = [];
        foreach ($this->getDoctrine()->getRepository(Ban::class)->findBy(['server' => $server]) as $banned) {
            $banlist[] = [
                'id' => $banned->getPilot()->getId(),
                'callsign' => $banned->getPilot()->getCallsign(),
                'ipAddress' => $banned->getIpAddress(),
                'reason' => $banned->getReason(),
                'from' => $banned->getBannedFrom()->format('Y-m-d H:i:s'),
                'until' => $banned->getBannedUntil()->format('Y-m-d H:i:s'),
            ];
        }

        return $this->json($banlist);
    }

    /**
     * @Route("/list/all", name="api.open.servers.list", options={"expose"=true}, methods={"GET"})
     * @param SerializerInterface $serializer
     * @return JsonResponse
     * @throws ExceptionInterface
     */
    public function list(SerializerInterface $serializer): JsonResponse
    {
        return $this->json($serializer->normalize(
            $this->getDoctrine()->getRepository(Server::class)->findBy([
                'active' => true
            ]),
            'json', ['groups' => ['api_open_servers']]
        ));
    }

    /**
     * @param Request $request
     * @param Server|null $server
     * @return JsonResponse
     * @Route("/{server}/kills", name="api.open.servers.tour_kills")
     */
    public function tourKills(Request $request, Server $server = null): JsonResponse
    {
        if (empty($server)) return $this->json([], 404);

        $em = $this->getDoctrine()->getManager();
        $tour = $this->getTourById($request->get('tour', 0));
        $redResults = array();
        $blueResults = array();
        $redKills = $em->getRepository(Kill::class)->getKillsByDay('RED', $server, $tour);
        $blueKills = $em->getRepository(Kill::class)->getKillsByDay('BLUE', $server, $tour);
        foreach ($blueKills as $kill) {
            $dayNumber = (int)date('d', strtotime($kill['kill_day']));
            $blueResults[$dayNumber] = array(
                'dayNumber' => $dayNumber,
                'kills' => (int)$kill['kills'],
            );
        }

        foreach ($redKills as $kill) {
            $dayNumber = (int)date('d', strtotime($kill['kill_day']));
            $redResults[$dayNumber] = array(
                'dayNumber' => $dayNumber,
                'kills' => (int)$kill['kills'],
            );
        }
        $results = [];

        $currentDayNumber = (int)date('t');

        for ($i = 1; $i <= $currentDayNumber; $i++) {
            $day = ($i < 10) ? '0' . $i : $i;
            $results[] = [
                'day' => date('d M', strtotime(date('Y-m-' . $day))),
                'blueKills' => !isset($blueResults[$i]) ? 0 : $blueResults[$i]['kills'],
                'redKills' => !isset($redResults[$i]) ? 0 : $redResults[$i]['kills'],

            ];
        }

        return $this->json($results);
    }

    /**
     * @param Request $request
     * @param Server|null $server
     * @return JsonResponse
     * @Route("/{server}/dogfights", name="api.servers.tour_dogfights")
     */
    public function dogfights(Request $request, Server $server = null): JsonResponse
    {
        if (empty($server)) return $this->json([], 404);
        $tour = $this->getTourById($request->get('tour', 0));
        $em = $this->getDoctrine()->getManager();
        $redResults = array();
        $blueResults = array();
        $redDogfights = $em->getRepository(Dogfight::class)->getKillsByDay($server, 'RED', $tour);
        $blueDogfights = $em->getRepository(Dogfight::class)->getKillsByDay($server, 'BLUE', $tour);
        $currentDayNumber = (int)date('t');

        foreach ($blueDogfights as $dogfight) {
            $dayNumber = (int)date('d', strtotime($dogfight['kill_day']));
            $blueResults[$dayNumber] = array(
                'dayNumber' => $dayNumber,
                'kills' => (int)$dogfight['kills'],
            );
        }

        foreach ($redDogfights as $dogfight) {
            $dayNumber = (int)date('d', strtotime($dogfight['kill_day']));
            $redResults[$dayNumber] = array(
                'dayNumber' => $dayNumber,
                'kills' => (int)$dogfight['kills'],
            );
        }
        $results = [];

        for ($i = 1; $i <= $currentDayNumber; $i++) {
            $day = ($i < 10) ? '0' . $i : $i;
            $results[] = [
                'day' => date('d M', strtotime(date('Y-m-' . $day))),
                'blueKills' => !isset($blueResults[$i]) ? 0 : $blueResults[$i]['kills'],
                'redKills' => !isset($redResults[$i]) ? 0 : $redResults[$i]['kills'],
            ];
        }

        return $this->json($results);
    }

    /**
     * @param SerializerInterface $serializer
     * @param Server $server
     * @return JsonResponse
     * @throws ExceptionInterface
     * @Route("/{server}/online", name="api.open.servers.online")
     */
    public function online(SerializerInterface $serializer, Server $server): JsonResponse
    {
        $online = $this->getDoctrine()->getRepository(Flight::class)->findBy([
            'server' => $server,
        ]);
        return $this->json($serializer->normalize($online, 'json', ['groups' => ['api_online']]));
    }

    /**
     * @Route("/{server}/anticipation", name="api.open.servers.anticipation", options={"expose"=true}, methods={"GET"})
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param Server|null $server
     * @return JsonResponse
     * @throws ExceptionInterface
     */
    public function anticipation(Request $request, SerializerInterface $serializer, Server $server = null): JsonResponse
    {
        if (empty($server)) {
            return $this->json([], 404);
        }

        $em = $this->getDoctrine()->getManager();
        $tourId = (int)$request->get('tour', 0);
        $tour = $this->getTourById($tourId);

        $options['server'] = $server;

        $data = $serializer->normalize($server, 'json', ['groups' => 'api_open_servers']);
        $mr = $this->getDoctrine()->getRepository(MissionRegistry::class)->getLastMissionRegistry($server);
        $missionRegistry = $serializer->normalize($mr, 'json', ['groups' => 'api_mission']);
        if ($server->isOnline()) {
            $missionRegistry = null;
        }
        $online = $this->getDoctrine()->getRepository(Flight::class)->findBy([
            'server' => $server,
        ]);

        return $this->json([
            'tour' => $tour,
            'server' => $data,
            'online' => $serializer->normalize($online, 'json', ['groups' => ['api_online']]),
            'missionRegistry' => $missionRegistry,
        ]);
    }

    /**
     * @Rest\Get ("/{server}/dogfights-performance", name="api.open.servers.dogfights_performance")
     * @param Request $request
     * @param Server|null $server
     * @return JsonResponse
     */
    public function dogfightsPerformance(Request $request, Server $server = null): JsonResponse
    {
        if (empty($server)) {
            return $this->json([], 404);
        }

        $em = $this->getDoctrine()->getManager();
        $tour = $this->getTourById((int)$request->get('tour', 0));

        $dogfights = $em->getRepository(Dogfight::class)->getDogfightsInfo($server, $tour);

        $tourSorties = $em->getRepository(Sortie::class)->getTourSorties($server, $tour);
        $tourDeaths = $em->getRepository(Event::class)->getTourDeaths($server, $tour);

        return $this->json([
            'dogfights' => $dogfights,
            'sorties' => $tourSorties,
            'deaths' => $tourDeaths,
        ]);
    }

    /**
     * @Rest\Get ("/{server}/kills-performance", name="api.open.servers.kills_performance")
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param Server|null $server
     * @return JsonResponse
     */
    public function killsPerformance(Request $request, SerializerInterface $serializer, Server $server = null): JsonResponse
    {
        if (empty($server)) {
            return $this->json([], 404);
        }

        $em = $this->getDoctrine()->getManager();
        $tourId = (int)$request->get('tour', 0);
        $tour = $this->getTourById($tourId);

        $options['server'] = $server;

        $kills = $em->getRepository(Kill::class)->getKillsInfo($server, $tour);

        $tourSorties = $em->getRepository(Sortie::class)->getTourSorties($server, $tour);
        $tourDeaths = $em->getRepository(Event::class)->getTourDeaths($server, $tour);

        return $this->json([
            'kills' => $kills,
            'sorties' => $tourSorties,
            'deaths' => $tourDeaths,
        ]);
    }

    /**
     * @Route("/{server}/played/{tour}", name="api.open.servers.played")
     * @param Server|null $server
     * @param Tour|null $tour
     * @return JsonResponse
     */
    public function played(Server $server = null, Tour $tour = null): JsonResponse
    {
        if (empty($server)) {
            return $this->json([], 404);
        }
        $em = $this->getDoctrine()->getManager();

        $played = $em->getRepository(MissionRegistry::class)->getPlayedMissionsForSides($server, $tour);
        return $this->json($played);
    }

    /**
     * @Route("/{server}/flights/{tour}", name="api.open.servers.flights")
     * @param Server|null $server
     * @param Tour|null $tour
     * @return JsonResponse
     */
    public function flights(Server $server = null, Tour $tour = null): JsonResponse
    {
        $em = $this->getDoctrine()->getManager();

        if (empty($server)) {
            return $this->json([], 404);
        }
        if (empty($tour)) {
            $tour = $em->getRepository(Tour::class)->getCurrentTour();
        }

        $data = $em->getRepository(Sortie::class)->getFlightsInfo($server, $tour);
        return $this->json($data);
    }

    /**
     * @Route("/{server}/flights-data/{tour}", name="api.open.servers.flights_data")
     * @param Server|null $server
     * @param Tour|null $tour
     * @return JsonResponse
     */
    public function flightsData(Server $server = null, Tour $tour = null): JsonResponse
    {
        if (empty($server)) {
            return $this->json([], 404);
        }
        $em = $this->getDoctrine()->getManager();
        if (empty($tour)) {
            $tour = $em->getRepository(Tour::class)->getCurrentTour();
        }
        $data = $em->getRepository(Sortie::class)->getAllFlightsCountByDay($server, $tour);
        return $this->json($data);
    }

    /**
     * @Route("/{server}/ground-kills/{tour}", name="api.open.servers.ground_kills")
     * @param Server|null $server
     * @param Tour|null $tour
     * @return JsonResponse
     * @throws \Doctrine\DBAL\Driver\Exception
     * @throws \Doctrine\DBAL\Exception
     */
    public function groundKills(Server $server = null, Tour $tour = null): JsonResponse
    {
        if (empty($server)) {
            return $this->json([], 404);
        }
        $em = $this->getDoctrine()->getManager();

        $data = $em->getRepository(Kill::class)->getGroundKillsInfoByPoints($server, $tour);
        return $this->json($data);
    }

    /**
     * @Route("/{server}/air-kills/{tour}", name="api.open.servers.air_kills")
     * @param Server|null $server
     * @param Tour|null $tour
     * @return JsonResponse
     */
    public function airKills(Server $server = null, Tour $tour = null): JsonResponse
    {
        if (empty($server)) {
            return $this->json([], 404);
        }
        $em = $this->getDoctrine()->getManager();
        if (empty($tour)) {
            $tour = $em->getRepository(Tour::class)->getCurrentTour();
        }
        $data = $em->getRepository(Dogfight::class)->getAirKillsInfo($server, $tour);
        return $this->json($data);
    }

    /**
     * @Route("/{server}/top-flights/{side}/{tour}", name="api.open.servers.top_flights")
     * @param null $side
     * @param Server|null $server
     * @param Tour|null $tour
     * @return JsonResponse
     */
    public function topFlights($side = null, Server $server = null, Tour $tour = null): JsonResponse
    {
        if (empty($side) || empty($server)) {
            return $this->json([], 404);
        }
        $em = $this->getDoctrine()->getManager();
        return $this->json($em->getRepository(Sortie::class)->getTopFlightPlanes($server, $side, $tour));
    }

    /**
     * @Route("/{server}/top-attackers/{side}/{tour}", name="api.open.servers.top_attackers")
     * @param null $side
     * @param Server|null $server
     * @param Tour|null $tour
     * @return JsonResponse
     */
    public function topAttackers($side = null, Server $server = null, Tour $tour = null): JsonResponse
    {
        if (empty($side) || empty($server)) {
            return $this->json([], 404);
        }
        $em = $this->getDoctrine()->getManager();

        return $this->json(
            $em->getRepository(Kill::class)->getTopGroundAssaults($server, $side, $tour, 10)
        );
    }


    /**
     * @Route("/{server}/top-fighters", name="api.open.servers.top_fighters")
     * @param Request $request
     * @param Server|null $server
     * @return JsonResponse
     */
    public function topFighters(Request $request, Server $server = null): JsonResponse
    {
        $side = $request->get('side', null);
        if (empty($server) || empty($side)) {
            return $this->json([], 404);
        }
        $em = $this->getDoctrine()->getManager();

        $tour = $this->getTourById($request->get('tour'));
        $data = $em->getRepository(Dogfight::class)->getTopDogfighters($server, $tour, $side, 10);
        return $this->json($data
        );
    }

    /**
     * @Route("/top-players/{server}", name="api.open.servers.top_players", options={"expose"=true})
     * @param Server|null $server
     * @return JsonResponse
     */
    public function topPlayers(Server $server = null): JsonResponse
    {
        if ($server === null) {
            return $this->json([
                'topKillers' => [],
                'topAttackers' => [],
            ]);
        }

        return $this->json([
            'topKillers' => $this->getDoctrine()->getRepository(Dogfight::class)->getTopKillers($server),
            'topAttackers' => $this->getDoctrine()->getRepository(Kill::class)->getTopAttackers($server),
        ]);
    }

    /**
     * @Route("/{server}/get-online/{registry}", name="api.open.servers.get_online", options={"expose"=true}, methods={"GET"})
     * @param Server|null $server
     * @param MissionRegistry|null $registry
     * @return JsonResponse
     */
    public function getOnline(Server $server = null, MissionRegistry $registry = null): JsonResponse
    {
        if ($server === null || $registry === null) {
            return $this->json([]);
        }

        $em = $this->getDoctrine()->getManager();
        $online = $em->getRepository(Online::class)->getPilotsOnlineInfo($server, $registry);
        return $this->json($online);
    }

    /**
     * @Route("/popular", name="api.open.servers.popular", methods={"GET"}, options={"expose"=true})
     * @return JsonResponse
     */
    public function popular(): JsonResponse
    {
        return $this->json($this->getDoctrine()->getRepository(Server::class)->getPopularServers());
    }

    /**
     * @Route("/{server}/get-current-kills", name="api.open.servers.get_current_kills", methods={"GET"}, options={"expose"=true})
     * @param Server $server
     * @return JsonResponse
     */
    public function getCurrentKills(Server $server): JsonResponse
    {
        $currentKills = $this->getDoctrine()->getRepository(CurrentKill::class)->getCurrentKills($server);
        return $this->json($currentKills);
    }

    /**
     * @Route("/{server}/missions", name="api.open.servers.missions", options={"expose"=true}, methods={"GET"})
     * @param Server|null $server
     * @return JsonResponse
     */
    public function missions(Server $server = null): JsonResponse
    {
        $missions = $this->getDoctrine()->getRepository(MissionRegistry::class)->getMissions($server);
        return $this->json($missions);
    }

    /**
     * @Route("/{server}/current-mission", name="api.open.servers.current_mission", methods={"GET"})
     * @param SerializerInterface $serializer
     * @param Server|null $server
     * @return JsonResponse
     * @throws ExceptionInterface
     */
    public function currentMission(SerializerInterface $serializer, Server $server = null): JsonResponse
    {
        $mission = $this->getDoctrine()->getRepository(MissionRegistry::class)->getLastMissionRegistry($server);

        return $this->json($serializer->normalize($mission, 'json', ['groups' => 'api_mission']));
    }

    /**
     * @Route("/{server}/map", name="api.open.servers.map", options={"expose" : true})
     * @param Server|null $server
     * @return JsonResponse
     */
    public function map(Server $server = null): JsonResponse
    {
        $response = [];
        if (empty($server) || !$server->isOnline()) {
            return $this->json([]);
        }
        if (!$server->isShowMap()) {
            return $this->json([]);
        }
        $units = $this->getDoctrine()->getRepository(MapUnit::class)->findBy(['server' => $server]);
        /** @var MapUnit $unit */
        foreach ($units as $unit) {
            $response[] = [
                'id' => $unit->getId(),
                'identifier' => $unit->getIdentifier(),
                'title' => $unit->getTitle(),
                'type' => $unit->getType(),
                'country' => $unit->getCountry(),
                'side' => $unit->getSide(),
                'latitude' => $unit->getLatitude(),
                'longitude' => $unit->getLongitude(),
                'altitude' => $unit->getAltitude(),
                'heading' => $unit->getHeading(),
                'isHuman' => $unit->isHuman(),
                'isStatic' => $unit->isStatic(),
            ];
        }
        return $this->json($response);
    }

    /**
     * @Route("/get-pilot-id/{ucid}", options={"expose":true}, name="api.open.servers.get_pilot_id", methods={"GET"})
     * @param $ucid
     * @return JsonResponse
     */
    public function getPilotId($ucid): JsonResponse
    {
        /** @var Pilot $pilot */
        $pilot = $this->getDoctrine()->getRepository(Pilot::class)->findOneBy([
            'ucid' => $ucid,
        ]);
        if ($pilot === null) {
            return $this->json([
                'status' => 1,
            ]);
        }
        return $this->json([
            'status' => 0,
            'id' => $pilot->getId(),
        ]);
    }

    /**
     * @Route("/get-online-html/{pilot}/{server}", name="api.open.servers.get_online_html", methods={"POST"})
     * @param Pilot|null $pilot
     * @param Server|null $server
     * @return Response
     */
    public function getOnlineHtml(Pilot $pilot = null, Server $server = null): Response
    {
        if ($pilot === null || $server === null) {
            return new Response('');
        }
        $member = $this->getDoctrine()->getRepository(Online::class)->getPilotOnlineInfo($pilot);
        return $this->render('servers/index/member.html.twig', [
            'member' => $member,
            'server' => $server
        ]);
    }

    /**
     * @Route("/{server}/status", name="api.open.servers.status", methods={"GET"})
     * @param Server|null $server
     * @return JsonResponse
     */
    public function status(Server $server = null): JsonResponse
    {
        $result = array();
        if ($server === null) {
            return $this->json($result);
        }
        try {
            $fp = fsockopen($server->getAddress(), $server->getPort(), $errno, $errstr, 5);
            if (!$fp) {
                $result['status'] = 1;
                $result['message'] = 'Server offline';
            } else {
                $result['status'] = 0;
                $result['message'] = 'Server online';
            }
            return new JsonResponse($result);
        } catch (Exception $e) {
            $result['status'] = 999;
            $result['message'] = 'Server offline';
            return new JsonResponse($result);
        }
    }

    /**
     * @Route("/{server}/pilots-pvp-ranking", name="api.open.servers.pilots_pvp_ranking", methods={"GET"})
     * @param Request $request
     * @param Server|null $server
     * @return JsonResponse
     */
    public function pilotsPvpRanking(Request $request, Server $server = null): JsonResponse
    {
        if (empty($server)) {
            return $this->json([
                'pilots' => [],
            ], 404);
        }
        $em = $this->getDoctrine()->getManager();
        $tourId = $request->query->getInt('tour', 0);
        $tour = $this->getTourById($tourId);
        $data = $em->getRepository(Pilot::class)->getPilotsPvpRanking($server, $tour);

        return $this->json($data);
    }

    /**
     * @param Request $request
     * @param Server $server
     * @return Response
     * @Route("/{server}/all-dogfights", name="api.open.servers.all_dogfights", methods={"GET"})
     */
    public function allDogfights(Request $request, Server $server): Response
    {
        $tour = $this->getTourById($request->query->getInt('tour', 0));
        $dogfights = $this->getDoctrine()->getRepository(Dogfight::class)->findBy(
            ['tour' => $tour, 'server' => $server, 'pvp' => true]
        );
        $result = [];
        /** @var Dogfight $dogfight */
        foreach ($dogfights as $dogfight) {
            $result[] = [
                'time' => $dogfight->getKillTime()->format('Y-m-d H:i:s'),
                'winnerId' => $dogfight->getPilot()->getId(),
                'winnerCallsign' => $dogfight->getPilot()->getCallsign(),
                'winnerSide' => $dogfight->getSide(),
                'winnerPlane' => $dogfight->getPlane()->getName(),
                'loserId' => $dogfight->getVictim()->getId(),
                'loserCallsign' => $dogfight->getVictim()->getCallsign(),
                'loserPlane' => $dogfight->getVictimPlane()->getName(),
                'loserSide' => $dogfight->getVictimSide(),
            ];
        }
        return $this->json([
            'dogfights' => $result,
        ]);
    }

    /**
     * @param Request $request
     * @param Server|null $server
     * @return Response
     * @Route("/{server}/fighters-ranking", name="api.open.servers.fighters_ranking", methods={"GET"})
     */
    public function fightersRanking(Request $request, Server $server = null): Response
    {
        if (empty($server)) {
            return $this->json([], 404);
        }
        $em = $this->getDoctrine()->getManager();
        $tour = $this->getTourById($request->query->getInt('tour', 0));

        return $this->json([
            'redRanking' => $em->getRepository(Dogfight::class)
                ->getTopDogfighters($server, $tour, Dogfight::RED, 20),
            'blueRanking' => $em->getRepository(Dogfight::class)
                ->getTopDogfighters($server, $tour, Dogfight::BLUE, 20),
        ]);
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

    /**
     * @param Request $request
     * @param Server|null $server
     * @return JsonResponse
     * @Route("/{server}/pilots-planes-pvp-ranking", name="api.open.servers.pilots_planes_pvp_ranking")
     */
    public function pilotsPlanesPvpRanking(Request $request, Server $server = null): JsonResponse
    {
        if (empty($server)) {
            return $this->json([
                'status' => 1,
                'ranking' => []
            ], 404);
        }
        $em = $this->getDoctrine();
        $tour = $this->getTourById($request->query->getInt('tour', 0));
        $data = $em->getRepository(Pilot::class)->getPilotsPlanesPvpRankingInfo($server, $tour);
        return $this->json($data);
    }

    /**
     * @Rest\Get("/{server}/pilots-pve-ranking")
     * @param Request $request
     * @param Server|null $server
     * @return JsonResponse
     * @throws \Doctrine\DBAL\Driver\Exception
     * @throws \Doctrine\DBAL\Exception
     */
    public function pilotsPveRanking(Request $request, Server $server = null): JsonResponse
    {
        if (empty($server)) {
            return $this->json([
                'status' => 1,
                'ranking' => []
            ], 404);
        }

        $em = $this->getDoctrine();
        $tour = $this->getTourById($request->query->get('tour', 0));
        $data = $em->getRepository(Server::class)->getPvePilotsRanking($server, $tour);
        return $this->json($data);
    }


    /**
     * @param Request $request
     * @param Server|null $server
     * @return JsonResponse
     * @Route("/{server}/pilots-planes-pve-ranking", name="api.open.servers.pilots_planes_pve_ranking")
     */
    public function pilotsPlanesPveRanking(Request $request, Server $server = null): JsonResponse
    {
        if (empty($server)) {
            return $this->json([
                'status' => 1,
                'ranking' => []
            ], 404);
        }
        $em = $this->getDoctrine();
        $tour = $this->getTourById($request->query->getInt('tour', 0));
        $data = $em->getRepository(Pilot::class)->getPilotsPlanesPveRankingInfo($server, $tour);
        return $this->json($data);
    }


    /**
     * @Route("/{server}/top-elo-fighters", name="api.open.servers.top_elo_fighters")
     * @param Request $request
     * @param Server|null $server
     * @return JsonResponse
     */
    public function topEloFighters(Request $request, Server $server = null): JsonResponse
    {
        if (empty($server)) {
            return $this->json([], 404);
        }
        $em = $this->getDoctrine()->getManager();
        $limit = (int)$request->get('limit', 10);
        $tour = $this->getTourById($request->get('tour', 0));
        $data = $em->getRepository(Elo::class)->getTopEloFighters($server, $tour, $limit);
        return $this->json($data);
    }

    /**
     * @Route("/{server}/top-airwins-fighters", name="api.open.servers.top_airwins_fighters")
     * @param Request $request
     * @param Server|null $server
     * @return JsonResponse
     */
    public function topAirWinsFighters(Request $request, Server $server = null): JsonResponse
    {
        if (empty($server)) {
            return $this->json([], 404);
        }
        $em = $this->getDoctrine()->getManager();
        $limit = (int)$request->get('limit', 10);
        $tour = $this->getTourById($request->get('tour', 0));
        $data = $em->getRepository(Dogfight::class)->getTopAirWinsFighters($server, $tour, $limit);
        return $this->json($data);
    }

    /**
     * @Route("/{server}/top-airbattles-fighters", name="api.open.servers.top_airbattles_fighters")
     * @param Request $request
     * @param Server|null $server
     * @return JsonResponse
     */
    public function topAirBattlesFighters(Request $request, Server $server = null): JsonResponse
    {
        if (empty($server)) {
            return $this->json([], 404);
        }
        $em = $this->getDoctrine()->getManager();
        $limit = (int)$request->get('limit', 10);
        $tour = $this->getTourById($request->get('tour', 0));
        $data = $em->getRepository(Dogfight::class)->getTopAirBattleFighters($server, $tour, $limit);
        return $this->json($data);
    }

    /**
     * @Route("/{server}/top-sorties-fighters", name="api.open.servers.top_sorties_fighters")
     * @param Request $request
     * @param Server|null $server
     * @return JsonResponse
     */
    public function topKillsSortiesFighters(Request $request, Server $server = null): JsonResponse
    {
        if (empty($server)) {
            return $this->json([], 404);
        }
        $em = $this->getDoctrine()->getManager();
        $limit = (int)$request->get('limit', 10);
        $tour = $this->getTourById($request->get('tour', 0));
        $data = $em->getRepository(Dogfight::class)->getTopKillsSortiesFighters($server, $tour, $limit);
        return $this->json($data);
    }

    /**
     * @Route("/{server}/top-kills-landings-fighters", name="api.open.servers.top_kills_landings_fighters")
     * @param Request $request
     * @param Server|null $server
     * @return JsonResponse
     */
    public function topKillsLandingsFighters(Request $request, Server $server = null): JsonResponse
    {
        if (empty($server)) {
            return $this->json([], 404);
        }
        $em = $this->getDoctrine()->getManager();
        $limit = (int)$request->get('limit', 10);
        $tour = $this->getTourById($request->get('tour', 0));
        $data = $em->getRepository(Dogfight::class)->getTopKillsLandingsFighters($server, $tour, $limit);
        return $this->json($data);
    }


    /**
     * @Route("/{server}/top-kills-deaths-fighters", name="api.open.servers.top_kills_deaths_fighters")
     * @param Request $request
     * @param Server|null $server
     * @return JsonResponse
     */
    public function topKillsDeathsFighters(Request $request, Server $server = null): JsonResponse
    {
        if (empty($server)) {
            return $this->json([], 404);
        }
        $em = $this->getDoctrine()->getManager();
        $limit = (int)$request->get('limit', 10);
        $tour = $this->getTourById($request->get('tour', 0));
        $data = $em->getRepository(Dogfight::class)->getTopKillsDeathsFighters($server, $tour, $limit);
        return $this->json($data);
    }

    /**
     * @Route("/{server}/mission-sessions", name="api.open.servers.mission_sessions", methods={"GET"})
     * @param Request $request
     * @param Server|null $server
     * @return JsonResponse
     */
    public function missionSessions(Request $request, Server $server = null): JsonResponse
    {
        $tour = $this->getTourById($request->get('tour', 0));
        if (empty($server)) {
            return $this->json([], 404);
        }
        $data = $this->getDoctrine()->getRepository(MissionRegistry::class)->getList($server, $tour);
        return $this->json($data);
    }

    /**
     * @Route("/{server}/aebobatics-top-planes", name="api.open.servers.aerobatics_top_planes", methods={"GET"})
     * @param Request $request
     * @param Server|null $server
     * @return JsonResponse
     * @throws \Doctrine\DBAL\Driver\Exception
     * @throws \Doctrine\DBAL\Exception
     */
    public function aerobaticsTopPlanes(Request $request, Server $server = null): JsonResponse
    {
        $tour = $this->getTourById($request->get('tour', 0));
        $limit = (int)$request->get('limit', 10);
        if (empty($server)) {
            return $this->json([], 404);
        }
        $data = $this->getDoctrine()->getRepository(Sortie::class)->getAerobaticsTopPLanes($server, $tour, $limit);
        return $this->json($data);
    }

    /**
     * @Route("/{server}/aebobatics-top-pilots", name="api.open.servers.aerobatics_top_pilots", methods={"GET"})
     * @param Request $request
     * @param Server|null $server
     * @return JsonResponse
     * @throws \Doctrine\DBAL\Driver\Exception
     * @throws \Doctrine\DBAL\Exception
     */
    public function aerobaticsTopPilotsPlanes(Request $request, Server $server = null): JsonResponse
    {
        $tour = $this->getTourById($request->get('tour', 0));
        $limit = (int)$request->get('limit', 10);
        $type = (int)$request->get('type', 0);
        if (empty($server)) {
            return $this->json([], 404);
        }
        $data = $this->getDoctrine()->getRepository(Sortie::class)->getTopPilotsForType($server, $tour, $type, $limit);
        return $this->json($data);
    }


    /**
     * @Route("/{server}/aerobatics-top-attackers", name="api.open.servers.aerobatics_top_attackers", methods={"GET"})
     * @param Request $request
     * @param Server|null $server
     * @return JsonResponse
     */
    public function aerobaticsTopAttackers(Request $request, Server $server = null): JsonResponse
    {
        $tour = $this->getTourById($request->get('tour', 0));
        $limit = (int)$request->get('limit', 10);

        if (empty($server)) {
            return $this->json([], 404);
        }
        $data = $this->getDoctrine()->getRepository(Kill::class)->getTopAttackers($server, $tour, $limit);
        return $this->json($data);
    }
}
