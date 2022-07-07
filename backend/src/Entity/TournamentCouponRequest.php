<?php

namespace App\Entity;

use App\Includes\Timestamp;
use App\Repository\TournamentCouponRequestRepository;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=TournamentCouponRequestRepository::class)
 * @ORM\Table(name="tournament_coupon_requests")
 * @ORM\HasLifecycleCallbacks
 */
class TournamentCouponRequest
{
    use Timestamp;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"api_profile"})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Tournament::class, inversedBy="tournamentCouponRequests")
     * @ORM\JoinColumn(nullable=false)
     */
    private $tournament;

    /**
     * @ORM\ManyToOne(targetEntity=Server::class, inversedBy="tournamentCouponRequests")
     */
    private $server;

    /**
     * @ORM\ManyToOne(targetEntity=Pilot::class, inversedBy="tournamentCouponRequests")
     * @ORM\JoinColumn(nullable=false)
     */
    private $pilot;

    /**
     * @ORM\Column(type="boolean", options={"default": true})
     * @Groups({"api_profile"})
     */
    private $active = true;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $acceptTime;

    /**
     * @ORM\Column(type="boolean", options={"default": false})
     */
    private $transferred = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTournament(): ?Tournament
    {
        return $this->tournament;
    }

    public function setTournament(?Tournament $tournament): self
    {
        $this->tournament = $tournament;

        return $this;
    }

    public function getServer(): ?Server
    {
        return $this->server;
    }

    public function setServer(?Server $server): self
    {
        $this->server = $server;

        return $this;
    }

    public function getPilot(): ?Pilot
    {
        return $this->pilot;
    }

    public function setPilot(?Pilot $pilot): self
    {
        $this->pilot = $pilot;

        return $this;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function getAcceptTime(): ?DateTimeInterface
    {
        return $this->acceptTime;
    }

    public function setAcceptTime(?DateTimeInterface $acceptTime): self
    {
        $this->acceptTime = $acceptTime;

        return $this;
    }

    public function getTransferred(): ?bool
    {
        return $this->transferred;
    }

    public function setTransferred(bool $transferred): self
    {
        $this->transferred = $transferred;

        return $this;
    }
}
