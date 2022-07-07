<?php

namespace App\Entity;

use App\Repository\BanRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BanRepository::class)
 * @ORM\Table(name="banlist")
 */
class Ban
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @var Server
     * @ORM\ManyToOne(targetEntity="Server")
     * @ORM\JoinColumn(name="server_id", referencedColumnName="id")
     */
    public $server;
    /**
     * @var Pilot
     * @ORM\ManyToOne(targetEntity="Pilot", inversedBy="banRecords")
     * @ORM\JoinColumn(name="pilot_id", referencedColumnName="id")
     */
    public $pilot;
    /**
     * @var string
     *
     * @ORM\Column(name="ip_address", type="string")
     */
    protected $ipAddress;
    /**
     * @var string
     *
     * @ORM\Column(name="reason", type="string", nullable=true)
     */
    protected $reason;
    /**
     * @var DateTime
     * @ORM\Column(name="banned_from", type="datetime")
     */
    private $bannedFrom;
    /**
     * @var DateTime
     * @ORM\Column(name="banned_until", type="datetime")
     */
    private $bannedUntil;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Server
     */
    public function getServer(): Server
    {
        return $this->server;
    }

    /**
     * @param Server $server
     */
    public function setServer(Server $server): void
    {
        $this->server = $server;
    }

    /**
     * @return Pilot
     */
    public function getPilot(): Pilot
    {
        return $this->pilot;
    }

    /**
     * @param Pilot $pilot
     */
    public function setPilot(Pilot $pilot): void
    {
        $this->pilot = $pilot;
    }

    /**
     * @return string
     */
    public function getIpAddress(): string
    {
        return $this->ipAddress;
    }

    /**
     * @param string $ipAddress
     */
    public function setIpAddress(string $ipAddress): void
    {
        $this->ipAddress = $ipAddress;
    }

    /**
     * @return string
     */
    public function getReason(): string
    {
        return $this->reason;
    }

    /**
     * @param string $reason
     */
    public function setReason(string $reason): void
    {
        $this->reason = $reason;
    }

    /**
     * @return DateTime
     */
    public function getBannedFrom()
    {
        return $this->bannedFrom;
    }

    /**
     * @param mixed $bannedFrom
     */
    public function setBannedFrom($bannedFrom): void
    {
        $this->bannedFrom = $bannedFrom;
    }

    /**
     * @return DateTime
     */
    public function getBannedUntil()
    {
        return $this->bannedUntil;
    }

    /**
     * @param mixed $bannedUntil
     */
    public function setBannedUntil($bannedUntil): void
    {
        $this->bannedUntil = $bannedUntil;
    }
}
