<?php

namespace App\Entity;

use App\Includes\Timestamp;
use App\Repository\StreakRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * DcsTemporaryStreaks
 *
 * @ORM\Table(name="streaks")
 * @ORM\Entity(repositoryClass="App\Repository\StreakRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Streak
{
    use Timestamp;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @var bool
     * @ORM\Column(name="is_air", type="boolean", options={"default": false})
     */
    protected $air;
    /**
     * @var bool
     * @ORM\Column(name="is_current", type="boolean", options={"default": false})
     */
    protected $current;
    /**
     * @ORM\ManyToOne(targetEntity="Pilot", inversedBy="streaks")
     * @ORM\JoinColumn(name="pilot_id", referencedColumnName="id")
     */
    private $pilot;
    /**
     * @ORM\ManyToOne(targetEntity="Server", inversedBy="tempStreaks")
     * @ORM\JoinColumn(name="server_id", referencedColumnName="id")
     */
    private $server;
    /**
     * @var Tour
     * @ORM\ManyToOne(targetEntity="Tour")
     * @ORM\JoinColumn(name="tour_id", referencedColumnName="id", nullable=true)
     */
    private $tour;
    /**
     * @var int
     *
     * @ORM\Column(name="streak", type="integer", options={"default":0})
     */
    private $streak = 0;

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
     * Set streak
     *
     * @param integer $streak
     *
     * @return Streak
     */
    public function setStreak($streak)
    {
        $this->streak = $streak;

        return $this;
    }

    /**
     * Get streak
     *
     * @return integer
     */
    public function getStreak()
    {
        return $this->streak;
    }

    /**
     * Set pilot
     *
     * @param Pilot $pilot
     *
     * @return Streak
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
     * @return Streak
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
     * @return Tour|null
     */
    public function getTour(): ?Tour
    {
        return $this->tour;
    }

    /**
     * @param Tour $tour
     */
    public function setTour(Tour $tour): void
    {
        $this->tour = $tour;
    }

    /**
     * @return bool
     */
    public function isAir(): bool
    {
        return $this->air;
    }

    /**
     * @param bool $air
     */
    public function setAir(bool $air): void
    {
        $this->air = $air;
    }

    /**
     * @return bool
     */
    public function isCurrent(): bool
    {
        return $this->current;
    }

    /**
     * @param bool $current
     */
    public function setCurrent(bool $current): void
    {
        $this->current = $current;
    }
}
