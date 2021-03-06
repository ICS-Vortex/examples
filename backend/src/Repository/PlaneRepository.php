<?php

namespace App\Repository;

use App\Entity\Dogfight;
use App\Entity\Event;
use App\Entity\Kill;
use App\Entity\Pilot;
use App\Entity\Plane;
use App\Entity\Server;
use App\Entity\Setting;
use App\Entity\Sortie;
use App\Entity\Tour;
use App\Helper\Helper;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\ORMException;
use Monolog\Logger;

/**
 * PlaneRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PlaneRepository extends BaseRepository
{
    public function getPlane($plane)
    {
        $em = $this->getEntityManager();
        $this->log('Getting place object', Logger::INFO, 'repository');
        if (empty($plane)) {
            $this->log('Empty plane name passed into getPlane() function. Getting fallback plane...', Logger::WARNING, 'repository');
            $planeOption = $em->getRepository(Setting::class)->getOption(SettingRepository::SETTING_FALLBACK_PLANE);
            if ($planeOption !== null) {
                $plane = $planeOption->getValue();
                $this->log('Fallback plane: ' . $plane, Logger::INFO, 'repository');
            } else {
                $this->log('Falback plane options not found.', Logger::EMERGENCY, 'repository');
                return null;
            }
        }

        $planeObject = $this->findOneBy(array(
            'name' => $plane,
        ));

        if (empty($planeObject)) {
            $this->log("Plane {$plane} not found. Adding plane into DB", Logger::INFO, 'repository');
            $newPlane = new Plane();
            $newPlane->setName($plane);

            try {
                $em->persist($newPlane);
                $em->flush();
                $this->log('New plane saved into db', Logger::INFO, 'repository');
                return $newPlane;
            } catch (ORMException $e) {
                $this->log($e->getMessage(), Logger::ERROR, 'repository');
                return null;
            }

        }
        $this->log("Plane {$planeObject->getName()} found", Logger::INFO, 'repository');
        return $planeObject;
    }

    public function getPlanesListForServer(Server $server) : array
    {
        $em = $this->getEntityManager();
        $sortiesTable = $em->getClassMetadata(Sortie::class)->getTableName();
        $planesTable = $em->getClassMetadata(Plane::class)->getTableName();

        $query = "
            SELECT 
                DISTINCT plane_id,
                `{$planesTable}`.*
            FROM `{$sortiesTable}`
            LEFT JOIN `{$planesTable}` ON `{$planesTable}`.id=`{$sortiesTable}`.`plane_id`
            WHERE server_id={$server->getId()}
        ";

        return $em->getConnection()->query($query)->fetchAll();
    }

    /**
     * @param Pilot $pilot
     * @param Server|null $server
     * @param Tour|null $tour
     * @param int $limit
     * @return mixed[]
     */
    public function getPlanesInfoForPilot(Pilot $pilot, Server $server = null, Tour $tour = null, $limit = 5)
    {
        $em = $this->getEntityManager();

        $tourWhere = null;
        if (!empty($tour)) {
            $tourWhere .= "AND tour_id={$tour->getId()}";
        }
        if (!empty($server)) {
            $tourWhere .= " AND server_id={$server->getId()}";
        }
        $planesTable = $em->getClassMetadata(Plane::class)->getTableName();
        $dogfightsTable = $em->getClassMetadata(Dogfight::class)->getTableName();
        $groundKillsTable = $em->getClassMetadata(Kill::class)->getTableName();
        $sortiesTable = $em->getClassMetadata(Sortie::class)->getTableName();
        $eventsTable = $em->getClassMetadata(Event::class)->getTableName();

        $query = "
            SELECT 
                {$planesTable}.id AS id,
                {$planesTable}.name name,
                IFNULL(dogfights.airkills, 0) AS airkills,
                IFNULL(dogfights.airPoints, 0) AS airPoints,
                IFNULL(groundKills.destroyed, 0) AS destroyed,
                IFNULL(groundKills.points, 0) AS groundPoints,
                IFNULL(looses.looses, 0) AS looses,
                IFNULL(deaths.died, 0) AS died,
                IFNULL(takeoffs.takeoffs, 0) AS takeoffs,
                IFNULL(landings.landings, 0) AS landings,
                IFNULL(({$sortiesTable}.sorties), 0) AS sorties,
                IFNULL(({$sortiesTable}.total_time), 0) AS totalTime
            FROM `{$planesTable}`
            INNER JOIN (
                SELECT plane_id, COUNT(plane_id) sorties, SUM(total_time) total_time
                FROM {$sortiesTable}
                WHERE pilot_id={$pilot->getId()} {$tourWhere}
                GROUP BY plane_id
            ){$sortiesTable} ON {$sortiesTable}.plane_id={$planesTable}.id
            LEFT JOIN (
                SELECT COUNT(id) airkills, SUM(points) airPoints, pilot_plane_id plane_id
                FROM `{$dogfightsTable}`
                WHERE friendly != 1 AND pilot_id={$pilot->getId()} {$tourWhere}
                GROUP BY plane_id
            ) AS dogfights ON dogfights.plane_id=`{$planesTable}`.`id`
            LEFT JOIN (
                SELECT COUNT(plane_id) AS destroyed, SUM(points) AS points, plane_id
                FROM `{$groundKillsTable}`
                WHERE friendly != 1 AND pilot_id={$pilot->getId()} {$tourWhere}
                GROUP BY `plane_id`
            ) AS groundKills ON groundKills.plane_id=`{$planesTable}`.`id`
            LEFT JOIN (
                SELECT COUNT(victim_plane_id) AS looses, victim_plane_id AS plane_id
                FROM `{$dogfightsTable}`
                WHERE friendly != 1 AND victim_id={$pilot->getId()} {$tourWhere}
                GROUP BY `victim_plane_id`
            ) AS looses ON looses.plane_id=`{$planesTable}`.`id`
            LEFT JOIN (
                SELECT SUM(total_time) AS total_time, COUNT(plane_id) AS flights, plane_id
                FROM `{$sortiesTable}`
                WHERE pilot_id={$pilot->getId()} {$tourWhere} AND total_time > 0
                GROUP BY plane_id
            ) AS flights ON flights.plane_id=`{$planesTable}`.`id`
            LEFT JOIN
               (SELECT COUNT(event) as died, plane_id
                FROM  `{$eventsTable}` 
                WHERE (event = 'DEATH' AND pilot_id={$pilot->getId()}) {$tourWhere}
                GROUP BY plane_id
            ) AS deaths ON deaths.plane_id=`{$planesTable}`.`id`
            LEFT JOIN
               (SELECT COUNT(event) as takeoffs, plane_id
                FROM  `{$eventsTable}` 
                WHERE (event = 'TAKEOFF' AND pilot_id={$pilot->getId()}) {$tourWhere}
                GROUP BY plane_id
            ) AS takeoffs ON takeoffs.plane_id=`{$planesTable}`.`id`
            LEFT JOIN
               (SELECT COUNT(id) AS landings, plane_id
                FROM  `{$sortiesTable}`
                WHERE pilot_id={$pilot->getId()} AND landing_airfield_id !='' {$tourWhere}
                GROUP BY plane_id
            ) AS landings ON landings.plane_id=`{$planesTable}`.`id`
            LIMIT {$limit}
        ";

        $result = [];
        try {
            $data = $em->getConnection()->query($query)->fetchAll();
            foreach ($data as $row) {
                $row['totalTime'] = Helper::calculateFlightTime($row['totalTime']);
                $result[] = $row;
            }
            return $result;
        }catch (DBALException $e) {
            $this->log("Message: {$e->getMessage()} in file {$e->getFile()}, at line: {$e->getLine()}");
            return [];
        }
    }
}
