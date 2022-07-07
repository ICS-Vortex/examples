<?php

namespace App\Repository;

use App\Entity\Dogfight;
use App\Entity\Elo;
use App\Entity\Pilot;
use App\Entity\Server;
use App\Entity\Tour;
use Doctrine\DBAL\Driver\Exception;
use Doctrine\ORM\ORMException;
use Monolog\Logger;

class EloRepository extends BaseRepository
{
    public const ELO_TYPE_PLANE_GENERAL = 'plane_general';
    public const ELO_TYPE_PLANE_TOUR = 'plane_tour';
    public const ELO_TYPE_SIDE_GENERAL = 'side_general';
    public const ELO_TYPE_SIDE_TOUR = 'side_tour';

    public static $planeElos = [
        self::ELO_TYPE_PLANE_TOUR,
        self::ELO_TYPE_PLANE_GENERAL
    ];

    public function typeIsGeneraL(string $type)
    {
        return match ($type) {
            self::ELO_TYPE_PLANE_GENERAL, self::ELO_TYPE_SIDE_GENERAL => true,
            default => false,
        };
    }

    /**
     * @param Dogfight $dogfight
     * @param string $type
     * @return bool
     */
    public function calculateElo(Dogfight $dogfight, $type = EloRepository::ELO_TYPE_SIDE_GENERAL): bool
    {
        $em = $this->getEntityManager();
        $excludeSidesTypes = [EloRepository::ELO_TYPE_PLANE_TOUR, EloRepository::ELO_TYPE_PLANE_GENERAL];
        /** @var DogfightRepository $dogfightsRepo */
        $dogfightsRepo = $em->getRepository(Dogfight::class);
        $pilot = $dogfight->getPilot();
        $victim = $dogfight->getVictim();
        $pilotPlane = $dogfight->getPlane();
        $victimPlane = $dogfight->getVictimPlane();
        $pilotSide = $dogfight->getSide();
        $victimSide = $dogfight->getVictimSide();
        $pilotSearchOptions = [
            'pilot' => $dogfight->getPilot(),
            'tour' => $this->typeIsGeneraL($type) ? null : $dogfight->getTour(),
            'server' => $dogfight->getServer(),
            'type' => $type,
        ];
        $victimSearchOptions = [
            'pilot' => $dogfight->getVictim(),
            'tour' => $this->typeIsGeneraL($type) ? null : $dogfight->getTour(),
            'server' => $dogfight->getServer(),
            'type' => $type,
        ];
        if (in_array($type, self::$planeElos)) {
            $pilotSearchOptions['plane'] = $pilotPlane;
            $victimSearchOptions['plane'] = $victimPlane;
        }

        if (!in_array($type, $excludeSidesTypes)) {
            $pilotSearchOptions['side'] = $pilotSide;
            $victimSearchOptions['side'] = $victimSide;
        }
        /** @var Elo $pilotElo */
        $pilotElo = $em->getRepository(Elo::class)->findOneBy($pilotSearchOptions);
        if (empty($pilotElo)) {
            $pilotElo = new Elo();
            $pilotElo->setServer($dogfight->getServer());
            $pilotElo->setPilot($pilot);
            if ($type === EloRepository::ELO_TYPE_PLANE_TOUR || $type === EloRepository::ELO_TYPE_SIDE_TOUR) {
                $pilotElo->setTour($dogfight->getTour());
            }
            $pilotElo->setType($type);
            if (!in_array($type, $excludeSidesTypes)) {
                $pilotElo->setSide($pilotSide);
            }
            if (in_array($type, self::$planeElos)) {
                $pilotElo->setPlane($pilotPlane);
            }
        }

        /** @var Elo $victimElo */
        $victimElo = $em->getRepository(Elo::class)->findOneBy($victimSearchOptions);
        if (empty($victimElo)) {
            $victimElo = new Elo();
            $victimElo->setServer($dogfight->getServer());
            $victimElo->setPilot($victim);
            $victimElo->setType($type);
            if (!in_array($type, $excludeSidesTypes)) {
                $victimElo->setSide($dogfight->getVictimSide());
            }
            if (in_array($type, self::$planeElos)) {
                $victimElo->setPlane($dogfight->getVictimPlane());
            }
            if ($type === EloRepository::ELO_TYPE_PLANE_TOUR || $type === EloRepository::ELO_TYPE_SIDE_TOUR) {
                $victimElo->setTour($dogfight->getTour());
            }
        }

        $pilotCoefficient = $pilotElo->getCoefficient();
        $pilotRating = $pilotElo->getRating();

        $victimCoefficient = $victimElo->getCoefficient();
        $victimRating = $victimElo->getRating();

        $pilotElo->setRating($pilotRating + $pilotCoefficient * (1 - 1 / (1 + 10 ** (($victimRating - $pilotRating) / 400))));
        $victimElo->setRating($victimRating + $victimCoefficient * (0 - 1 / (1 + 10 ** (($pilotRating - $victimRating) / 400))));

        if ($pilotElo->getRating() >= 2400) {
            $pilotElo->setCoefficient(10);
        } else {
            $dogfights = match ($type) {
                EloRepository::ELO_TYPE_SIDE_TOUR => $dogfightsRepo->getDogfights($dogfight->getServer(), $pilot, $pilotSide, null, $dogfight->getTour()),
                EloRepository::ELO_TYPE_SIDE_GENERAL => $dogfightsRepo->getDogfights($dogfight->getServer(), $pilot, $pilotSide),
                EloRepository::ELO_TYPE_PLANE_TOUR => $dogfightsRepo->getDogfights($dogfight->getServer(), $pilot, null, $pilotPlane, $dogfight->getTour()),
                EloRepository::ELO_TYPE_PLANE_GENERAL => $dogfightsRepo->getDogfights($dogfight->getServer(), $pilot, null, $pilotPlane),
                default => $dogfightsRepo->getDogfights($dogfight->getServer(), $pilot),
            };
            if ($dogfights >= 30) {
                $pilotElo->setCoefficient(20);
            }
        }

        if ($victimElo->getRating() >= 2400) {
            $victimElo->setCoefficient(10);
        } else {
            $dogfights = match ($type) {
                EloRepository::ELO_TYPE_SIDE_TOUR => $dogfightsRepo->getDogfights($dogfight->getServer(), $victim, $victimSide, null, $dogfight->getTour()),
                EloRepository::ELO_TYPE_SIDE_GENERAL => $dogfightsRepo->getDogfights($dogfight->getServer(), $victim, $victimSide),
                EloRepository::ELO_TYPE_PLANE_TOUR => $dogfightsRepo->getDogfights($dogfight->getServer(), $victim, null, $victimPlane, $dogfight->getTour()),
                EloRepository::ELO_TYPE_PLANE_GENERAL => $dogfightsRepo->getDogfights($dogfight->getServer(), $victim, null, $victimPlane),
                default => $dogfightsRepo->getDogfights($dogfight->getServer(), $victim),
            };
            if ($dogfights >= 30) {
                $victimElo->setCoefficient(20);
            }
        }

        try {
            $em->persist($pilotElo);
            $em->flush();

            $em->persist($victimElo);
            $em->flush();
            return true;
        } catch (ORMException $e) {
            return false;
        }
    }

    /**
     * @return bool
     * @deprecated
     */
    public function resetElo(): bool
    {
        $em = $this->getEntityManager();
        $dogfightsTable = $em->getClassMetadata(Dogfight::class)->getTableName();
        $pilotsTable = $em->getClassMetadata(Pilot::class)->getTableName();
        $eloTable = $em->getClassMetadata(Elo::class)->getTableName();
        try {
            $em->getConnection()->executeQuery("DELETE FROM {$eloTable}");
            $em->getConnection()->executeQuery("UPDATE {$pilotsTable} SET br_param = 1000, bk_param = 40,rr_param = 1000,rk_param = 40 ");
            $em->getConnection()->executeQuery("UPDATE {$dogfightsTable} SET elo = 0");
            return true;
        } catch (\Exception $e) {
            $this->log($e->getMessage() . ' in file ' . $e->getFile() . ' at line ' . $e->getLine(), Logger::ALERT);
            $this->log($e->getTraceAsString(), Logger::ALERT);
            return false;
        }
    }

    public function findLastElosBySides(Pilot $pilot, array $tours = []): array
    {
        if (empty($tours)) {
            $this->log('Empty data set in function findLastElosBySides of the EloRepository', Logger::WARNING);
            return [];
        }

        $toursArray = [];
        /** @var Tour $tour */
        foreach ($tours as $tour) {
            $toursArray[] = $tour->getId();
        }
        sort($toursArray, SORT_NUMERIC);
        $tourIds = implode(',', $toursArray);
        $em = $this->getEntityManager();
        $elosTable = $this->getClassMetadata()->getTableName();
        $toursTable = $em->getClassMetadata(Tour::class)->getTableName();
        $type = EloRepository::ELO_TYPE_SIDE_GENERAL;

        $redTempElos = "
            CREATE TEMPORARY TABLE IF NOT EXISTS temp_red_elos (
                SELECT 
                    {$toursTable}.id, 
                    {$toursTable}.title, 
                    {$toursTable}.title_en, 
                    {$elosTable}.side,
                AVG(IFNULL({$elosTable}.rating, 1000)) rating
                FROM {$toursTable} 
                LEFT JOIN {$elosTable} ON {$elosTable}.tour_id = {$toursTable}.id
                WHERE {$elosTable}.pilot_id = {$pilot->getId()} AND {$elosTable}.side = 'RED' 
                    AND {$elosTable}.type = '{$type}' AND {$toursTable}.id IN ({$tourIds})  
                GROUP BY {$elosTable}.tour_id
            )
        ";
        $blueTempElos = "
            CREATE TEMPORARY TABLE IF NOT EXISTS temp_blue_elos (
                SELECT 
                    {$toursTable}.id, 
                    {$toursTable}.title, 
                    {$toursTable}.title_en, 
                    {$elosTable}.side,
                AVG(IFNULL({$elosTable}.rating, 1000)) rating
                FROM {$elosTable} 
                LEFT JOIN {$toursTable} ON {$toursTable}.id = {$elosTable}.tour_id 
                WHERE {$elosTable}.pilot_id = {$pilot->getId()} AND {$elosTable}.side = 'BLUE' 
                    AND {$elosTable}.type = '{$type}' AND {$toursTable}.id IN ({$tourIds}) 
                GROUP BY {$elosTable}.tour_id
            );
        ";
        $sql = "
            SELECT 
               tre.id,
               tre.title,
               tre.title_en titleEn,
               tre.rating red,
               tbe.rating blue
            FROM temp_red_elos tre
            LEFT JOIN temp_blue_elos tbe ON tbe.id = tre.id
            ORDER BY tre.id ASC
        ";

        try {
            $response = [];
            $em->getConnection()->executeQuery($redTempElos);
            $em->getConnection()->executeQuery($blueTempElos);
            $result = $em->getConnection()->executeQuery($sql)->fetchAllAssociative();
            foreach ($result as $row) {
                $row['red'] = (float) $row['red'];
                $row['blue'] = (float)$row['blue'];
                $response[] = $row;
            }
            return $response;
        } catch (Exception | \Doctrine\DBAL\Exception $e) {
            $this->log($e->getMessage() . ' in file ' . $e->getFile() . ' at line ' . $e->getLine(), Logger::ALERT);
            $this->log($e->getTraceAsString(), Logger::ALERT);
            return [];
        }
    }

    public function getTopEloFighters(Server $server, Tour $tour, $limit = 10)
    {
        $em = $this->getEntityManager();
        $table = $em->getClassMetadata(Elo::class)->getTableName();
        $pilotsTable = $em->getClassMetadata(Pilot::class)->getTableName();
        $type = EloRepository::ELO_TYPE_SIDE_TOUR;
        $sql = "
            SELECT 
                   {$pilotsTable}.id id,
                   {$pilotsTable}.username callsign,
                   {$table}.side side, 
                   {$table}.rating rating 
            FROM {$table}
            LEFT JOIN {$pilotsTable} ON {$pilotsTable}.id = {$table}.pilot_id
            WHERE {$table}.server_id = {$server->getId()} AND {$table}.tour_id = {$tour->getId()}
                AND {$table}.`type` = '{$type}'
            ORDER BY rating DESC
            LIMIT {$limit}
        ";
        return $em->getConnection()->executeQuery($sql)->fetchAllAssociative();
    }
}
