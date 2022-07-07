<?php

namespace App\Repository;

use App\Entity\Pilot;
use App\Entity\Plane;
use App\Entity\RaceType;
use App\Entity\Server;
use Doctrine\DBAL\Driver\Exception;
use Monolog\Logger;

class RaceRepository extends BaseRepository
{
    public function getRaces(Server $server, int $type): array
    {
        $em = $this->getEntityManager();
        $table = $this->getClassMetadata()->getTableName();
        $pilots = $em->getClassMetadata(Pilot::class)->getTableName();
        $planes = $em->getClassMetadata(Plane::class)->getTableName();
        $types = $em->getClassMetadata(RaceType::class)->getTableName();
        $typeWhere = '';
        if (!empty($type)) {
            $typeWhere .= 'AND races.type_id = ' . $type;
        }
        $sql = "
        SELECT  
            {$table}.pilot_id id, 
            {$types}.type,
            {$pilots}.username callsign, 
            {$pilots}.country country,
            {$planes}.name plane,
            MIN({$table}.time) AS race_time
        FROM `{$table}` 
        LEFT JOIN {$pilots} on {$pilots}.id = {$table}.pilot_id
        LEFT JOIN {$planes} ON planes.id = {$table}.plane_id
        LEFT JOIN {$types} ON {$types}.id = {$table}.type_id
        WHERE {$table}.server_id = {$server->getId()} {$typeWhere}
        GROUP BY {$table}.pilot_id, {$planes}.name, {$types}.type
        ORDER BY race_time ASC
        ";
        try {
            return $em->getConnection()->executeQuery($sql)->fetchAllAssociative();
        } catch (Exception | \Doctrine\DBAL\Exception $e) {
            $this->log($e->getTraceAsString(), Logger::EMERGENCY);
            return [];
        }
    }
}
