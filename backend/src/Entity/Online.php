<?php

namespace App\Entity;

use App\Helper\Helper;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Online
 *
 * @ORM\Table(name="online", uniqueConstraints={
 *     @ORM\UniqueConstraint(name="unique_pilot_and_server",columns={"pilot_id", "server_id"})
 * },
 * indexes={
 *    @ORM\Index(name="idx_server_pilot", columns={"server_id", "pilot_id"})
 * })
 * @ORM\Entity(repositoryClass="App\Repository\OnlineRepository")
 */
class Online
{
    public const RED = 'RED';
    public const BLUE = 'BLUE';
    public const SPECTATOR = 'SPECTATOR';

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups({"api_open_servers", "api_tournaments"})
     */
    private $id;
    /**
     * @ORM\ManyToOne(targetEntity="Server", inversedBy="pilotsOnline")
     * @ORM\JoinColumn(name="server_id", referencedColumnName="id")
     */
    public $server;
    /**
     * @ORM\ManyToOne(targetEntity="Pilot", inversedBy="onlineRecord")
     * @ORM\JoinColumn(name="pilot_id", referencedColumnName="id", unique=true)
     * @Groups({"api_open_servers", "api_tournaments"})
     */
    private $pilot;
    /**
     * @ORM\ManyToOne(targetEntity="Plane")
     * @ORM\JoinColumn(name="plane_id", referencedColumnName="id", nullable=true)
     * @Groups({"api_open_servers", "api_tournaments"})
     */
    private $plane;
    /**
     * @var string
     *
     * @ORM\Column(name="side", type="string")
     * @Groups({"api_open_servers", "api_tournaments"})
     */
    private $side;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="enter_time", type="datetime")
     * @Groups({"api_open_servers", "api_tournaments"})
     */
    private $enterTime;
    /**
     * @var string
     *
     * @ORM\Column(name="frequencies", type="text", nullable=true)
     * @Groups({"api_open_servers", "api_tournaments"})
     */
    private $frequencies;
    /**
     * @var string
     *
     * @ORM\Column(name="coordinates", type="text", nullable=true)
     * @Groups({"api_open_servers", "api_tournaments"})
     */
    private $coordinates;

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
     * @return Online
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
     * Set enterTime
     *
     * @param DateTime $enterTime
     *
     * @return Online
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
     * @param Server $server
     *
     * @return Online
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
     * Set pilot
     *
     * @param Pilot $pilot
     *
     * @return Online
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
     * @return Online
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
     * @param string $type
     * @return mixed
     */
    public function getFrequencies($type = 'array')
    {
        $result = null;
        switch ($type) {
            case 'array':
                $result = empty($this->frequencies) ? [] : json_decode($this->frequencies, true);
                break;
            case 'json':
                $result = empty($this->frequencies) ? '[]' : $this->frequencies;
                break;
            case 'html':
                if(empty($this->frequencies)){
                    break;
                }
                $result .= '<ul>';
                foreach (json_decode($this->frequencies, true) as $radio) {
                    $frequency = Helper::formatFrequency($radio['frequency']);
                    $result .= "<li>{$radio['radio']} - {$frequency}</li>";
                }
                $result .= '</ul>';
                break;
            case 'text':
                if(empty($this->frequencies)){
                    break;
                }
                foreach (json_decode($this->frequencies, true) as $radio) {
                    $frequency = Helper::formatFrequency($radio['frequency']);
                    $result .= "{$radio['radio']} - {$frequency},";
                }
                $result = substr($result, 0, -1);
                break;
            default:
                break;
        }

        return $result;
    }

    /**
     * @param string $frequencies
     */
    public function setFrequencies($frequencies)
    {
        $this->frequencies = $frequencies;
    }

    /**
     * @return string
     */
    public function getCoordinates()
    {
        return $this->coordinates;
    }

    /**
     * @param string $coordinates
     */
    public function setCoordinates($coordinates)
    {
        $this->coordinates = $coordinates;
    }

    public function __toString() : string
    {
        return '#'.$this->id;
    }
}
