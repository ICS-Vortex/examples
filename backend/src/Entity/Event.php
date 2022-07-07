<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Table(name="events", indexes={
 *     @ORM\Index(name="pilots_events_ranking_idx", columns={"event", "tour_id", "server_id"})
 * })
 * @ORM\Entity(repositoryClass="App\Repository\EventRepository")
 */
class Event
{
    public const RED = 'RED';
    public const BLUE = 'BLUE';
    public const TAKEOFF = 'TAKEOFF';
    public const LANDING = 'LANDING';
    public const CRASHLANDING = 'CRASHLANDING';
    public const DEATH = 'DEATH';
    public const EJECT = 'EJECT';
    public const CRASH = 'CRASH';
    public const DISCONNECT = 'DISCONNECT';

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups({"api_open_servers", "api_mission"})
     */
    private $id;
    /**
     * @ORM\ManyToOne(targetEntity="Pilot", inversedBy="events", cascade={"remove"})
     * @ORM\JoinColumn(name="pilot_id", referencedColumnName="id")
     * @Groups({"api_open_servers"})
     */
    private $pilot;
    /**
     * @ORM\ManyToOne(targetEntity="Server", inversedBy="events")
     * @ORM\JoinColumn(name="server_id", referencedColumnName="id")
     */
    public $server;
    /**
     * @ORM\ManyToOne(targetEntity="MissionRegistry", inversedBy="events", cascade={"remove"})
     * @ORM\JoinColumn(name="mission_registry_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $registeredMission;
    /**
     * @ORM\ManyToOne(targetEntity="Tour")
     * @ORM\JoinColumn(name="tour_id", referencedColumnName="id")
     * @Groups({"api_open_servers"})
     */
    public $tour;
    /**
     * @var string
     *
     * @ORM\Column(name="event", type="string")
     * @Groups({"api_open_servers", "api_mission"})
     */
    private $event = self::TAKEOFF;
    /**
     * @var string
     *
     * @ORM\Column(name="side", type="string")
     * @Groups({"api_open_servers", "api_mission"})
     */
    private $side;
    /**
     * @ORM\ManyToOne(targetEntity="Plane", inversedBy="events")
     * @ORM\JoinColumn(name="plane_id", referencedColumnName="id", nullable=true)
     * @Groups({"api_open_servers"})
     */
    private $plane;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="event_time", type="datetime")
     * @Groups({"api_open_servers", "api_mission"})
     */
    private $eventTime;

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
     * Set event
     *
     * @param string $event
     *
     * @return Event
     */
    public function setEvent($event)
    {
        $this->event = $event;

        return $this;
    }

    /**
     * Get event
     *
     * @return string
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * Set side
     *
     * @param string $side
     *
     * @return Event
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
     * Set eventTime
     *
     * @param DateTime $eventTime
     *
     * @return Event
     */
    public function setEventTime($eventTime)
    {
        $this->eventTime = $eventTime;

        return $this;
    }

    /**
     * Get eventTime
     *
     * @return DateTime
     */
    public function getEventTime()
    {
        return $this->eventTime;
    }

    /**
     * Set pilot
     *
     * @param Pilot $pilot
     *
     * @return Event
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
     * @return Event
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
     * @return Event
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
     * @return Event
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
     * @return Event
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

    public function __toString() : string
    {
        return $this->event;
    }
}
