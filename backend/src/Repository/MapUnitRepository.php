<?php

namespace App\Repository;

use App\Entity\MapUnit;
use Doctrine\DBAL\DBALException;
use Monolog\Logger;

class MapUnitRepository extends BaseRepository
{
    public const TYPE_PLANE = 'Plane';
    public const TYPE_HELICOPTER = 'Helicopter';
    public const TYPE_SAM = 'SAM';
    public const TYPE_SHIP = 'Ship';
    public const TYPE_ARTILLERY = 'Artillery';

    public function save(MapUnit $unit) : bool
    {
        $table = $this->getClassMetadata()->getTableName();
        $isHuman = $unit->isHuman() === true ? '1' : '0';
        $isStatic = $unit->isStatic() === true ? '1' : '0';
        $query = "INSERT INTO {$table} (identifier, `type`, is_human, is_static, title, country, side, latitude, longitude, altitude, heading, server_id) ";
        $values = "{$unit->getIdentifier()}, '{$unit->getType()}', '{$isHuman}', '{$isStatic}', '{$unit->getTitle()}', '{$unit->getCountry()}', '{$unit->getSide()}',";
        $values .= "{$unit->getLatitude()}, {$unit->getLongitude()}, {$unit->getAltitude()}, {$unit->getHeading()}, {$unit->getServer()->getId()}";
        $update = 'latitude=VALUES(latitude), longitude=VALUES(longitude),altitude=VALUES(altitude),heading=VALUES(heading)';
        $query .= "VALUES ({$values}) ON DUPLICATE KEY UPDATE {$update}";
        try {
            $this->log('Running query: '.$query, Logger::INFO, 'repository');
            $this->getEntityManager()->getConnection()->query($query);
            $this->log('Done', Logger::INFO, 'repository');
            return true;
        } catch (DBALException $e) {
            $this->log($e->getMessage(), Logger::CRITICAL, 'repository');
            return false;
        }
    }
}
