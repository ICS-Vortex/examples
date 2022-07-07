<?php

namespace App\Entity;

use App\Repository\SortieRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Table(name="sorties", indexes={
 *     @ORM\Index(name="pilots_sorties_ranking_idx", columns={"landing_airfield_id", "tour_id", "server_id"}),
 *     @ORM\Index(name="pilots_ranking_sorties_idx", columns={"total_time"}),
 * })
 * @ORM\Entity(repositoryClass="App\Repository\SortieRepository")
 */
class Sortie
{
    const RED = 'RED';
    const BLUE = 'BLUE';

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups({"api_sorties", "api_mission"})
     */
    private $id;
    /**
     * @ORM\ManyToOne(targetEntity="Pilot", inversedBy="sorties")
     * @ORM\JoinColumn(name="pilot_id", referencedColumnName="id")
     * @Groups({"api_sorties"})
     */
    public $pilot;
    /**
     * @ORM\ManyToOne(targetEntity="Server", inversedBy="flightHours")
     * @ORM\JoinColumn(name="server_id", referencedColumnName="id")
     * @Groups({"api_sorties"})
     */
    public $server;
    /**
     * @ORM\ManyToOne(targetEntity="Theatre")
     * @ORM\JoinColumn(name="theatre_id", referencedColumnName="id", nullable=true)
     */
    public $theatre;
    /**
     * @ORM\ManyToOne(targetEntity="MissionRegistry", cascade={"remove"}, inversedBy="sorties")
     * @ORM\JoinColumn(name="mission_registry_id", referencedColumnName="id")
     */
    private $registeredMission;
    /**
     * @ORM\ManyToOne(targetEntity="Tour")
     * @ORM\JoinColumn(name="tour_id", referencedColumnName="id")
     */
    public $tour;
    /**
     * @ORM\ManyToOne(targetEntity="Plane", inversedBy="flights")
     * @ORM\JoinColumn(name="plane_id", referencedColumnName="id", nullable=false)
     * @Groups({"api_sorties"})
     */
    private $plane;
    /**
     * @var string
     *
     * @ORM\Column(name="side", type="string")
     * @Groups({"api_sorties", "api_mission"})
     */
    private $side = self::RED;
    /**
     * @var DateTime
     *
     * @ORM\Column(name="start_flight", type="datetime")
     * @Groups({"api_sorties", "api_mission"})
     */
    private $startFlight;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="end_flight", type="datetime")
     * @Groups({"api_sorties", "api_mission"})
     */
    private $endFlight;

    /**
     * @var int
     *
     * @ORM\Column(name="total_time", type="integer")
     * @Groups({"api_sorties", "api_mission"})
     */
    private $totalTime;
    /**
     * @ORM\ManyToOne(targetEntity="Airfield")
     * @ORM\JoinColumn(name="takeoff_airfield_id", referencedColumnName="id")
     * @Groups({"api_sorties"})
     */
    public $takeoffFrom;
    /**
     * @ORM\ManyToOne(targetEntity="Airfield")
     * @ORM\JoinColumn(name="landing_airfield_id", referencedColumnName="id")
     * @Groups({"api_sorties"})
     */
    public $landingAt;
    /**
     * @var string
     *
     * @ORM\Column(name="status", nullable=true, type="string")
     * @Groups({"api_sorties", "api_mission"})
     */
    private $status = SortieRepository::STATUS_AIRFIELD;
    /**
     * @var boolean
     * @ORM\Column(name="night_flight", type="boolean", options={"default":false})
     * @Groups({"api_sorties"})
     */
    private $nightFlight = false;
    /**
     * @var boolean
     * @ORM\Column(name="combat_flight", type="boolean", options={"default":false})
     * @Groups({"api_sorties"})
     */
    private $combatFlight = false;
    /**
     * @var boolean
     * @ORM\Column(name="bad_weather_flight", type="boolean", options={"default":false})
     * @Groups({"api_sorties"})
     */
    private $badWeatherFlight = false;
    /**
     * @var boolean
     * @ORM\Column(name="group_flight", type="boolean", options={"default":false})
     * @Groups({"api_sorties"})
     */
    private $groupFlight = false;
    /**
     * @var boolean
     * @ORM\Column(name="emergency_flight", type="boolean", options={"default":false})
     * @Groups({"api_sorties"})
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
     * @return Sortie
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
     * Set startFlight
     *
     * @param DateTime $startFlight
     *
     * @return Sortie
     */
    public function setStartFlight($startFlight)
    {
        $this->startFlight = $startFlight;

        return $this;
    }

    /**
     * Get startFlight
     *
     * @return DateTime
     */
    public function getStartFlight()
    {
        return $this->startFlight;
    }

    /**
     * Set endFlight
     *
     * @param DateTime $endFlight
     *
     * @return Sortie
     */
    public function setEndFlight($endFlight)
    {
        $this->endFlight = $endFlight;

        return $this;
    }

    /**
     * Get endFlight
     *
     * @return DateTime
     */
    public function getEndFlight()
    {
        return $this->endFlight;
    }

    /**
     * Set totalTime
     *
     * @param integer $totalTime
     *
     * @return Sortie
     */
    public function setTotalTime($totalTime)
    {
        $this->totalTime = $totalTime;

        return $this;
    }

    /**
     * Get totalTime
     *
     * @return integer
     */
    public function getTotalTime()
    {
        return $this->totalTime;
    }

    /**
     * Set status
     *
     * @param string $status
     *
     * @return Sortie
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set pilot
     *
     * @param Pilot $pilot
     *
     * @return Sortie
     */
    public function setPilot(Pilot $pilot)
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
     * Set server
     *
     * @param Server|null $server
     *
     * @return Sortie
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
     * @param MissionRegistry|null $registeredMission
     *
     * @return Sortie
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
     * Set tour
     *
     * @param Tour|null $tour
     *
     * @return Sortie
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
     * Set plane
     *
     * @param Plane $plane
     *
     * @return Sortie
     */
    public function setPlane(Plane $plane)
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
     * Set takeoffFrom
     *
     * @param Airfield|null $takeoffFrom
     *
     * @return Sortie
     */
    public function setTakeoffFrom(Airfield $takeoffFrom = null)
    {
        $this->takeoffFrom = $takeoffFrom;

        return $this;
    }

    /**
     * Get takeoffFrom
     *
     * @return Airfield
     */
    public function getTakeoffFrom()
    {
        return $this->takeoffFrom;
    }

    /**
     * Set landingAt
     *
     * @param Airfield|null $landingAt
     *
     * @return Sortie
     */
    public function setLandingAt(Airfield $landingAt = null)
    {
        $this->landingAt = $landingAt;

        return $this;
    }

    /**
     * Get landingAt
     *
     * @return Airfield
     */
    public function getLandingAt()
    {
        return $this->landingAt;
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
