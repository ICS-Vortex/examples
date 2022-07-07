<?php

namespace App\Controller\Api\Open;

use App\Entity\MissionRegistry;
use App\Entity\Tour;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/api/open/missions")
 */
class MissionsController extends AbstractController
{
    /**
     * @Route("/{missionRegistry}", name="api.open.missions.mission", methods={"GET"})
     * @param SerializerInterface $serializer
     * @param MissionRegistry|null $missionRegistry
     * @return JsonResponse
     * @throws ExceptionInterface
     */
    public function mission(SerializerInterface $serializer, MissionRegistry $missionRegistry = null): JsonResponse
    {
        if (empty($missionRegistry)) {
            return $this->json([], 404);
        }

        $killCountsBySide = $missionRegistry->getKillsCountBySide();
        $dogfightsBySide = $missionRegistry->getDogfightsBySide();
        $pointsBySide = $missionRegistry->getPointsBySides();
        $sortiesBySide = $missionRegistry->getSortiesCountsBySide();

        return $this->json([
            'id' => $missionRegistry->getId(),
            'name' => $missionRegistry->getMission()->getName(),
            'winner' => $missionRegistry->getWinner(),
            'start' => $missionRegistry->getStart()->format('Y-m-d H:i:s'),
            'end' => $missionRegistry->getEnd()?->format('Y-m-d H:i:s'),
            'totalSortiesTime' => $missionRegistry->getTotalSortiesTime(),
            'killsBySides' => $killCountsBySide,
            'killsPercents' => $this->getPercentsForSides($killCountsBySide),
            'dogfightsBySides' => $dogfightsBySide,
            'dogfightsPercents' => $this->getPercentsForSides($dogfightsBySide),
            'pointsBySides' => $pointsBySide,
            'pointsPercents' => $this->getPercentsForSides($pointsBySide),
            'sortiesBySides' => $sortiesBySide,
            'sortiesPercents' => $this->getPercentsForSides($sortiesBySide),
            'sortiesHoursBySides' => $missionRegistry->getSortiesHoursBySide(),
            'duration' => $missionRegistry->getDuration(),
            'server' => $serializer->normalize($missionRegistry->getServer(), 'json', ['groups' => 'api_open_servers']),
        ]);
    }

    /**
     * @Route("/{missionRegistry}/ranking", name="api.open.missions.ranking", methods={"GET"})
     * @param SerializerInterface $serializer
     * @param MissionRegistry|null $missionRegistry
     * @return JsonResponse
     */
    public function ranking(SerializerInterface $serializer, MissionRegistry $missionRegistry = null): JsonResponse
    {
        if (empty($missionRegistry)) {
            return $this->json([], 404);
        }
        $data = $this->getDoctrine()->getRepository(MissionRegistry::class)->getRanking($missionRegistry);
        return $this->json($data);
    }


    public function getPercentsForSides($sides)
    {
        $total = $sides['RED'] + $sides['BLUE'];
        $redPercent = $sides['RED'] * 100 / ($total === 0 ? 1 : $total);
        $bluePercent = $sides['BLUE'] * 100 / ($total === 0 ? 1 : $total);
        return ['redPercent' => round($redPercent), 'bluePercent' => round($bluePercent)];
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