<?php

namespace App\Entity;

use App\Repository\TournamentStageRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=TournamentStageRepository::class)
 * @ORM\Table(name="tournament_stages")
 */
class TournamentStage
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"api_tournaments"})
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"api_tournaments"})
     */
    private $start;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"api_tournaments"})
     */
    private $end;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     * @Groups({"api_tournaments"})
     */
    private $timeQuota;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"api_tournaments"})
     */
    private $position;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"api_tournaments"})
     */
    private $winners = 0;

    /**
     * @ORM\ManyToMany(targetEntity=Pilot::class, inversedBy="tournamentStages")
     */
    private $participants;

    /**
     * @ORM\ManyToOne(targetEntity=Tournament::class, inversedBy="stages")
     * @ORM\JoinColumn(nullable=false)
     */
    private $tournament;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"api_tournaments"})
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"api_tournaments"})
     */
    private $titleEn;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"api_tournaments"})
     */
    private $code;

    /**
     * @ORM\OneToMany(targetEntity=RaceRun::class, mappedBy="stage", orphanRemoval=true)
     */
    private $raceRuns;

    /**
     * @ORM\Column(type="boolean", options={"default": true})
     */
    private $hidden = true;

    #[Pure]
    public function __construct()
    {
        $this->participants = new ArrayCollection();
        $this->raceRuns = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStart(): ?DateTimeInterface
    {
        return $this->start;
    }

    public function setStart(DateTimeInterface $start): self
    {
        $this->start = $start;

        return $this;
    }

    public function getEnd(): ?DateTimeInterface
    {
        return $this->end;
    }

    public function setEnd(DateTimeInterface $end): self
    {
        $this->end = $end;

        return $this;
    }

    public function getTimeQuota(): ?string
    {
        return $this->timeQuota;
    }

    public function setTimeQuota(string $timeQuota): self
    {
        $this->timeQuota = $timeQuota;

        return $this;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(int $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function getWinners(): ?int
    {
        return $this->winners;
    }

    public function setWinners(int $winners): self
    {
        $this->winners = $winners;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getParticipants(): Collection
    {
        return $this->participants;
    }

    public function addParticipant(Pilot $participant): self
    {
        if (!$this->participants->contains($participant)) {
            $this->participants[] = $participant;
        }

        return $this;
    }

    public function removeParticipant(Pilot $participant): self
    {
        $this->participants->removeElement($participant);

        return $this;
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

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getTitleEn(): ?string
    {
        return $this->titleEn;
    }

    public function setTitleEn(string $titleEn): self
    {
        $this->titleEn = $titleEn;

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

    /**
     * @return Collection|RaceRun[]
     */
    public function getRaceRuns(): Collection
    {
        return $this->raceRuns;
    }

    public function addRaceRun(RaceRun $raceRun): self
    {
        if (!$this->raceRuns->contains($raceRun)) {
            $this->raceRuns[] = $raceRun;
            $raceRun->setStage($this);
        }

        return $this;
    }

    public function removeRaceRun(RaceRun $raceRun): self
    {
        if ($this->raceRuns->removeElement($raceRun)) {
            // set the owning side to null (unless already changed)
            if ($raceRun->getStage() === $this) {
                $raceRun->setStage(null);
            }
        }

        return $this;
    }

    public function isHidden(): ?bool
    {
        return $this->hidden;
    }

    public function setHidden(bool $hidden): self
    {
        $this->hidden = $hidden;

        return $this;
    }

    public function getRace($pilot): ?RaceRun
    {
        /** @var RaceRun $race */
        foreach ($this->raceRuns as $race) {
            if ($race->getPilot() === $pilot) {
                return $race;
            }
        }
        return null;
    }

    public function __toString()
    {
        return $this->title . ' | ' . $this->titleEn;
    }
}
