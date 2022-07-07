<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * DcsPilotsKills
 *
 * @ORM\Table(name="kills")
 * @ORM\Entity(repositoryClass="App\Repository\KillRepository")
 */
class Kill
{
    public const RED = 'RED';
    public const BLUE = 'BLUE';
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups({"api_mission"})
     */
    private $id;
    /**
     * @ORM\ManyToOne(targetEntity="Pilot", inversedBy="kills")
     * @ORM\JoinColumn(name="pilot_id", referencedColumnName="id")
     */
    public $pilot;
    /**
     * @ORM\ManyToOne(targetEntity="Server", inversedBy="kills")
     * @ORM\JoinColumn(name="server_id", referencedColumnName="id")
     */
    public $server;
    /**
     * @ORM\ManyToOne(targetEntity="MissionRegistry", inversedBy="kills")
     * @ORM\JoinColumn(name="mission_registry_id", referencedColumnName="id")
     */
    private $registeredMission;
    /**
     * @ORM\ManyToOne(targetEntity="Tour")
     * @ORM\JoinColumn(name="tour_id", referencedColumnName="id")
     */
    public $tour;
    /**
     * @ORM\ManyToOne(targetEntity="Plane", inversedBy="kills")
     * @ORM\JoinColumn(name="plane_id", referencedColumnName="id", nullable=true)
     */
    private $plane;
    /**
     * @var string
     *
     * @ORM\Column(name="side", type="string")
     * @Groups({"api_mission"})
     */
    private $side;
    /**
     * @var string
     *
     * @ORM\Column(name="target_side", type="string")
     * @Groups({"api_mission"})
     */
    private $targetSide;
    /**
     * @var Unit
     * @ORM\ManyToOne(targetEntity="Unit")
     * @ORM\JoinColumn(name="unit_id", referencedColumnName="id")
     */
    public $unit;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="kill_time", type="datetime")
     * @Groups({"api_mission"})
     */
    private $killTime;

    /**
     * @var int
     *
     * @ORM\Column(name="friendly", type="boolean", options={"default":0})
     */
    private $friendly = false;

    /**
     * @ORM\Column(name="ground_kill", type="boolean", nullable=true, options={"default" = 0})
     */
    private $groundKill = false;
    /**
     * @ORM\Column(name="sea_kill", type="boolean", nullable=true, options={"default" = 0})
     */
    private $seaKill = false;
    /**
     * @var int
     *
     * @ORM\Column(name="points", type="integer")
     * @Groups({"api_mission"})
     */
    private $points;

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
     * @return Kill
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
     * Set killTime
     *
     * @param DateTime $killTime
     *
     * @return Kill
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
     * Set friendly
     *
     * @param boolean $friendly
     *
     * @return Kill
     */
    public function setFriendly($friendly)
    {
        $this->friendly = $friendly;

        return $this;
    }

    /**
     * Get friendly
     *
     * @return boolean
     */
    public function getFriendly()
    {
        return $this->friendly;
    }

    /**
     * Set points
     *
     * @param integer $points
     *
     * @return Kill
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
     * Set pilot
     *
     * @param Pilot $pilot
     *
     * @return Kill
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
     * Set server
     *
     * @param Server $server
     *
     * @return Kill
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
     * @return Kill
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
     * @return Kill
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
     * @param Plane|null $plane
     *
     * @return Kill
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
     * Set unit
     *
     * @param Unit|null $unit
     *
     * @return Kill
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

    public function __toString() : string
    {
        return $this->unit->getName();
    }

    /**
     * @return bool
     */
    public function isGroundKill(): bool
    {
        return $this->groundKill;
    }

    /**
     * @param bool $groundKill
     */
    public function setGroundKill(bool $groundKill): void
    {
        $this->groundKill = $groundKill;
    }

    /**
     * @return bool
     */
    public function isSeaKill(): bool
    {
        return $this->seaKill;
    }

    /**
     * @param bool $seaKill
     */
    public function setSeaKill(bool $seaKill): void
    {
        $this->seaKill = $seaKill;
    }

    /**
     * @return string
     */
    public function getTargetSide(): string
    {
        return $this->targetSide;
    }

    /**
     * @param string $targetSide
     * @return Kill
     */
    public function setTargetSide(string $targetSide): Kill
    {
        $this->targetSide = $targetSide;
        return $this;
    }
}
