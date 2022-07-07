<?php

namespace App\Repository;

use App\Entity\Airfield;
use App\Entity\Flight;
use App\Entity\Pilot;
use App\Entity\Sortie;
use DateTime;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\ORMException;
use Exception;
use Monolog\Logger;

class FlightRepository extends BaseRepository
{
    public function getCurrentFlight(Pilot $pilot) : ?Flight
    {
        $em = $this->getEntityManager();
        /** @var Flight $flight */
        $flight = $em->getRepository(Flight::class)->findOneBy(array(
            'pilot' => $pilot,
        ));

        return $flight;
    }

    /**
     * @param Flight $flight
     * @param $time
     * @param Airfield|null $landedAt
     * @param string $status
     * @return bool
     * @throws Exception
     */
    public function endFlight(Flight $flight, $time, Airfield $landedAt = null, $status = SortieRepository::STATUS_AIRFIELD) : bool
    {

        $em = $this->getEntityManager();

        $sortie = new Sortie();
        $sortie->setPilot($flight->getPilot());
        $sortie->setTheatre($flight->getTheatre());

        $sortie->setTour($flight->getTour());
        $sortie->setRegisteredMission($flight->getRegisteredMission());
        $sortie->setSide($flight->getSide());
        $sortie->setEmergencyFlight($flight->isEmergencyFlight());
        if(empty($landedAt) && $status !== SortieRepository::STATUS_AIRFIELD && $status !== SortieRepository::STATUS_FIELD) {
            $sortie->setEmergencyFlight(true);
        }
        $sortie->setStatus($status);
        $sortie->setPlane($flight->getPlane());
        $sortie->setTakeoffFrom($flight->getAirfield());
        $sortie->setStartFlight(new DateTime($flight->getStartFlightTime()->format('Y-m-d H:i:s')));
        $sortie->setLandingAt($landedAt);
        $sortie->setEndFlight(new DateTime($time));
        $sortie->setTotalTime(
            strtotime($time) - strtotime($flight->getStartFlightTime()->format('Y-m-d H:i:s'))
        );
        $sortie->setNightFlight($flight->isNightFlight());
        $sortie->setGroupFlight($flight->isGroupFlight());
        $sortie->setBadWeatherFlight($flight->isBadWeatherFlight());
        $sortie->setCombatFlight($flight->isCombatFlight());
        $sortie->setServer($flight->getServer());

        try {
            $em->persist($sortie);
            $em->remove($flight);
            $em->flush();
            return true;
        } catch (ORMException $e) {
            $this->log($e->getMessage() . "File: {$e->getFile()}, line: {$e->getLine()}", Logger::ALERT, 'repository');
            return false;
        }
    }

    /**
     * @param Flight $flight
     * @return bool
     * @deprecated
     */
    public function save(Flight $flight) : bool
    {
        $em = $this->getEntityManager();

        $time = $flight->getStartFlightTime()->format('Y-m-d H:i:s');
        $table = $em->getClassMetadata(Flight::class)->getTableName();
        $nightFlight = (int) $flight->isNightFlight();
        $fields = '(server_id, night_flight, theatre_id, mission_registry_id,pilot_id, plane_id, airfield_id, tour_id,side, start_flight_time)';
        $updateFields = 'server_id=VALUES(server_id),night_flight=VALUES(night_flight),theatre_id=VALUES(theatre_id), mission_registry_id=VALUES(mission_registry_id), pilot_id=VALUES(pilot_id), plane_id=VALUES(plane_id), airfield_id=VALUES(airfield_id), tour_id=VALUES(tour_id), side=VALUES(side), start_flight_time=VALUES(start_flight_time)';
        $server = $flight->getServer()->getId();
        $missionRegistry = $flight->getRegisteredMission()->getId();
        $pilot = $flight->getPilot()->getId();
        $plane = $flight->getPlane()->getId();
        $airfield = ($flight->getAirfield() !== null) ? $flight->getAirfield()->getId() : 'NULL';
        $theatre = ($flight->getTheatre() !== null) ? $flight->getTheatre()->getId() : 'NULL';
        $tour = $flight->getTour()->getId();
        $side = $flight->getSide();
        $values = "({$server},'{$nightFlight}',{$theatre}, {$missionRegistry}, {$pilot}, {$plane}, {$airfield}, {$tour},'{$side}', '{$time}')";
        $query = "INSERT INTO `{$table}`{$fields} VALUES {$values} ON DUPLICATE KEY UPDATE {$updateFields};";
        $this->log('Running query: ' . $query, Logger::DEBUG, 'repository');
        try {
            $em->getConnection()->prepare($query)->execute();
            return true;
        } catch (DBALException $e) {
            $this->log($e->getMessage(),  Logger::CRITICAL, 'repository');
            return false;
        }
    }

    public function clearFlights(Pilot $pilot) : bool
    {
        try {
            $this->getEntityManager()->createQuery('DELETE FROM App:Flight flight WHERE flight.pilot = :pilot')
                ->setParameter('pilot', $pilot)->execute();
            return true;
        } catch (Exception $e) {
            $this->log($e->getMessage() . "File: {$e->getFile()}, line: {$e->getLine()}", Logger::ALERT, 'repository');
            return false;
        }

    }

    public function markFlightAsCombat(Pilot $pilot)
    {
        $flight = $this->findOneBy([
            'pilot' => $pilot
        ]);

        if (empty($flight)) {
            return false;
        }

        if ($flight->isCombatFlight()) {
            return true;
        }

        $flight->setCombatFlight(true);

        try {
            $this->getEntityManager()->persist($flight);
            $this->getEntityManager()->flush();
            return true;
        } catch (ORMException $e) {
            $this->log($e->getMessage() . "File: {$e->getFile()}, line: {$e->getLine()}", Logger::ALERT, 'repository');
            return false;
        }
    }

    public function isBadWeatherFlight(Flight $flight)
    {
        $limit = $flight->getPlane()->getWeatherLimit();
        if (empty($limit)) {
            return false;
        }

        if (empty($flight->getRegisteredMission())) {
            return false;
        }

        $currentClouds = $flight->getRegisteredMission()->getCloudsBase() && 10000;
        $currentFogVisibility = $flight->getRegisteredMission()->getFogVisibility();
        $cloudsThickness = $flight->getRegisteredMission()->getCloudsThickness();
        $cloudsDensity = $flight->getRegisteredMission()->getCloudsDensity();
        $isFog = $flight->getRegisteredMission()->isFog();

        if ($flight->isNightFlight()) {
            $visibilityLimit = $limit->getVisibilityNight();
            $cloudsLimit = $limit->getVisibilityNight();
        } else {
            $visibilityLimit = $limit->getVisibilityDay();
            $cloudsLimit = $limit->getCloudsBaseDay();
        }

        if ($currentClouds < $cloudsLimit && $cloudsDensity >= 8) {
            return true;
        }

        if ($isFog && $currentFogVisibility < $visibilityLimit && $cloudsDensity >= 8) {
            return true;
        }

        return false;
    }
}
