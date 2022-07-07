<?php

namespace App\Entity;

use App\Repository\TournamentRequestRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TournamentRequestRepository::class)
 * @ORM\Table(name="tournaments_requests")
 */
class TournamentRequest
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Tournament::class, inversedBy="tournamentRequests")
     * @ORM\JoinColumn(nullable=false)
     */
    private $tournament;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $surname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=6)
     */
    private $code;

    /**
     * @ORM\ManyToOne(targetEntity=Plane::class, inversedBy="tournamentRequests")
     * @ORM\JoinColumn(nullable=false)
     */
    private $aircraft;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $squad;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $squadImage;

    /**
     * @ORM\Column(type="decimal", precision=2, scale=0, nullable=true)
     */
    private $desiredTailNumber;

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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getAircraft(): ?Plane
    {
        return $this->aircraft;
    }

    public function setAircraft(?Plane $aircraft): self
    {
        $this->aircraft = $aircraft;

        return $this;
    }

    public function getSquad(): ?string
    {
        return $this->squad;
    }

    public function setSquad(string $squad): self
    {
        $this->squad = $squad;

        return $this;
    }

    public function getSquadImage(): ?string
    {
        return $this->squadImage;
    }

    public function setSquadImage(?string $squadImage): self
    {
        $this->squadImage = $squadImage;

        return $this;
    }

    public function getDesiredTailNumber(): ?string
    {
        return $this->desiredTailNumber;
    }

    public function setDesiredTailNumber(?string $desiredTailNumber): self
    {
        $this->desiredTailNumber = $desiredTailNumber;

        return $this;
    }
}
