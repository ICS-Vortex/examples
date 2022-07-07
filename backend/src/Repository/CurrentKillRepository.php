<?php

namespace App\Repository;

use App\Entity\Pilot;
use App\Entity\Plane;
use App\Entity\Server;
use App\Entity\Unit;
use Doctrine\ORM\EntityManager;

class CurrentKillRepository extends BaseRepository
{
    public const ACTION_DESTROYED = 'destroyed';
    public const ACTION_KILLED = 'killed';

    public function getKills() : array
    {
        $em = $this->getEntityManager();
        $resultArray = array();
        $result = $em->createQuery('
            SELECT COUNT(kills.id) AS total,kills.coalition
            FROM App:CurrentKill kills
            GROUP BY kills.coalition
        ')->execute();
        if(!empty($result)){
            foreach($result as $item){
                $resultArray[] = (object)$item;
            }
            return $resultArray;
        }
        return [];
    }

    public function getCurrentKills(Server $server) : array
    {
        $table = $this->getClassMetadata()->getTableName();
        $pilotsTable = $this->getEntityManager()->getClassMetadata(Pilot::class)->getTableName();
        $planesTable = $this->getEntityManager()->getClassMetadata(Plane::class)->getTableName();
        $unitsTable = $this->getEntityManager()->getClassMetadata(Unit::class)->getTableName();

        $query = "
            SELECT 
                {$table}.action,
                pilot.id pilotId,
                victim.id victimId,
                pilot.username pilotCallsign,
                victim.username victimCallsign,
                pilotPlane.name pilotPlane,
                victimPlane.name victimPlane,
                {$unitsTable}.name unit
            FROM {$table}
            LEFT JOIN {$unitsTable} ON {$unitsTable}.id={$table}.unit_id
            LEFT JOIN {$pilotsTable} pilot ON pilot.id={$table}.pilot_id
            LEFT JOIN {$pilotsTable} victim ON victim.id={$table}.victim_id
            LEFT JOIN {$planesTable} pilotPlane ON pilotPlane.id={$table}.plane_id
            LEFT JOIN {$planesTable} victimPlane ON victimPlane.id={$table}.victim_plane_id
            WHERE {$table}.server_id={$server->getId()}
        ";
        $result = $this->getEntityManager()->getConnection()->query($query)->fetchAll();
        $destroyed = [];
        $killed = [];
        foreach ($result as $row) {
            if ($row['action'] === self::ACTION_DESTROYED) {
                $destroyed[] = $row;
            } else {
                $killed[] = $row;
            }
        }

        return ['destroyed' => $destroyed, 'killed' => $killed];
    }
}
