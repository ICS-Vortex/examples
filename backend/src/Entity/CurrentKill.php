<?php

namespace App\Entity;

use App\Repository\CurrentKillRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * DcsCurrentMissionKills
 *
 * @ORM\Table(name="current_kills")
 * @ORM\Entity(repositoryClass="App\Repository\CurrentKillRepository")
 */
class CurrentKill
{
    const RED = 'RED';
    const BLUE = 'BLUE';

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @var string
     *
     * @ORM\Column(name="action", type="string", length=32, options={"default":"destroyed"})
     */
    private $action = CurrentKillRepository::ACTION_DESTROYED;
    /**
     * @ORM\ManyToOne(targetEntity="Server", inversedBy="currentKills")
     * @ORM\JoinColumn(name="server_id", referencedColumnName="id")
     */
    public $server;
    /**
     * @ORM\ManyToOne(targetEntity="MissionRegistry", inversedBy="currentKills")
     * @ORM\JoinColumn(name="mission_registry_id", referencedColumnName="id")
     */
    private $registeredMission;
    /**
     * @ORM\ManyToOne(targetEntity="Tour")
     * @ORM\JoinColumn(name="tour_id", referencedColumnName="id")
     */
    public $tour;
    /**
     * @var DateTime
     *
     * @ORM\Column(name="kill_time", type="datetime")
     */
    private $killTime;
    /**
     * @var string
     *
     * @ORM\Column(name="side", type="string", length=255, options={"default":"RED"})
     */
    private $side = self::RED;

    /**
     * @var int
     *
     * @ORM\Column(name="points", type="integer")
     */
    private $points;
    /**
     * @ORM\ManyToOne(targetEntity="Unit")
     * @ORM\JoinColumn(name="unit_id", referencedColumnName="id", nullable=true)
     */
    private $unit;

    /**
     * @ORM\ManyToOne(targetEntity="Pilot", inversedBy="currentKills")
     * @ORM\JoinColumn(name="pilot_id", referencedColumnName="id")
     */
    private $pilot;

    /**
     * @var Plane
     * @ORM\ManyToOne(targetEntity="Plane")
     * @ORM\JoinColumn(name="plane_id", referencedColumnName="id")
     */
    private $plane;

    /**
     * @var Pilot
     * @ORM\ManyToOne(targetEntity="Pilot")
     * @ORM\JoinColumn(name="victim_id", referencedColumnName="id", nullable=true)
     */
    public $victim;
    /**
     * @var Plane
     * @ORM\ManyToOne(targetEntity="Plane")
     * @ORM\JoinColumn(name="victim_plane_id", referencedColumnName="id", nullable=true)
     */
    private $victimPlane;
    /**
     * @var string
     *
     * @ORM\Column(name="victim_side", type="string")
     */
    private $victimSide;
    /**
     * @var boolean
     *
     * @ORM\Column(name="is_human", type="boolean", options={"default"=0})
     */
    private $isHuman = false;
    /**
     * @var boolean
     *
     * @ORM\Column(name="friendly_fire", type="boolean", options={"default"=0})
     */
    private $friendlyFire = false;
    /**
     * @var boolean
     *
     * @ORM\Column(name="is_ai", type="boolean", options={"default"=0})
     */
    private $isAi = false;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId() : ?int
    {
        return $this->id;
    }

    /**
     * Set killTime
     *
     * @param DateTime $killTime
     *
     * @return CurrentKill
     */
    public function setKillTime($killTime)
    {
        $this->killTime = $killTime;

        return $this;
    }

    /**
     * Get killTime
     *
     * @return DateTime
     */
    public function getKillTime()
    {
        return $this->killTime;
    }

    /**
     * Set side
     *
     * @param string $side
     *
     * @return CurrentKill
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
     * Set points
     *
     * @param integer $points
     *
     * @return CurrentKill
     */
    public function setPoints($points)
    {
        $this->points = $points;

        return $this;
    }

    /**
     * Get points
     *
     * @return integer
     */
    public function getPoints()
    {
        return $this->points;
    }

    /**
     * Set server
     *
     * @param Server $server
     *
     * @return CurrentKill
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
     * @return CurrentKill
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
     * @param Tour $tour
     *
     * @return CurrentKill
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
     * Set unit
     *
     * @param Unit $unit
     *
     * @return CurrentKill
     */
    public function setUnit(Unit $unit = null)
    {
        $this->unit = $unit;

        return $this;
    }

    /**
     * Get unit
     *
     * @return Unit
     */
    public function getUnit()
    {
        return $this->unit;
    }

    /**
     * Set pilot
     *
     * @param Pilot $pilot
     *
     * @return CurrentKill
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
     * @return string
     */
    public function getAction(): string
    {
        return $this->action;
    }

    /**
     * @param string $action
     */
    public function setAction(string $action): void
    {
        $this->action = $action;
    }

    /**
     * @return Plane
     */
    public function getPlane(): ?Plane
    {
        return $this->plane;
    }

    /**
     * @param Plane $plane
     */
    public function setPlane(Plane $plane): void
    {
        $this->plane = $plane;
    }

    /**
     * @return Pilot
     */
    public function getVictim(): ?Pilot
    {
        return $this->victim;
    }

    /**
     * @param Pilot $victim
     */
    public function setVictim(Pilot $victim): void
    {
        $this->victim = $victim;
    }

    /**
     * @return Plane
     */
    public function getVictimPlane(): ?Plane
    {
        return $this->victimPlane;
    }

    /**
     * @param Plane $victimPlane
     */
    public function setVictimPlane(Plane $victimPlane): void
    {
        $this->victimPlane = $victimPlane;
    }

    /**
     * @return string
     */
    public function getVictimSide(): ?string
    {
        return $this->victimSide;
    }

    /**
     * @param string $victimSide
     */
    public function setVictimSide($victimSide): void
    {
        $this->victimSide = $victimSide;
    }

    /**
     * @return bool
     */
    public function isHuman(): bool
    {
        return $this->isHuman;
    }

    /**
     * @param bool $isHuman
     */
    public function setIsHuman(bool $isHuman): void
    {
        $this->isHuman = $isHuman;
    }

    /**
     * @return bool
     */
    public function isFriendlyFire(): bool
    {
        return $this->friendlyFire;
    }

    /**
     * @param bool $friendlyFire
     */
    public function setFriendlyFire(bool $friendlyFire): void
    {
        $this->friendlyFire = $friendlyFire;
    }

    /**
     * @return bool
     */
    public function isAi(): bool
    {
        return $this->isAi;
    }

    /**
     * @param bool $isAi
     */
    public function setIsAi(bool $isAi): void
    {
        $this->isAi = $isAi;
    }
}
