<?php

namespace App\Repository;

use App\Entity\Dogfight;
use App\Entity\Elo;
use App\Entity\Event;
use App\Entity\Pilot;
use App\Entity\Plane;
use App\Entity\Server;
use App\Entity\Sortie;
use App\Entity\Tour;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Exception;
use Monolog\Logger;

class DogfightRepository extends BaseRepository
{
    /**
     * @param Server $server
     * @param int $count
     * @return array
     * @throws Exception
     * @throws \Doctrine\DBAL\Driver\Exception
     */
    public function getTopKillers(Server $server, int $count = 10): array
    {
        $em = $this->getEntityManager();
        $table = $this->getClassMetadata()->getTableName();
        $pTable = $em->getClassMetadata(Pilot::class)->getTableName();
        $query = "
            SELECT
                {$pTable}.username AS callsign,
                IFNULL(COUNT({$table}.pilot_id), 0) AS kills,
                {$table}.pilot_id AS id
            FROM {$table}
            LEFT JOIN {$pTable} ON {$pTable}.id={$table}.pilot_id
            WHERE server_id = {$server->getId()}
            GROUP BY {$table}.pilot_id
            ORDER BY kills DESC
            LIMIT {$count}
        ";
        return $em->getConnection()->executeQuery($query)->fetchAllAssociative();
    }

    /**
     * @param Server $server
     * @param Tour $tour
     * @return array
     */
    public function getDogfightsInfo(Server $server, Tour $tour): array
    {
        $em = $this->getEntityManager();
        $table = $em->getClassMetadata(Dogfight::class)->getTableName();
        $sql = "
            SELECT 
                pilot_side AS side,
                COUNT(id) AS kills
            FROM `{$table}` 
            WHERE ai_id IS NULL AND in_air = 1 AND server_id = {$server->getId()} AND tour_id={$tour->getId()}
            GROUP BY pilot_side
        ";

        $sql .= "";
        try {
            $queryResult = $em->getConnection()->executeQuery($sql)->fetchAllAssociative();
            $results = array();
            foreach ($queryResult as $item) {
                $results[$item['side']] = (int)$item['kills'];
            }
            if (!isset($results[Dogfight::RED])) {
                $results[Dogfight::RED] = 0;
            }
            if (!isset($results[Dogfight::BLUE])) {
                $results[Dogfight::BLUE] = 0;
            }

            if (($results[Dogfight::RED] + $results[Dogfight::BLUE]) == 0) {
                $results['total'] = 1;
            } else {
                $results['total'] = $results[Dogfight::RED] + $results[Dogfight::BLUE];
            }

            return $results;
        }catch (\Doctrine\DBAL\Driver\Exception|\Exception $e) {
            $this->log($e->getMessage(). ' - ' . $e->getTraceAsString(), Logger::CRITICAL);
            return [
                Dogfight::RED => 0,
                Dogfight::BLUE=> 0,
                'total' => 1,
            ];
        }
    }


    public function getTopDogfighters(Server $server, Tour $tour, $side, $limit = null): array
    {
        $em = $this->getEntityManager();
        $eloTable = $em->getClassMetadata(Elo::class)->getTableName();
        $pilotsTable = $em->getClassMetadata(Pilot::class)->getTableName();
        $dogfightsTable = $em->getClassMetadata(Dogfight::class)->getTableName();
        $tourWhere = null;
        $type = EloRepository::ELO_TYPE_SIDE_TOUR;

        $sql = "
            SELECT
                {$pilotsTable}.id id,
                {$pilotsTable}.country country,
                {$pilotsTable}.username callsign,
                IFNULL(dogfights.dogfights, 0) * 1 dogfights,
                IFNULL({$eloTable}.rating, 1000) rating
            FROM `{$eloTable}`
            LEFT JOIN {$pilotsTable} ON {$pilotsTable}.id={$eloTable}.pilot_id
            LEFT JOIN (
                SELECT pilot_id, COUNT(pilot_id) AS dogfights
                FROM {$dogfightsTable}
                WHERE pilot_side='{$side}' AND tour_id = {$tour->getId()} AND server_id={$server->getId()}
                GROUP BY pilot_id
            ) AS dogfights ON dogfights.pilot_id={$pilotsTable}.id
            WHERE {$eloTable}.side = '{$side}' AND {$eloTable}.server_id = {$server->getId()}
            AND {$eloTable}.tour_id = {$tour->getId()} AND {$eloTable}.type = '{$type}'
            ORDER BY rating DESC
        ";

        if (!empty($limit)) {
            $sql .= " LIMIT {$limit}";
        }

        $result = [];
        foreach ($em->getConnection()->executeQuery($sql)->fetchAllAssociative() as $row) {
            $row['dogfights'] = (int)$row['dogfights'];
            $row['rating'] = (float)$row['rating'];
            $result[] = $row;
        }

        return $result;
    }

    public function getDogfights(Server $server, Pilot $pilot, $side = null, Plane $plane = null, Tour $tour = null)
    {
        $em = $this->getEntityManager();
        $dogfightsTable = $em->getClassMetadata(Dogfight::class)->getTableName();
        $query = "SELECT COUNT(*) AS battles FROM {$dogfightsTable} WHERE 1";
        $query .= " AND (pilot_id={$pilot->getId()} OR victim_id = {$pilot->getId()})";
        $query .= " AND server_id={$server->getId()}";
        $query .= ' AND is_pvp = 1';

        if (!empty($plane)) {
            $query .= " AND (pilot_plane_id={$plane->getId()} OR victim_plane_id = {$plane->getId()})";
        }
        if (!empty($tour)) {
            $query .= " AND tour_id={$tour->getId()}";
        }

        if (!empty($side)) {
            $query .= " AND (pilot_side='{$side}' OR victim_side = '{$side}')";
        }
        $query .= " LIMIT 1";
        try {
            $result = $em->getConnection()->executeQuery($query)->fetchAssociative();
            if ($result) {
                return (int)$result['battles'];
            }

            return 0;
        } catch (DBALException $e) {
            return 0;
        }
    }

    public function getKillsByDay(Server $server, $side, Tour $tour)
    {
        $em = $this->getEntityManager();
        $table = $em->getClassMetadata(Dogfight::class)->getTableName();
        $sql = "
            SELECT
                IFNULL(COUNT({$table}.pilot_id), 0) AS kills,
                DATE_FORMAT({$table}.kill_time, '%Y-%m-%d') AS kill_day
            FROM `{$table}`
            WHERE {$table}.friendly = 0 AND {$table}.ai_id IS NULL AND {$table}.pilot_side='{$side}' AND {$table}.server_id = {$server->getId()} 
                AND {$table}.tour_id={$tour->getId()}
            GROUP BY kill_day
            ORDER BY kill_day ASC
        ";

        return $em->getConnection()->executeQuery($sql)->fetchAllAssociative();
    }

    /**
     * @param Pilot $pilot
     * @param Server|null $server
     * @param Tour|null $tour
     * @return mixed[]
     * @throws DBALException
     */
    public function getPilotDogfights(Pilot $pilot, Server $server = null, Tour $tour = null)
    {
        $em = $this->getEntityManager();
        $dogfightsTable = $em->getClassMetadata(Dogfight::class)->getTableName();
        $pilotsTable = $em->getClassMetadata(Pilot::class)->getTableName();
        $planesTable = $em->getClassMetadata(Plane::class)->getTableName();

        $query = "
            SELECT 
            {$pilotsTable}.id AS pilotId, 
            {$pilotsTable}.username AS pilotCallsign, 
            {$planesTable}.name AS pilotPlane,
            {$planesTable}.id AS pilotPlaneId,
            {$dogfightsTable}.kill_time AS killTime,
            {$dogfightsTable}.pilot_side AS pilotSide,
            {$dogfightsTable}.victim_side AS victimSide,
            victim.id as victimId,
            victim.username as victimCallsign,
            victimPlane.name as victimPlane 
            FROM {$dogfightsTable}
            INNER JOIN {$pilotsTable} ON {$pilotsTable}.id={$dogfightsTable}.pilot_id
            INNER JOIN {$pilotsTable} AS victim ON victim.id={$dogfightsTable}.victim_id
            INNER JOIN {$planesTable} ON {$planesTable}.id = {$dogfightsTable}.pilot_plane_id 
            INNER JOIN {$planesTable} AS victimPlane ON victimPlane.id = {$dogfightsTable}.victim_plane_id
            WHERE ({$dogfightsTable}.pilot_id={$pilot->getId()} OR {$dogfightsTable}.victim_id={$pilot->getId()}) 
        ";
        if (!empty($tour)) {
            $query .= " AND {$dogfightsTable}.tour_id={$tour->getId()}";
        }

        if (!empty($server)) {
            $query .= " AND {$dogfightsTable}.server_id={$server->getId()}";
        }

        $query .= " ORDER BY {$dogfightsTable}.kill_time DESC LIMIT 100";
        $result = $em->getConnection()->executeQuery($query)->fetchAllAssociative();
        return $result;
    }

    public function getAirKillsInfo(Server $server, Tour $tour): array
    {
        $em = $this->getEntityManager();
        $table = $this->getClassMetadata()->getTableName();
        $sql = "
            SELECT 
                pilot_side side,
                COUNT(id) AS kills
            FROM `{$table}` 
            WHERE server_id = {$server->getId()} AND tour_id = {$tour->getId()} and ai_id IS NULL and in_air = 1
            GROUP BY pilot_side
        ";

        $search = $em->getConnection()->executeQuery($sql)->fetchAllAssociative();
        $results = [];
        foreach ($search as $item) {
            $results[$item['side']] = (int)$item['kills'] ?? 0;
        }
        $results['RED'] = $results['RED'] ?? 0;
        $results['BLUE'] = $results['BLUE'] ?? 0;
        $results['total'] = $results['BLUE'] + $results['RED'];

        return $results;
    }

    /**
     * @param array $options
     * @param array $sort
     * @param int $limit
     * @return array
     */
    public function findDogfights($options = [], $sort = [], $limit = 5 ) : array
    {
        $qb = $this->createQueryBuilder('d');
        $qb->where($qb->expr()->andx(
            $qb->expr()->isNotNull('d.victim')
        ));
        foreach ($options as $key => $value) {
            $qb->andWhere("d.{$key} = :{$key}");
            $qb->setParameter($key, $value);
        }
        $qb->andWhere('d.pvp = :isPvp')->setParameter('isPvp', true);
        $qb->orderBy('d.id', 'ASC');
        $qb->setMaxResults($limit);

        return $qb->getQuery()->getResult();
    }

    /**
     * @param Pilot $pilot
     * @return array
     * @throws \Doctrine\DBAL\Driver\Exception
     * @throws Exception
     */
    public function findLastAirWins(Pilot $pilot): array
    {
        $em = $this->getEntityManager();

        $tours = $em->getRepository(Tour::class)->findBy([], ['id' => 'DESC'], 5);
        if (empty($tours)) {
            $this->log('Empty TOURS set in function findLastAirWins in DogfightRepository', Logger::WARNING);
            return [];
        }

        $toursTable = $em->getClassMetadata(Tour::class)->getTableName();
        $dogfightsTable = $em->getClassMetadata(Dogfight::class)->getTableName();

        $toursArray = [];
        /** @var Tour $tour */
        foreach ($tours as $tour) {
            $toursArray[] = $tour->getId();
        }
        sort($toursArray, SORT_NUMERIC);
        $tourIds = implode(',', $toursArray);

        $redTempAirWins = "
            CREATE TEMPORARY TABLE IF NOT EXISTS temp_red_air_wins (
                SELECT 
                    {$toursTable}.id, 
                    {$toursTable}.title, 
                    {$toursTable}.title_en titleEn, 
                    {$dogfightsTable}.pilot_id, 
                    COUNT(pilot_id) airWins
                FROM `{$dogfightsTable}` 
                LEFT JOIN {$toursTable} ON {$toursTable}.id = {$dogfightsTable}.tour_id
                WHERE {$dogfightsTable}.pilot_id={$pilot->getId()} AND {$dogfightsTable}.victim_id IS NOT NULL 
                AND {$dogfightsTable}.friendly = 0 AND {$dogfightsTable}.pilot_side = 'RED' 
                AND {$dogfightsTable}.tour_id IN ({$tourIds}) 
                GROUP BY {$dogfightsTable}.tour_id
            )
        ";
        $blueTempAirWins = "
            CREATE TEMPORARY TABLE IF NOT EXISTS temp_blue_air_wins (
                SELECT 
                    {$toursTable}.id, 
                    {$toursTable}.title, 
                    {$toursTable}.title_en titleEn, 
                    {$dogfightsTable}.pilot_id, 
                    COUNT(pilot_id) airWins
                FROM `{$dogfightsTable}` 
                LEFT JOIN {$toursTable} ON {$toursTable}.id = {$dogfightsTable}.tour_id
                WHERE {$dogfightsTable}.pilot_id={$pilot->getId()} AND {$dogfightsTable}.victim_id IS NOT NULL 
                AND {$dogfightsTable}.friendly = 0 AND {$dogfightsTable}.pilot_side = 'BLUE' 
                AND {$dogfightsTable}.tour_id IN ({$tourIds}) 
                GROUP BY {$dogfightsTable}.tour_id
            )
        ";

        $sql = "
            SELECT 
                traw.id, 
                traw.title, 
                traw.titleEn, 
                IFNULL(traw.airwins, 0) redAirKills, 
                IFNULL(tbaw.airwins, 0) blueAirKills
            FROM temp_red_air_wins traw
            LEFT JOIN temp_blue_air_wins tbaw ON tbaw.pilot_id = traw.pilot_id AND tbaw.id = traw.id
            ORDER BY traw.id ASC
        ";
        $em->getConnection()->executeQuery($redTempAirWins);
        $em->getConnection()->executeQuery($blueTempAirWins);
        $result = $em->getConnection()->executeQuery($sql)->fetchAllAssociative();
        $response = [];
        foreach ($result as $row) {
            $row['id'] = (int)$row['id'];
            $row['redAirKills'] = (int)$row['redAirKills'];
            $row['blueAirKills'] = (int)$row['blueAirKills'];
            $response[] = $row;
        }
        return $response;
    }

    /**
     * @param Pilot $pilot
     * @param Server|null $server
     * @param Tour|null $tour
     * @return array
     */
    public function getPilotBattles(Pilot $pilot, Server $server = null, Tour $tour = null): array
    {
        $em = $this->getEntityManager();
        $pilotsTable = $em->getClassMetadata(Pilot::class)->getTableName();
        $serversTable = $em->getClassMetadata(Server::class)->getTableName();
        $dogfightsTable = $em->getClassMetadata(Dogfight::class)->getTableName();
        $planesTable = $em->getClassMetadata(Plane::class)->getTableName();
        $sql = "
            SELECT  
                st.id               serverId,
                st.name             serverName,
                pt.username         pilotCallsign,
                vt.username         victimCallsign,
                ppt.name            pilotPlane,
                dft.pilot_side      pilotSide,
                vpt.name            victimPlane,
                dft.victim_side     victimSide,
                dft.kill_time       killTime
            FROM {$dogfightsTable} dft
            LEFT JOIN {$planesTable} ppt ON ppt.id = dft.pilot_plane_id
            LEFT JOIN {$planesTable} vpt ON vpt.id = dft.victim_plane_id
            LEFT JOIN {$pilotsTable} pt ON pt.id = dft.pilot_id
            LEFT JOIN {$pilotsTable} vt ON vt.id = dft.victim_id
            LEFT JOIN {$serversTable} st ON st.id = dft.server_id
            WHERE (dft.pilot_id = {$pilot->getId()} OR dft.victim_id = {$pilot->getId()})
                AND dft.is_pvp = 1 AND dft.in_air = 1 and dft.friendly = 0
        ";
        if (!empty($server)) {
            $sql .= " AND dft.server_id = {$server->getId()}";
        }
        if (!empty($tour)) {
            $sql .= " AND dft.tour_id = {$server->getId()}";
        }

        $sql .= " LIMIT 5";
        try {
            return $em->getConnection()->executeQuery($sql)->fetchAllAssociative();
        } catch (\Doctrine\DBAL\Driver\Exception | Exception $e) {
            // TODO Add logging
            return [];
        }
    }


    public function getTopAirWinsFighters(Server $server, Tour $tour, $limit = 10)
    {
        $em = $this->getEntityManager();
        $table = $em->getClassMetadata(Dogfight::class)->getTableName();
        $pilotsTable = $em->getClassMetadata(Pilot::class)->getTableName();
        $sql = "
            SELECT 
                   {$pilotsTable}.id id,
                   {$pilotsTable}.username callsign,
                   COUNT({$table}.id) airWins
            FROM {$table}
            LEFT JOIN {$pilotsTable} ON {$pilotsTable}.id = {$table}.pilot_id
            WHERE {$table}.server_id = {$server->getId()} AND {$table}.tour_id = {$tour->getId()}
                AND {$table}.`in_air` = 1 AND {$table}.friendly = 0 AND {$table}.ai_id IS NULL AND {$table}.is_pvp = 1
            GROUP BY {$table}.pilot_id
            ORDER BY airWins DESC
            LIMIT {$limit}
        ";
        return $em->getConnection()->executeQuery($sql)->fetchAllAssociative();
    }

    public function getTopAirBattleFighters(Server $server, Tour $tour, $limit = 10)
    {
        $em = $this->getEntityManager();
        $table = $em->getClassMetadata(Dogfight::class)->getTableName();
        $pilotsTable = $em->getClassMetadata(Pilot::class)->getTableName();
        $airWinsSql = "
        CREATE TEMPORARY TABLE IF NOT EXISTS temp_airwins_table (
            SELECT
                pilot_id,
                IFNULL(COUNT(pilot_id), 0) as battles
            FROM {$table}
            WHERE server_id = {$server->getId()} AND tour_id = {$tour->getId()}
                AND `in_air` = 1 AND friendly = 0 AND {$table}.ai_id IS NULL AND {$table}.is_pvp = 1
            GROUP BY pilot_id
        )
        ";

        $airLosesSql = "
            CREATE TEMPORARY TABLE IF NOT EXISTS temp_airloses_table (
                SELECT
                    victim_id pilot_id,
                    IFNULL(COUNT(victim_id), 0) as battles
                FROM {$table}
                WHERE server_id = {$server->getId()} AND tour_id = {$tour->getId()}
                    AND `in_air` = 1 AND friendly = 0 AND {$table}.ai_id IS NULL AND {$table}.is_pvp = 1
                GROUP BY victim_id
            )
        ";

        $sql = "
            SELECT 
                plts.id id, 
                plts.username callsign,
                (IFNULL(taw.battles, 0) + IFNULL(tal.battles, 0)) battles
            FROM temp_airwins_table taw
            LEFT JOIN temp_airloses_table tal ON tal.pilot_id = taw.pilot_id
            LEFT JOIN {$pilotsTable} plts ON plts.id = taw.pilot_id
            ORDER BY battles DESC
            LIMIT {$limit}
        ";
        $em->getConnection()->executeQuery($airWinsSql);
        $em->getConnection()->executeQuery($airLosesSql);
        return $em->getConnection()->executeQuery($sql)->fetchAllAssociative();
    }

    public function getTopKillsSortiesFighters(Server $server, Tour $tour, $limit = 10)
    {
        $em = $this->getEntityManager();
        $table = $em->getClassMetadata(Dogfight::class)->getTableName();
        $pilotsTable = $em->getClassMetadata(Pilot::class)->getTableName();
        $sortiesTable = $em->getClassMetadata(Sortie::class)->getTableName();
        $airWinsSql = "
        CREATE TEMPORARY TABLE IF NOT EXISTS temp_airwins_table (
            SELECT
                pilot_id,
                COUNT(pilot_id) as battles
            FROM {$table}
            WHERE server_id = {$server->getId()} AND tour_id = {$tour->getId()}
                AND `in_air` = 1 AND friendly = 0 AND {$table}.ai_id IS NULL AND {$table}.is_pvp = 1
            GROUP BY pilot_id
        )
        ";

        $sortiesSql = "
            CREATE TEMPORARY TABLE IF NOT EXISTS temp_sorties_table (
                SELECT
                    pilot_id,
                    IFNULL(COUNT(pilot_id), 1) as sorties
                FROM {$sortiesTable}
                WHERE server_id = {$server->getId()} AND tour_id = {$tour->getId()}
                GROUP BY pilot_id
            )
        ";

        $sql = "
            SELECT 
                plts.id id, 
                plts.username callsign,
                (IFNULL(taw.battles, 0)/IFNULL(tst.sorties, 1)) ks
            FROM temp_airwins_table taw
            LEFT JOIN temp_sorties_table tst ON tst.pilot_id = taw.pilot_id
            LEFT JOIN {$pilotsTable} plts ON plts.id = taw.pilot_id AND plts.id = tst.pilot_id
            ORDER BY battles DESC
            LIMIT {$limit}
        ";
        $em->getConnection()->executeQuery($airWinsSql);
        $em->getConnection()->executeQuery($sortiesSql);
        return $em->getConnection()->executeQuery($sql)->fetchAllAssociative();
    }


    public function getTopKillsLandingsFighters(Server $server, Tour $tour, $limit = 10)
    {
        $em = $this->getEntityManager();
        $table = $em->getClassMetadata(Dogfight::class)->getTableName();
        $pilotsTable = $em->getClassMetadata(Pilot::class)->getTableName();
        $sortiesTable = $em->getClassMetadata(Sortie::class)->getTableName();
        $airWinsSql = "
        CREATE TEMPORARY TABLE IF NOT EXISTS temp_airwins_table (
            SELECT
                pilot_id,
                COUNT(pilot_id) as battles
            FROM {$table}
            WHERE server_id = {$server->getId()} AND tour_id = {$tour->getId()}
                AND `in_air` = 1 AND friendly = 0 AND {$table}.ai_id IS NULL AND {$table}.is_pvp = 1
            GROUP BY pilot_id
        )
        ";

        $takeoffsSql = "
            CREATE TEMPORARY TABLE IF NOT EXISTS temp_takeoffs_table (
                SELECT
                    pilot_id,
                    IFNULL(COUNT(id), 1) as takeoffs
                FROM {$sortiesTable}
                WHERE server_id = {$server->getId()} AND tour_id = {$tour->getId()}
                GROUP BY pilot_id
            )
        ";
        $landingsSql = "
            CREATE TEMPORARY TABLE IF NOT EXISTS temp_landings_table (
                SELECT
                    pilot_id,
                    IFNULL(COUNT(id), 0) as landings
                FROM {$sortiesTable}
                WHERE server_id = {$server->getId()} AND tour_id = {$tour->getId()} AND landing_airfield_id IS NOT NULL
                GROUP BY pilot_id
            )
        ";

        $sql = "
            SELECT 
                plts.id id, 
                plts.username callsign,
                (taw.battles/IF((tot.takeoffs - tlt.landings) = 0, 1 , (tot.takeoffs - tlt.landings))) kl
            FROM temp_airwins_table taw
            LEFT JOIN temp_takeoffs_table tot ON tot.pilot_id = taw.pilot_id
            LEFT JOIN temp_landings_table tlt ON tlt.pilot_id = taw.pilot_id
            LEFT JOIN {$pilotsTable} plts ON plts.id = taw.pilot_id and plts.id = tot.pilot_id and plts.id = tlt.pilot_id
            ORDER BY battles DESC
            LIMIT {$limit}
        ";
        $em->getConnection()->executeQuery($airWinsSql);
        $em->getConnection()->executeQuery($takeoffsSql);
        $em->getConnection()->executeQuery($landingsSql);
        return $em->getConnection()->executeQuery($sql)->fetchAllAssociative();
    }


    public function getTopKillsDeathsFighters(Server $server, Tour $tour, $limit = 10)
    {
        $em = $this->getEntityManager();
        $table = $em->getClassMetadata(Dogfight::class)->getTableName();
        $pilotsTable = $em->getClassMetadata(Pilot::class)->getTableName();
        $eventsTable = $em->getClassMetadata(Event::class)->getTableName();
        $event = Event::DEATH;

        $airWinsSql = "
        CREATE TEMPORARY TABLE IF NOT EXISTS temp_airwins_table (
            SELECT
                pilot_id,
                COUNT(pilot_id) as battles
            FROM {$table}
            WHERE server_id = {$server->getId()} AND tour_id = {$tour->getId()}
                AND `in_air` = 1 AND friendly = 0 AND {$table}.ai_id IS NULL AND {$table}.is_pvp = 1
            GROUP BY pilot_id
        )
        ";

        $deathsSql = "
            CREATE TEMPORARY TABLE IF NOT EXISTS temp_deaths_table (
                SELECT
                    pilot_id,
                    IFNULL(COUNT(pilot_id), 1) as deaths
                FROM {$eventsTable}
                WHERE server_id = {$server->getId()} AND tour_id = {$tour->getId()} AND event = '{$event}'
                GROUP BY pilot_id
            )
        ";

        $sql = "
            SELECT 
                plts.id id, 
                plts.username callsign,
                (IFNULL(taw.battles, 0)/IFNULL(tdt.deaths, 1)) kd
            FROM temp_airwins_table taw
            LEFT JOIN temp_deaths_table tdt ON tdt.pilot_id = taw.pilot_id
            LEFT JOIN {$pilotsTable} plts ON plts.id = taw.pilot_id AND plts.id = tdt.pilot_id
            ORDER BY battles DESC
            LIMIT {$limit}
        ";
        $em->getConnection()->executeQuery($airWinsSql);
        $em->getConnection()->executeQuery($deathsSql);
        return $em->getConnection()->executeQuery($sql)->fetchAllAssociative();
    }
}
