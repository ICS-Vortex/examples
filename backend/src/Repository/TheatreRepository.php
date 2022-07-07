<?php

namespace App\Repository;

use App\Entity\Flight;
use App\Entity\Theatre;
use Doctrine\ORM\ORMException;
use Monolog\Logger;

class TheatreRepository extends BaseRepository
{
    /**
     * @param $time
     * @param Flight $flight
     * @return false
     */
    public function isNightTime($time, Flight $flight)
    {
        $theatre = $flight->getRegisteredMission()->getTheatre();
        if (empty($theatre)) {
            return false;
        }

        if (empty($theatre->getNightEnd()) || empty($theatre->getNightStart())) {
            return false;
        }

        $now = date('Y-m-d H:i:s', strtotime($time));

        $dayStart = date('Y-m-d 00:00:00', strtotime($time));
        $dayEnd = date('Y-m-d 23:59:59', strtotime($time));

        $nightStart = date('Y-m-d', strtotime($time)) .' '. $theatre->getNightStart()->format('H:i:s');
        $nightEnd = date('Y-m-d', strtotime($time)) .' '. $theatre->getNightEnd()->format('H:i:s');

        if ($now >= $dayStart && $now <= $nightEnd) {
            return true;
        }

        if ($now >= $nightStart && $now <= $dayEnd) {
            return true;
        }
        return false;
    }

    /**
     * @param string|null $name
     * @return Theatre|object|null
     */
    public function getTheatre(?string $name)
    {
        if (empty($name)) {
            return null;
        }
        $theatre = $this->findOneBy([
            'name' => $name
        ]);

        try {
            if (empty($theatre)) {
                $theatre = new Theatre();
                $theatre->setName($name);

                $this->getEntityManager()->persist($theatre);
                $this->getEntityManager()->flush();
            }

            return $theatre;
        } catch (ORMException $e) {
            $this->log($e->getMessage(), Logger::ALERT, 'repository');
            return null;
        }
    }
}
