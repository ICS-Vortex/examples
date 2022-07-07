<?php

namespace App\Repository;

use App\Entity\Pilot;
use App\Entity\Plane;
use App\Entity\Tournament;
use App\Entity\TournamentStage;
use Doctrine\DBAL\Driver\Exception;
use Monolog\Logger;

class RaceRunRepository extends BaseRepository
{
    /**
     * @return array
     */
    public function getTimingByPlanes(): array
    {
        $em = $this->getEntityManager();
        $table = $this->getClassMetadata()->getTableName();
        $planes = $em->getClassMetadata(Plane::class)->getTableName();
        $pilots = $em->getClassMetadata(Pilot::class)->getTableName();
        $sql = "
            SELECT {$planes}.name plane,{$pilots}.username callsign, {$pilots}.country country, MIN(`time`) `time` FROM `{$table}`
            LEFT JOIN {$planes} ON {$planes}.id = {$table}.plane_id
            LEFT JOIN {$pilots} ON {$pilots}.id = {$table}.pilot_id
            WHERE {$planes}.is_helicopter = 1
            GROUP BY {$planes}.id, {$pilots}.id
            ORDER BY `time` ASC
        ";
        $results = [];
        try {
            $result = $em->getConnection()->executeQuery($sql)->fetchAllAssociative();
            foreach ($result as $row) {
                $results[$row['plane']][] = $row;
            }
            return $results;
        } catch (Exception | \Doctrine\DBAL\Exception $e) {
            $this->log($e->getTraceAsString(), Logger::ALERT);
            return [];
        }
    }

    /**
     * @param Plane $plane
     * @param Tournament|null $tournament
     * @return array
     */
    public function getPlaneTiming(Plane $plane, ?Tournament $tournament = null): array
    {
        $em = $this->getEntityManager();
        $table = $this->getClassMetadata()->getTableName();
        $planes = $em->getClassMetadata(Plane::class)->getTableName();
        $pilots = $em->getClassMetadata(Pilot::class)->getTableName();
        $tournamentSQL = '';
        if (!empty($tournament) && !$tournament->getHidden()) {
            $tournamentSQL = "AND {$table}.tournament_id = {$tournament->getId()}";
        }
        $sql = "
            SELECT {$planes}.name plane,{$pilots}.username callsign, {$pilots}.country country, MIN(`time`) `time`
            FROM `{$table}`
            LEFT JOIN {$planes} ON {$planes}.id = {$table}.plane_id
            LEFT JOIN {$pilots} ON {$pilots}.id = {$table}.pilot_id
            WHERE {$table}.plane_id = {$plane->getId()} {$tournamentSQL}
            GROUP BY {$planes}.id, {$pilots}.id
            ORDER BY `time` ASC
            LIMIT 10
        ";

        try {
            return $em->getConnection()->executeQuery($sql)->fetchAllAssociative();
        } catch (Exception | \Doctrine\DBAL\Exception $e) {
            $this->log($e->getTraceAsString(), Logger::ALERT);
            return [];
        }
    }

    /**
     * @param Tournament|null $tournament
     * @return array
     */
    public function getPilotsTiming(?Tournament $tournament = null): array
    {
        $em = $this->getEntityManager();
        $table = $this->getClassMetadata()->getTableName();
        $planes = $em->getClassMetadata(Plane::class)->getTableName();
        $pilots = $em->getClassMetadata(Pilot::class)->getTableName();
        $tournamentSQL = '';
        if (!empty($tournament) && !$tournament->getHidden()) {
            $tournamentSQL = "AND {$table}.tournament_id = {$tournament->getId()}";
        }
        $sql = "
            SELECT
               {$pilots}.username callsign,
               {$planes}.name plane,
               {$pilots}.country country,
               {$pilots}.ip_country ipCountry,
               MIN(`{$table}`.`time`) `time`
            FROM `{$table}`
            LEFT JOIN {$planes} ON {$planes}.id = {$table}.plane_id
            LEFT JOIN {$pilots} ON {$pilots}.id = {$table}.pilot_id
            WHERE {$planes}.is_helicopter = 1 {$tournamentSQL}
            GROUP BY {$pilots}.username, {$planes}.name, {$pilots}.country
            ORDER BY `time`, callsign ASC
        ";

        try {
            $result = [];
            $search = $em->getConnection()->executeQuery($sql)->fetchAllAssociative();
            $i = 0;
            foreach ($search as $row) {
                $row['id'] = $i + 1;
                $result[] = $row;
                $i++;
            }
            return $result;
        } catch (Exception | \Doctrine\DBAL\Exception $e) {
            $this->log($e->getTraceAsString(), Logger::ALERT);
            return [];
        }
    }

    /**
     * @param Tournament|null $tournament
     * @param TournamentStage|null $stage
     * @return array
     */
    public function getBestTiming(?Tournament $tournament, ?TournamentStage $stage, ?int $limit): array
    {
        $em = $this->getEntityManager();
        $table = $this->getClassMetadata()->getTableName();
        $planes = $em->getClassMetadata(Plane::class)->getTableName();
        $pilots = $em->getClassMetadata(Pilot::class)->getTableName();
        $tournamentWhere = '';
        $stageWhere = '';
        $queryLimit = '';
        $stageLimit = '';
        if (!empty($tournament) && !$tournament->getHidden()) {
            $tournamentWhere = "AND {$table}.tournament_id = {$tournament->getId()}";
        }
        if (!empty($limit)) {
            $queryLimit = "LIMIT {$limit}";
        }

        if (!empty($stage) && !$tournament->getHidden()) {
            $stageWhere = "AND {$table}.stage_id = {$stage->getId()}";
            if ($stage->getWinners() !== 0) {
                $queryLimit = "LIMIT {$stage->getWinners()}";
            }
        }
        $sql = "
            SELECT
                DISTINCT {$pilots}.id id,
                {$pilots}.username callsign,
                {$pilots}.country country,
                {$pilots}.ip_country ipCountry,
                {$planes}.name plane,
                rri.min_time time
            FROM {$table} rr
            INNER JOIN (
                SELECT pilot_id, MIN(time) as min_time
                FROM {$table}
                WHERE 1 {$tournamentWhere} {$stageWhere}
                GROUP by pilot_id
            ) rri ON rri.min_time = rr.time AND rr.pilot_id = rri.pilot_id
            LEFT JOIN {$pilots} ON {$pilots}.id = rr.pilot_id
            LEFT JOIN {$planes} ON {$planes}.id = rr.plane_id
            ORDER BY time
            {$queryLimit};
        ";

        try {
            $result = [];
            $search = $em->getConnection()->executeQuery($sql)->fetchAllAssociative();
            $i = 0;
            foreach ($search as $row) {
                $row['id'] = intval($row['id']);
                $row['ranking'] = $i + 1;
                $result[] = $row;
                $i++;
            }
            return $result;
        } catch (Exception | \Doctrine\DBAL\Exception $e) {
            $this->log($e->getTraceAsString(), Logger::ALERT);
            return [];
        }
    }
}
