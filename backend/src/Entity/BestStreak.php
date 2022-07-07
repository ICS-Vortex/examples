<?php

namespace App\Entity;

use App\Repository\StreakRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * DcsBestStreaks
 *
 * @ORM\Table(name="best_streaks")
 * @ORM\Entity(repositoryClass="App\Repository\BestStreakRepository")
 */
class BestStreak
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
     * @var string
     *
     * @ORM\Column(name="streak_type", type="string", length=32, nullable=false)
     */
    protected $streakType;
    /**
     * @ORM\ManyToOne(targetEntity="Pilot", inversedBy="bestStreaks")
     * @ORM\JoinColumn(name="pilot_id", referencedColumnName="id")
     */
    public $pilot;
    /**
     * @ORM\ManyToOne(targetEntity="Server", inversedBy="bestStreaks")
     * @ORM\JoinColumn(name="server_id", referencedColumnName="id")
     */
    public $server;
    /**
     * @var int
     *
     * @ORM\Column(name="streak", type="integer")
     */
    private $streak;

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
     * @return BestStreak
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
     * @return BestStreak
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
     * @return BestStreak
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
     * @return string
     */
    public function getStreakType()
    {
        return $this->streakType;
    }

    /**
     * @param string $streakType
     */
    public function setStreakType($streakType)
    {
        $this->streakType = $streakType;
    }


}
