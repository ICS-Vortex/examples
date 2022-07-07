<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="visitors")
 * @ORM\Entity(repositoryClass="App\Repository\VisitorRepository")
 */
class Visitor
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @ORM\ManyToOne(targetEntity="Server", inversedBy="visitors")
     * @ORM\JoinColumn(name="server_id", referencedColumnName="id")
     */
    public $server;
    /**
     * @var MissionRegistry
     * @ORM\ManyToOne(targetEntity="App\Entity\MissionRegistry")
     * @ORM\JoinColumn(name="mission_registry_id", referencedColumnName="id")
     */
    public $missionRegistry;
    /**
     * @ORM\ManyToOne(targetEntity="Tour")
     * @ORM\JoinColumn(name="tour_id", referencedColumnName="id")
     */
    public $tour;
    /**
     * @ORM\ManyToOne(targetEntity="Pilot", inversedBy="visits")
     * @ORM\JoinColumn(name="pilot_id", referencedColumnName="id")
     */
    private $pilot;
    /**
     * @var DateTime
     *
     * @ORM\Column(name="enter_time", type="datetime")
     */
    private $enterTime;

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
     * Set enterTime
     *
     * @param DateTime $enterTime
     *
     * @return Visitor
     */
    public function setEnterTime($enterTime)
    {
        $this->enterTime = $enterTime;

        return $this;
    }

    /**
     * Get enterTime
     *
     * @return DateTime
     */
    public function getEnterTime()
    {
        return $this->enterTime;
    }

    /**
     * Set server
     *
     * @param Server|null $server
     *
     * @return Visitor
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
     * Set tour
     *
     * @param Tour|null $tour
     *
     * @return Visitor
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
     * Set pilot
     *
     * @param Pilot|null $pilot
     *
     * @return Visitor
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

    public function __toString(): string
    {
        return $this->getPilot()->getNickname();
    }

    /**
     * @return MissionRegistry
     */
    public function getMissionRegistry(): MissionRegistry
    {
        return $this->missionRegistry;
    }

    /**
     * @param MissionRegistry $missionRegistry
     */
    public function setMissionRegistry(MissionRegistry $missionRegistry): void
    {
        $this->missionRegistry = $missionRegistry;
    }
}
