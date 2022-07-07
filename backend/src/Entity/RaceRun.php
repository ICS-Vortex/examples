<?php

namespace App\Entity;

use App\Includes\Timestamp;
use App\Repository\RaceRunRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=RaceRunRepository::class)
 * @ORM\Table(name="race_runs")
 * @ORM\HasLifecycleCallbacks
 */
class RaceRun
{
    use Timestamp;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Tour::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $tour;

    /**
     * @var float
     * @ORM\Column(type="decimal", precision=10, scale=2)
     * @Groups({"api_races"})
     */
    private $time;

    /**
     * @ORM\ManyToOne(targetEntity=Tournament::class, inversedBy="raceRuns")
     * @ORM\JoinColumn(nullable=true)
     */
    private $tournament;

    /**
     * @ORM\ManyToOne(targetEntity=TournamentStage::class, inversedBy="raceRuns")
     * @ORM\JoinColumn(nullable=true)
     */
    private $stage;

    /**
     * @ORM\ManyToOne(targetEntity=AircraftClass::class, inversedBy="raceRuns")
     * @ORM\JoinColumn(nullable=true)
     */
    private $aircraftClass;

    /**
     * @ORM\ManyToOne(targetEntity=Pilot::class, inversedBy="raceRuns")
     * @ORM\JoinColumn(nullable=false)
     */
    private $pilot;

    /**
     * @ORM\ManyToOne(targetEntity=Plane::class, inversedBy="raceRuns")
     * @ORM\JoinColumn(nullable=false)
     */
    private $plane;

    /**
     * @ORM\ManyToOne(targetEntity=Server::class, inversedBy="raceRuns")
     * @ORM\JoinColumn(nullable=false)
     */
    private $server;

    /**
     * @ORM\ManyToOne(targetEntity=MissionRegistry::class, inversedBy="raceRuns")
     * @ORM\JoinColumn(nullable=false)
     */
    private $missionRegistry;

    /**
     * @ORM\Column(type="datetime")
     */
    private $raceTime;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTour(): ?Tour
    {
        return $this->tour;
    }

    public function setTour(?Tour $tour): self
    {
        $this->tour = $tour;

        return $this;
    }


    public function getTime(): ?float
    {
        return $this->time;
    }

    public function setTime(float $time): self
    {
        $this->time = $time;

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

    public function getStage(): ?TournamentStage
    {
        return $this->stage;
    }

    public function setStage(?TournamentStage $stage): self
    {
        $this->stage = $stage;

        return $this;
    }

    public function getAircraftClass(): ?AircraftClass
    {
        return $this->aircraftClass;
    }

    public function setAircraftClass(?AircraftClass $aircraftClass): self
    {
        $this->aircraftClass = $aircraftClass;

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

    public function getPlane(): ?Plane
    {
        return $this->plane;
    }

    public function setPlane(?Plane $plane): self
    {
        $this->plane = $plane;

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

    public function getMissionRegistry(): ?MissionRegistry
    {
        return $this->missionRegistry;
    }

    public function setMissionRegistry(?MissionRegistry $missionRegistry): self
    {
        $this->missionRegistry = $missionRegistry;

        return $this;
    }

    public function getRaceTime(): ?DateTimeInterface
    {
        return $this->raceTime;
    }

    public function setRaceTime(DateTimeInterface $raceTime): self
    {
        $this->raceTime = $raceTime;

        return $this;
    }

    public function __construct()
    {
        $this->raceTime = new DateTime();
    }
}
