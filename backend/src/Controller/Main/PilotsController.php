<?php

namespace App\Controller\Main;

use App\Entity\Event;
use App\Entity\Pilot;
use App\Entity\Server;
use App\Entity\Sortie;
use App\Entity\Streak;
use App\Entity\Visitor;
use Doctrine\DBAL\DBALException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/pilots")
 */
class PilotsController extends AbstractController
{
    /**
     * @Route("/{pilot}/servers", name="main.pilots.servers", options={"expose"=true})
     * @param SerializerInterface $serializer
     * @param Pilot|null $pilot
     * @return JsonResponse
     * @throws DBALException
     */
    public function servers(SerializerInterface $serializer, Pilot $pilot = null) : JsonResponse
    {
        if ($pilot === null) {
            return $this->json([]);
        }

        $search = $this->getDoctrine()->getRepository(Pilot::class)->getPilotServers($pilot);
        $servers = $serializer->normalize($search, 'json', ['groups' => 'api_open_servers']);

        return $this->json([
            'pilot' => $serializer->normalize($pilot, 'json', ['groups' => ['api_open_servers']]),
            'servers' => $servers,
        ]);
    }

    /**
     * @Route("/{pilot}/server/{server}/info", name="main.pilots.info", options={"expose"=true})
     * @param SerializerInterface $serializer
     * @param Pilot|null $pilot
     * @param Server|null $server
     * @return JsonResponse
     */
    public function info(SerializerInterface $serializer, Pilot $pilot = null, Server $server = null) : JsonResponse
    {
        if ($pilot === null || $server === null) {
            return $this->json([]);
        }
        $events = $serializer->normalize($this->getDoctrine()->getRepository(Event::class)->findBy([
            'pilot' => $pilot,
            'server' => $server,
        ], ['id' => 'DESC'], 10), 'json', ['groups' => 'api_open_servers']);
        $favorPlane = $this->getDoctrine()->getRepository(Sortie::class)
            ->getPilotFavourPlane($pilot, $server);

        $general = $this->getDoctrine()->getRepository(Pilot::class)
            ->getPilotGeneralInfo($pilot, $server);
        $destroyed = $this->getDoctrine()->getRepository(Pilot::class)
            ->getPilotKills($pilot, $server);
        $lastSeen = $this->getDoctrine()->getRepository(Visitor::class)
            ->lastSeen($pilot, $server);
        $flightEvents = $this->getDoctrine()->getRepository(Pilot::class)
            ->getFlightEvents($pilot, $server);
        $streaks = $this->getDoctrine()->getRepository(Streak::class)
            ->getStreaks($pilot, $server);
        return $this->json([
            'events' => $events,
            'general' => $general,
            'lastSeen' => $lastSeen,
            'flightEvents' => $flightEvents,
            'favouritePlane' => $favorPlane,
            'destroyed' => $destroyed,
            'streaks' => $streaks
        ]);
    }
}