<?php

namespace App\Entity;

use App\Includes\Timestamp;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * DcsPilotsDogfights
 *
 * @ORM\Table(name="dogfights", indexes={
 *     @ORM\Index(name="pilots_ranking_wins_idx", columns={"friendly", "in_air", "ai_id", "victim_id", "tour_id", "server_id"}),
 *     @ORM\Index(name="pilots_ranking_dogfights_idx", columns={"friendly", "in_air", "victim_id", "tour_id", "server_id"}),
 *     @ORM\Index(name="pilots_ranking_loses_idx", columns={"friendly", "victim_id", "tour_id", "server_id"}),
 *     @ORM\Index(name="missions_sessions_list", columns={"server_id", "tour_id", "pilot_side"}),
 * })
 * @ORM\Entity(repositoryClass="App\Repository\DogfightRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Dogfight
{
    use Timestamp;

    public const RED = 'RED';
    public const BLUE = 'BLUE';

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups({"api_dogfights", "api_mission"})
     */
    private $id;
    /**
     * @ORM\ManyToOne(targetEntity="Pilot", inversedBy="dogfights")
     * @ORM\JoinColumn(name="pilot_id", referencedColumnName="id")
     * @Groups({"api_dogfights"})
     */
    public $pilot;
    /**
     * @ORM\ManyToOne(targetEntity="Server", inversedBy="dogfights")
     * @ORM\JoinColumn(name="server_id", referencedColumnName="id")
     * @Groups({"api_dogfights"})
     */
    public $server;
    /**
     * @ORM\ManyToOne(targetEntity="MissionRegistry", inversedBy="dogfights")
     * @ORM\JoinColumn(name="mission_registry_id", referencedColumnName="id")
     */
    private $registeredMission;
    /**
     * @ORM\ManyToOne(targetEntity="Tour")
     * @ORM\JoinColumn(name="tour_id", referencedColumnName="id")
     */
    public $tour;
    /**
     * @ORM\ManyToOne(targetEntity="Plane", inversedBy="dogfights")
     * @ORM\JoinColumn(name="pilot_plane_id", referencedColumnName="id")
     * @Groups({"api_dogfights"})
     */
    private $plane;
    /**
     * @var string
     *
     * @ORM\Column(name="pilot_side", type="string")
     * @Groups({"api_dogfights", "api_mission"})
     */
    private $side = self::RED;
    /**
     * @var Pilot
     * @ORM\ManyToOne(targetEntity="Pilot")
     * @ORM\JoinColumn(name="victim_id", referencedColumnName="id", nullable=true)
     * @Groups({"api_dogfights"})
     */
    public $victim;
    /**
     * @ORM\ManyToOne(targetEntity="Plane", inversedBy="loses")
     * @ORM\JoinColumn(name="victim_plane_id", referencedColumnName="id", nullable=true)
     * @Groups({"api_dogfights"})
     */
    private $victimPlane;
    /**
     * @var string
     *
     * @ORM\Column(name="victim_side", type="string")
     * @Groups({"api_dogfights", "api_mission"})
     */
    private $victimSide = self::RED;
    /**
     * @var Unit $ai
     * @ORM\ManyToOne(targetEntity="Unit")
     * @ORM\JoinColumn(name="ai_id", referencedColumnName="id", nullable=true)
     * @Groups({"api_dogfights"})
     */
    private $ai;
    /**
     * @var boolean $pvp
     * @ORM\Column(name="is_pvp", type="boolean", options={"default": false})
     * @Groups({"api_dogfights", "api_mission"})
     */
    private $pvp = false;
    /**
     * @var boolean $inAir
     * @ORM\Column(name="in_air", type="boolean", options={"default": true})
     * @Groups({"api_dogfights", "api_mission"})
     */
    private $inAir = true;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="kill_time", type="datetime")
     * @Groups({"api_dogfights", "api_mission"})
     */
    private $killTime;

    /**
     * @var int
     *
     * @ORM\Column(name="friendly", type="boolean")
     * @Groups({"api_dogfights", "api_mission"})
     */
    private $friendly;
    /**
     * @var bool
     *
     * @ORM\Column(name="elo_calculated", type="boolean", options={"default": false})
     */
    private $eloCalculated = false;

    /**
     * @var int
     *
     * @ORM\Column(name="points", type="integer")
     * @Groups({"api_dogfights", "api_mission"})
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
     * @return Dogfight
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
     * Set victimSide
     *
     * @param string $victimSide
     *
     * @return Dogfight
     */
    public function setVictimSide($victimSide)
    {
        $this->victimSide = $victimSide;

        return $this;
    }

    /**
     * Get victimSide
     *
     * @return string
     */
    public function getVictimSide()
    {
        return $this->victimSide;
    }

    /**
     * Set killTime
     *
     * @param DateTime $killTime
     *
     * @return Dogfight
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
     * @return Dogfight
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
    public function getFriendly() : bool
    {
        return $this->friendly;
    }

    /**
     * Get friendly
     *
     * @return boolean
     */
    public function isFriendly() : bool
    {
        return $this->friendly;
    }

    /**
     * Set points
     *
     * @param integer $points
     *
     * @return Dogfight
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
     * @return Dogfight
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
     * @return Dogfight
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
     * @return Dogfight
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
     * @return Dogfight
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
     * @return Dogfight
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
     * Set victim
     *
     * @param Pilot $victim
     *
     * @return Dogfight
     */
    public function setVictim(Pilot $victim)
    {
        $this->victim = $victim;

        return $this;
    }

    /**
     * Get victim
     *
     * @return Pilot
     */
    public function getVictim()
    {
        return $this->victim;
    }

    /**
     * Set victimPlane
     *
     * @param Plane $victimPlane
     *
     * @return Dogfight
     */
    public function setVictimPlane(Plane $victimPlane = null)
    {
        $this->victimPlane = $victimPlane;

        return $this;
    }

    /**
     * Get victimPlane
     *
     * @return Plane
     */
    public function getVictimPlane()
    {
        return $this->victimPlane;
    }

    /**
     * @return Unit
     */
    public function getAi(): ?Unit
    {
        return $this->ai;
    }

    /**
     * @param Unit $ai
     */
    public function setAi(Unit $ai): void
    {
        $this->ai = $ai;
    }

    public function __toString() : string
    {
        return !empty($this->victim) ? $this->victim->getCallsign() : $this->ai->getName();
    }

    /**
     * @return bool
     */
    public function isEloCalculated(): bool
    {
        return $this->eloCalculated;
    }

    /**
     * @param bool $eloCalculated
     */
    public function setEloCalculated(bool $eloCalculated): void
    {
        $this->eloCalculated = $eloCalculated;
    }

    /**
     * @return bool
     */
    public function isPvp(): bool
    {
        return $this->pvp;
    }

    /**
     * @param bool $pvp
     */
    public function setPvp(bool $pvp): void
    {
        $this->pvp = $pvp;
    }

    /**
     * @return bool
     */
    public function isInAir(): bool
    {
        return $this->inAir;
    }

    /**
     * @param bool $inAir
     */
    public function setInAir(bool $inAir): void
    {
        $this->inAir = $inAir;
    }

    /**
     * @return bool
     */
    public function isValidEloDogfight(): bool
    {
        if ($this->isEloCalculated()) {
            return false;
        }

        if (empty($this->victim)) {
            return false;
        }

        if ($this->getFriendly()) {
            return false;
        }

        if ($this->side === $this->victimSide) {
            return false;
        }

        if (!$this->inAir) {
            return false;
        }

        return true;
    }
}
