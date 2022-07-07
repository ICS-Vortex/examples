<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * DcsCurrentFlights
 *
 * @ORM\Table(name="flights")
 * @ORM\Entity(repositoryClass="App\Repository\FlightRepository")
 */
class Flight
{
    public const RED = 'RED';
    public const BLUE = 'BLUE';

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups({"api_online"})
     */
    private $id;
    /**
     * @ORM\ManyToOne(targetEntity="Server", inversedBy="currentFlights")
     * @ORM\JoinColumn(name="server_id", referencedColumnName="id", nullable=false)
     */
    public $server;
    /**
     * @ORM\ManyToOne(targetEntity="Theatre")
     * @ORM\JoinColumn(name="theatre_id", referencedColumnName="id", nullable=true)
     */
    public $theatre;
    /**
     * @ORM\ManyToOne(targetEntity="MissionRegistry", inversedBy="currentFlights")
     * @ORM\JoinColumn(name="mission_registry_id", referencedColumnName="id", nullable=false)
     */
    private $registeredMission;
    /**
     * @ORM\ManyToOne(targetEntity="Pilot")
     * @ORM\JoinColumn(name="pilot_id", referencedColumnName="id", unique=true, nullable=false)
     * @Groups({"api_online"})
     */
    public $pilot;
    /**
     * @ORM\ManyToOne(targetEntity="Plane")
     * @ORM\JoinColumn(name="plane_id", referencedColumnName="id", nullable=false)
     * @Groups({"api_online"})
     */
    private $plane;
    /**
     * @var string
     *
     * @ORM\Column(name="side", type="string", nullable=false, options={"default":"RED"})
     * @Groups({"api_online"})
     */
    private $side = self::RED;
    /**
     * @ORM\ManyToOne(targetEntity="Tour")
     * @ORM\JoinColumn(name="tour_id", referencedColumnName="id", nullable=false)
     */
    public $tour;
    /**
     * @var boolean
     * @ORM\Column(name="started", type="boolean", options={"default":false})
     * @Groups({"api_online"})
     */
    private $started = false;
    /**
     * @var DateTime
     *
     * @ORM\Column(name="start_flight_time", type="datetime", nullable=true)
     * @Groups({"api_online"})
     */
    private $startFlightTime;
    /**
     * @ORM\ManyToOne(targetEntity="Airfield")
     * @ORM\JoinColumn(name="airfield_id", referencedColumnName="id", nullable=true)
     * @Groups({"api_online"})
     */
    public $airfield;
    /**
     * @var boolean
     * @ORM\Column(name="combat_flight", type="boolean", options={"default":false})
     * @Groups({"api_online"})
     */
    private $combatFlight = false;
    /**
     * @var boolean
     * @ORM\Column(name="night_flight", type="boolean", options={"default":false})
     * @Groups({"api_online"})
     */
    private $nightFlight = false;
    /**
     * @var boolean
     * @ORM\Column(name="bad_weather_flight", type="boolean", options={"default":false})
     * @Groups({"api_online"})
     */
    private $badWeatherFlight = false;
    /**
     * @var boolean
     * @ORM\Column(name="group_flight", type="boolean", options={"default":false})
     * @Groups({"api_online"})
     */
    private $groupFlight = false;
    /**
     * @var boolean
     * @ORM\Column(name="emergency_flight", type="boolean", options={"default":false})
     * @Groups({"api_online"})
     */
    private $emergencyFlight = false;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set side
     *
     * @param string $side
     *
     * @return Flight
     */
    public function setSide($side)
    {
        $this->side = $side;

        return $this;
    }

    /**
     * Get side
     *
     * @return string
     */
    public function getSide()
    {
        return $this->side;
    }

    /**
     * Set startFlightTime
     *
     * @param DateTime $startFlightTime
     *
     * @return Flight
     */
    public function setStartFlightTime($startFlightTime)
    {
        $this->startFlightTime = $startFlightTime;

        return $this;
    }

    /**
     * Get startFlightTime
     *
     * @return DateTime
     */
    public function getStartFlightTime()
    {
        return $this->startFlightTime;
    }

    /**
     * Set server
     *
     * @param Server $server
     *
     * @return Flight
     */
    public function setServer(Server $server = null)
    {
        $this->server = $server;

        return $this;
    }

    /**
     * Get server
     *
     * @return Server
     */
    public function getServer()
    {
        return $this->server;
    }

    /**
     * Set registeredMission
     *
     * @param MissionRegistry $registeredMission
     *
     * @return Flight
     */
    public function setRegisteredMission(MissionRegistry $registeredMission = null)
    {
        $this->registeredMission = $registeredMission;

        return $this;
    }

    /**
     * Get registeredMission
     *
     * @return MissionRegistry
     */
    public function getRegisteredMission()
    {
        return $this->registeredMission;
    }

    /**
     * Set pilot
     *
     * @param Pilot $pilot
     *
     * @return Flight
     */
    public function setPilot(Pilot $pilot = null)
    {
        $this->pilot = $pilot;

        return $this;
    }

    /**
     * Get pilot
     *
     * @return Pilot
     */
    public function getPilot()
    {
        return $this->pilot;
    }

    /**
     * Set plane
     *
     * @param Plane $plane
     *
     * @return Flight
     */
    public function setPlane(Plane $plane = null)
    {
        $this->plane = $plane;

        return $this;
    }

    /**
     * Get plane
     *
     * @return Plane
     */
    public function getPlane()
    {
        return $this->plane;
    }

    /**
     * Set tour
     *
     * @param Tour|null $tour
     *
     * @return Flight
     */
    public function setTour(Tour $tour = null)
    {
        $this->tour = $tour;

        return $this;
    }

    /**
     * Get tour
     *
     * @return Tour
     */
    public function getTour()
    {
        return $this->tour;
    }

    /**
     * Set airfield
     *
     * @param Airfield|null $airfield
     *
     * @return Flight
     */
    public function setAirfield(Airfield $airfield = null)
    {
        $this->airfield = $airfield;

        return $this;
    }

    /**
     * Get airfield
     *
     * @return Airfield|null
     */
    public function getAirfield() : ?Airfield
    {
        return $this->airfield;
    }

    public function __toString() : string
    {
        return '#'.$this->id;
    }

    /**
     * @return mixed
     */
    public function getTheatre()
    {
        return $this->theatre;
    }

    /**
     * @param mixed $theatre
     */
    public function setTheatre($theatre): void
    {
        $this->theatre = $theatre;
    }

    /**
     * @return bool
     */
    public function isNightFlight(): bool
    {
        return $this->nightFlight;
    }

    /**
     * @param bool $nightFlight
     */
    public function setNightFlight(bool $nightFlight): void
    {
        $this->nightFlight = $nightFlight;
    }

    /**
     * @return bool
     */
    public function isCombatFlight(): bool
    {
        return $this->combatFlight;
    }

    /**
     * @param bool $combatFlight
     */
    public function setCombatFlight(bool $combatFlight): void
    {
        $this->combatFlight = $combatFlight;
    }

    /**
     * @return bool
     */
    public function isBadWeatherFlight(): bool
    {
        return $this->badWeatherFlight;
    }

    /**
     * @param bool $badWeatherFlight
     */
    public function setBadWeatherFlight(bool $badWeatherFlight): void
    {
        $this->badWeatherFlight = $badWeatherFlight;
    }

    /**
     * @return bool
     */
    public function isGroupFlight(): bool
    {
        return $this->groupFlight;
    }

    /**
     * @param bool $groupFlight
     */
    public function setGroupFlight(bool $groupFlight): void
    {
        $this->groupFlight = $groupFlight;
    }

    /**
     * @return bool
     */
    public function isStarted(): bool
    {
        return $this->started;
    }

    /**
     * @param bool $started
     */
    public function setStarted(bool $started): void
    {
        $this->started = $started;
    }

    /**
     * @return bool
     */
    public function isEmergencyFlight(): bool
    {
        return $this->emergencyFlight;
    }

    /**
     * @param bool $emergencyFlight
     */
    public function setEmergencyFlight(bool $emergencyFlight): void
    {
        $this->emergencyFlight = $emergencyFlight;
    }
}
