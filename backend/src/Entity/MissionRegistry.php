<?php

namespace App\Entity;

use App\Helper\Helper;
use App\Repository\MissionRegistryRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\ArrayShape;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * MissionRegistry
 *
 * @ORM\Table(name="mission_registries")
 * @ORM\Entity(repositoryClass="App\Repository\MissionRegistryRepository")
 */
class MissionRegistry
{
    public const RED = 'RED';
    public const BLUE = 'BLUE';
    public const DRAW = 'DRAW';

    /**
     * @var ?int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups({"api_mission", "api_tournaments"})
     */
    private $id;
    /**
     * @var Theatre
     * @ORM\ManyToOne(targetEntity="Theatre")
     * @ORM\JoinColumn(name="theatre_id", referencedColumnName="id", nullable=true)
     * @Groups({"api_mission", "api_tournaments"})
     */
    private $theatre;
    /**
     * @var DateTime
     *
     * @ORM\Column(name="start_time", type="datetime")
     * @Groups({"api_mission", "api_tournaments"})
     */
    private $start;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="end_time", type="datetime", nullable=true)
     * @Groups({"api_mission", "api_tournaments"})
     */
    private $end;
    /**
     * @var string
     *
     * @ORM\Column(name="winner", type="string", nullable=true)
     * @Groups({"api_mission", "api_tournaments"})
     */
    private $winner = MissionRegistryRepository::DRAW;
    /**
     * @ORM\Column(name="finished", type="boolean",nullable=true, options={"default" = 0})
     * @Groups({"api_mission", "api_tournaments"})
     */
    private $finished = false;
    /**
     * @var Server
     * @ORM\ManyToOne(targetEntity="Server", inversedBy="missionRegistry")
     * @ORM\JoinColumn(name="server_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $server;
    /**
     * @ORM\ManyToOne(targetEntity="Tour", inversedBy="missionRegistries")
     * @ORM\JoinColumn(name="tour_id", referencedColumnName="id")
     */
    private $tour;
    /**
     * @ORM\ManyToOne(targetEntity="Mission")
     * @ORM\JoinColumn(name="mission_id", referencedColumnName="id")
     * @Groups({"api_mission", "api_tournaments"})
     */
    private $mission;
    /**
     * @ORM\OneToMany(targetEntity="Kill", mappedBy="registeredMission", orphanRemoval=true)
     * @Groups({"api_mission"})
     */
    private $kills;
    /**
     * @ORM\OneToMany(targetEntity="Sortie", mappedBy="registeredMission", orphanRemoval=true)
     * @Groups({"api_mission"})
     */
    private $sorties;
    /**
     * @ORM\OneToMany(targetEntity="Event", mappedBy="registeredMission", orphanRemoval=true)
     * @Groups({"api_mission"})
     */
    private $events;
    /**
     * @ORM\OneToMany(targetEntity="Dogfight", mappedBy="registeredMission", orphanRemoval=true)
     * @Groups({"api_mission"})
     */
    private $dogfights;
    /**
     * @ORM\OneToMany(targetEntity="CurrentKill", mappedBy="registeredMission", orphanRemoval=true)
     */
    private $currentKills;
    /**
     * @ORM\OneToMany(targetEntity="Flight", mappedBy="registeredMission", orphanRemoval=true)
     */
    private $currentFlights;

    // WEATHER \\

    /**
     * @var ?integer
     *
     * @ORM\Column(name="atmosphere_type", type="integer", nullable=true)
     * @Groups({"api_mission", "api_tournaments"})
     */
    private $atmosphereType;
    /**
     * @var ?int
     *
     * @ORM\Column(name="groundTurbulence", type="integer", nullable=true)
     * @Groups({"api_mission", "api_tournaments"})
     */
    private $groundTurbulence;
    /**
     * @var boolean
     *
     * @ORM\Column(name="fog", type="boolean", options={"default":false})
     * @Groups({"api_mission", "api_tournaments"})
     */
    private $fog = false;
    /**
     * @var ?int
     *
     * @ORM\Column(name="wind_speed_at_8000", type="integer", nullable=true)
     * @Groups({"api_mission", "api_tournaments"})
     */
    private $windSpeedAt8000;
    /**
     * @var ?int
     *
     * @ORM\Column(name="wind_direction_at_8000", type="integer", nullable=true)
     * @Groups({"api_mission", "api_tournaments"})
     */
    private $windDirectionAt8000;

    /**
     * @var ?int
     *
     * @ORM\Column(name="wind_speed_at_2000", type="integer", nullable=true)
     * @Groups({"api_mission", "api_tournaments"})
     */
    private $windSpeedAt2000;
    /**
     * @var ?int
     *
     * @ORM\Column(name="wind_direction_at_2000", type="integer", nullable=true)
     * @Groups({"api_mission", "api_tournaments"})
     */
    private $windDirectionAt2000;

    /**
     * @var ?int
     *
     * @ORM\Column(name="wind_speed_at_ground", type="integer", nullable=true)
     * @Groups({"api_mission", "api_tournaments"})
     */
    private $windSpeedAtGround;
    /**
     * @var ?int
     *
     * @ORM\Column(name="wind_direction_at_ground", type="integer", nullable=true)
     * @Groups({"api_mission", "api_tournaments"})
     */
    private $windDirectionAtGround;
    /**
     * @var ?int
     *
     * @ORM\Column(name="temperature", type="integer", nullable=true)
     * @Groups({"api_mission", "api_tournaments"})
     */
    private $temperature;
    /**
     * @var ?int
     *
     * @ORM\Column(name="weather_type", type="integer", nullable=true)
     * @Groups({"api_mission", "api_tournaments"})
     */
    private $weatherType;

    /**
     * @var ?int
     *
     * @ORM\Column(name="qnh", type="integer", nullable=true)
     * @Groups({"api_mission", "api_tournaments"})
     */
    private $qnh;

    /**
     * @var string
     *
     * @ORM\Column(name="weather_name", type="string", nullable=true)
     * @Groups({"api_mission", "api_tournaments"})
     */
    private $weatherName;

    /**
     * @var ?int
     *
     * @ORM\Column(name="fog_thickness", type="integer", nullable=true)
     * @Groups({"api_mission", "api_tournaments"})
     */
    private $fogThickness;
    /**
     * @var ?int
     *
     * @ORM\Column(name="fog_visibility", type="integer", nullable=true)
     * @Groups({"api_mission", "api_tournaments"})
     */
    private $fogVisibility;
    /**
     * @var ?int
     *
     * @ORM\Column(name="visibility_distance", type="integer", nullable=true)
     * @Groups({"api_mission", "api_tournaments"})
     */
    private $visibilityDistance;
    /**
     * @var ?int
     *
     * @ORM\Column(name="dust_density", type="integer", nullable=true)
     * @Groups({"api_mission", "api_tournaments"})
     */
    private $dustDensity;
    /**
     * @var boolean
     *
     * @ORM\Column(name="dust", type="boolean", options={"default":false})
     * @Groups({"api_mission", "api_tournaments"})
     */
    private $dust = false;

    /**
     * @var ?int
     *
     * @ORM\Column(name="clouds_thickness", type="integer", nullable=true)
     * @Groups({"api_mission", "api_tournaments"})
     */
    private $cloudsThickness;
    /**
     * @var ?int
     *
     * @ORM\Column(name="clouds_density", type="integer", nullable=true)
     * @Groups({"api_mission", "api_tournaments"})
     */
    private $cloudsDensity;
    /**
     * @var ?int
     *
     * @ORM\Column(name="clouds_base", type="integer", nullable=true)
     * @Groups({"api_mission", "api_tournaments"})
     */
    private $cloudsBase;
    /**
     * @var ?int
     *
     * @ORM\Column(name="clouds_ip_recptns", type="integer", nullable=true)
     * @Groups({"api_mission", "api_tournaments"})
     */
    private $cloudsIpRecptns;

    /**
     * @ORM\OneToMany(targetEntity=RaceRun::class, mappedBy="missionRegistry", orphanRemoval=true)
     */
    private $raceRuns;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->kills = new ArrayCollection();
        $this->sorties = new ArrayCollection();
        $this->events = new ArrayCollection();
        $this->dogfights = new ArrayCollection();
        $this->currentKills = new ArrayCollection();
        $this->currentFlights = new ArrayCollection();
        $this->raceRuns = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return ?integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set start
     *
     * @param DateTime $start
     *
     * @return MissionRegistry
     */
    public function setStart($start)
    {
        $this->start = $start;

        return $this;
    }

    /**
     * Get start
     *
     * @return DateTime
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * Set end
     *
     * @param DateTime $end
     *
     * @return MissionRegistry
     */
    public function setEnd($end)
    {
        $this->end = $end;

        return $this;
    }

    /**
     * Get end
     *
     * @return DateTime
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * Set winner
     *
     * @param string $winner
     *
     * @return MissionRegistry
     */
    public function setWinner($winner)
    {
        $this->winner = $winner;

        return $this;
    }

    /**
     * Get winner
     *
     * @return string
     */
    public function getWinner()
    {
        return $this->winner;
    }

    /**
     * Set finished
     *
     * @param boolean $finished
     *
     * @return void
     */
    public function setFinished($finished) : void
    {
        $this->finished = $finished;
    }

    /**
     * Get finished
     *
     * @return boolean
     */
    public function getFinished() : bool
    {
        return $this->finished;
    }

    public function isFinished() : bool
    {
        return $this->finished;
    }

    /**
     * Set server
     *
     * @param Server $server
     *
     * @return MissionRegistry
     */
    public function setServer(Server $server)
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
     * @param Tour $tour
     *
     * @return MissionRegistry
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
     * Set mission
     *
     * @param Mission $mission
     *
     * @return MissionRegistry
     */
    public function setMission(Mission $mission)
    {
        $this->mission = $mission;

        return $this;
    }

    /**
     * Get mission
     *
     * @return Mission
     */
    public function getMission()
    {
        return $this->mission;
    }

    /**
     * Add kill
     *
     * @param Kill $kill
     *
     * @return MissionRegistry
     */
    public function addKill(Kill $kill)
    {
        $this->kills[] = $kill;

        return $this;
    }

    /**
     * Remove kill
     *
     * @param Kill $kill
     */
    public function removeKill(Kill $kill)
    {
        $this->kills->removeElement($kill);
    }

    /**
     * Get kills
     *
     * @return Collection
     */
    public function getKills()
    {
        return $this->kills;
    }

    /**
     * Add flightHour
     *
     * @param Sortie $sortie
     *
     * @return MissionRegistry
     */
    public function addSortie(Sortie $sortie)
    {
        $this->sorties[] = $sortie;

        return $this;
    }

    /**
     * Remove flightHour
     *
     * @param Sortie $flightHour
     */
    public function removeSortie(Sortie $flightHour)
    {
        $this->sorties->removeElement($flightHour);
    }

    /**
     * Get sorties
     *
     * @return Collection
     */
    public function getSorties()
    {
        return $this->sorties;
    }

    /**
     * Add event
     *
     * @param Event $event
     *
     * @return MissionRegistry
     */
    public function addEvent(Event $event)
    {
        $this->events[] = $event;

        return $this;
    }

    /**
     * Remove event
     *
     * @param Event $event
     */
    public function removeEvent(Event $event)
    {
        $this->events->removeElement($event);
    }

    /**
     * Get events
     *
     * @return Collection
     */
    public function getEvents()
    {
        return $this->events;
    }

    /**
     * Add dogfight
     *
     * @param Dogfight $dogfight
     *
     * @return MissionRegistry
     */
    public function addDogfight(Dogfight $dogfight)
    {
        $this->dogfights[] = $dogfight;

        return $this;
    }

    /**
     * Remove dogfight
     *
     * @param Dogfight $dogfight
     */
    public function removeDogfight(Dogfight $dogfight)
    {
        $this->dogfights->removeElement($dogfight);
    }

    /**
     * Get dogfights
     *
     * @return Collection
     */
    public function getDogfights()
    {
        return $this->dogfights;
    }

    /**
     * Add currentKill
     *
     * @param CurrentKill $currentKill
     *
     * @return MissionRegistry
     */
    public function addCurrentKill(CurrentKill $currentKill)
    {
        $this->currentKills[] = $currentKill;

        return $this;
    }

    /**
     * Remove currentKill
     *
     * @param CurrentKill $currentKill
     */
    public function removeCurrentKill(CurrentKill $currentKill)
    {
        $this->currentKills->removeElement($currentKill);
    }

    /**
     * Get currentKills
     *
     * @return Collection
     */
    public function getCurrentKills()
    {
        return $this->currentKills;
    }

    /**
     * Add currentFlight
     *
     * @param Flight $currentFlight
     *
     * @return MissionRegistry
     */
    public function addCurrentFlight(Flight $currentFlight)
    {
        $this->currentFlights[] = $currentFlight;

        return $this;
    }

    /**
     * Remove currentFlight
     *
     * @param Flight $currentFlight
     */
    public function removeCurrentFlight(Flight $currentFlight)
    {
        $this->currentFlights->removeElement($currentFlight);
    }

    /**
     * Get currentFlights
     *
     * @return Collection
     */
    public function getCurrentFlights()
    {
        return $this->currentFlights;
    }

    public function __toString() : string
    {
        return ($this->mission !== null) ? $this->mission->getName() : '#'.$this->id;
    }

    /**
     * @return Theatre
     */
    public function getTheatre(): ?Theatre
    {
        return $this->theatre;
    }

    /**
     * @param ?Theatre $theatre
     */
    public function setTheatre(?Theatre $theatre): void
    {
        $this->theatre = $theatre;
    }

    /**
     * @return ?int
     */
    public function getAtmosphereType(): ?int
    {
        return $this->atmosphereType;
    }

    /**
     * @param ?int $atmosphereType
     */
    public function setAtmosphereType(?int $atmosphereType): void
    {
        $this->atmosphereType = $atmosphereType;
    }

    /**
     * @return ?int
     */
    public function getGroundTurbulence(): ?int
    {
        return $this->groundTurbulence;
    }

    /**
     * @param ?int $groundTurbulence
     */
    public function setGroundTurbulence(?int $groundTurbulence): void
    {
        $this->groundTurbulence = $groundTurbulence;
    }

    /**
     * @return bool
     */
    public function isFog(): bool
    {
        return $this->fog;
    }

    /**
     * @param bool $fog
     */
    public function setFog(bool $fog): void
    {
        $this->fog = $fog;
    }

    /**
     * @return ?int
     */
    public function getWindSpeedAt8000(): ?int
    {
        return $this->windSpeedAt8000;
    }

    /**
     * @param ?int $windSpeedAt8000
     */
    public function setWindSpeedAt8000(?int $windSpeedAt8000): void
    {
        $this->windSpeedAt8000 = $windSpeedAt8000;
    }

    /**
     * @return ?int
     */
    public function getWindDirectionAt8000(): ?int
    {
        return $this->windDirectionAt8000;
    }

    /**
     * @param ?int $windDirectionAt8000
     */
    public function setWindDirectionAt8000(?int $windDirectionAt8000): void
    {
        $this->windDirectionAt8000 = $windDirectionAt8000;
    }

    /**
     * @return ?int
     */
    public function getWindSpeedAt2000(): ?int
    {
        return $this->windSpeedAt2000;
    }

    /**
     * @param ?int $windSpeedAt2000
     */
    public function setWindSpeedAt2000(?int $windSpeedAt2000): void
    {
        $this->windSpeedAt2000 = $windSpeedAt2000;
    }

    /**
     * @return ?int
     */
    public function getWindDirectionAt2000(): ?int
    {
        return $this->windDirectionAt2000;
    }

    /**
     * @param ?int $windDirectionAt2000
     */
    public function setWindDirectionAt2000(?int $windDirectionAt2000): void
    {
        $this->windDirectionAt2000 = $windDirectionAt2000;
    }

    /**
     * @return ?int
     */
    public function getWindSpeedAtGround(): ?int
    {
        return $this->windSpeedAtGround;
    }

    /**
     * @param ?int $windSpeedAtGround
     */
    public function setWindSpeedAtGround(?int $windSpeedAtGround): void
    {
        $this->windSpeedAtGround = $windSpeedAtGround;
    }

    /**
     * @return ?int
     */
    public function getWindDirectionAtGround(): ?int
    {
        return $this->windDirectionAtGround;
    }

    /**
     * @param ?int $windDirectionAtGround
     */
    public function setWindDirectionAtGround(?int $windDirectionAtGround): void
    {
        $this->windDirectionAtGround = $windDirectionAtGround;
    }

    /**
     * @return ?int
     */
    public function getTemperature(): ?int
    {
        return $this->temperature;
    }

    /**
     * @param ?int $temperature
     */
    public function setTemperature(?int $temperature): void
    {
        $this->temperature = $temperature;
    }

    /**
     * @return ?int
     */
    public function getWeatherType(): ?int
    {
        return $this->weatherType;
    }

    /**
     * @param ?int $weatherType
     */
    public function setWeatherType(?int $weatherType): void
    {
        $this->weatherType = $weatherType;
    }

    /**
     * @return ?int
     */
    public function getQnh(): ?int
    {
        return $this->qnh;
    }

    /**
     * @param ?int $qnh
     */
    public function setQnh(?int $qnh): void
    {
        $this->qnh = $qnh;
    }

    /**
     * @return string
     */
    public function getWeatherName(): ?string
    {
        return $this->weatherName;
    }

    /**
     * @param string $weatherName
     */
    public function setWeatherName(?string $weatherName): void
    {
        $this->weatherName = $weatherName;
    }

    /**
     * @return ?int
     */
    public function getFogThickness(): ?int
    {
        return $this->fogThickness;
    }

    /**
     * @param ?int $fogThickness
     */
    public function setFogThickness(?int $fogThickness): void
    {
        $this->fogThickness = $fogThickness;
    }

    /**
     * @return ?int
     */
    public function getFogVisibility(): ?int
    {
        return $this->fogVisibility;
    }

    /**
     * @param ?int $fogVisibility
     */
    public function setFogVisibility(?int $fogVisibility): void
    {
        $this->fogVisibility = $fogVisibility;
    }

    /**
     * @return ?int
     */
    public function getVisibilityDistance(): ?int
    {
        return $this->visibilityDistance;
    }

    /**
     * @param ?int $visibilityDistance
     */
    public function setVisibilityDistance(?int $visibilityDistance): void
    {
        $this->visibilityDistance = $visibilityDistance;
    }

    /**
     * @return ?int
     */
    public function getDustDensity(): ?int
    {
        return $this->dustDensity;
    }

    /**
     * @param ?int $dustDensity
     */
    public function setDustDensity(?int $dustDensity): void
    {
        $this->dustDensity = $dustDensity;
    }

    /**
     * @return bool
     */
    public function isDust(): bool
    {
        return $this->dust;
    }

    /**
     * @param bool $dust
     */
    public function setDust(bool $dust): void
    {
        $this->dust = $dust;
    }

    /**
     * @return ?int
     */
    public function getCloudsThickness(): ?int
    {
        return $this->cloudsThickness;
    }

    /**
     * @param ?int $cloudsThickness
     */
    public function setCloudsThickness(?int $cloudsThickness): void
    {
        $this->cloudsThickness = $cloudsThickness;
    }

    /**
     * @return ?int
     */
    public function getCloudsDensity(): ?int
    {
        return $this->cloudsDensity;
    }

    /**
     * @param ?int $cloudsDensity
     */
    public function setCloudsDensity(?int $cloudsDensity): void
    {
        $this->cloudsDensity = $cloudsDensity;
    }

    /**
     * @return ?int
     */
    public function getCloudsBase(): ?int
    {
        return $this->cloudsBase;
    }

    /**
     * @param ?int $cloudsBase
     */
    public function setCloudsBase(?int $cloudsBase): void
    {
        $this->cloudsBase = $cloudsBase;
    }

    /**
     * @return ?int
     */
    public function getCloudsIpRecptns(): ?int
    {
        return $this->cloudsIpRecptns;
    }

    /**
     * @param ?int $cloudsIpRecptns
     */
    public function setCloudsIpRecptns(?int $cloudsIpRecptns): void
    {
        $this->cloudsIpRecptns = $cloudsIpRecptns;
    }

    #[ArrayShape(['RED' => "int", 'BLUE' => "int"])]
    public function getKillsCountBySide(): array
    {
        $red = 0;
        $blue = 0;
        /** @var Kill $kill */
        foreach ($this->kills as $kill) {
            if ($kill->getSide() === 'RED') {
                $red++;
            }
            if ($kill->getSide() === 'BLUE') {
                $blue++;
            }
        }
        return ['RED' => $red, 'BLUE' => $blue];
    }

    public function getSortiesCountsBySide(): array
    {
        $red = 0;
        $blue = 0;
        /** @var Sortie $sortie */
        foreach ($this->sorties as $sortie) {
            if ($sortie->getSide() === 'RED') {
                $red++;
            }
            if ($sortie->getSide() === 'BLUE') {
                $blue++;
            }
        }
        return ['RED' => $red, 'BLUE' => $blue];
    }

    public function getSortiesHoursBySide(): array
    {
        $red = 0;
        $blue = 0;
        /** @var Sortie $sortie */
        foreach ($this->sorties as $sortie) {
            if ($sortie->getSide() === 'RED') {
                $red += $sortie->getTotalTime();
            }
            if ($sortie->getSide() === 'BLUE') {
                $blue += $sortie->getTotalTime();
            }
        }
        return ['RED' => Helper::calculateFlightTime($red), 'BLUE' => Helper::calculateFlightTime($blue)];
    }

    public function getTotalSortiesTime()
    {
        $hours = 0;
        foreach ($this->sorties as $sortie) {
            $hours += $sortie->getTotalTime();
        }
        return Helper::calculateFlightTime($hours);
    }

    public function getDuration($asSeconds = false)
    {
        $start = $this->start;
        $end = $this->end;
        if (!$this->finished) {
            $end = $this->server->getLastActivity();
        }
        $difference = strtotime($end->format('Y-m-d H:i:s')) - strtotime($start->format('Y-m-d H:i:s'));
        if ($asSeconds) {
            return $difference;
        }
        return Helper::calculateFlightTime($difference);
    }

    #[ArrayShape(['RED' => "int", 'BLUE' => "int"])]
    public function getDogfightsBySide(): array
    {
        $red = 0;
        $blue = 0;
        /** @var Dogfight $dogfight */
        foreach ($this->dogfights as $dogfight) {
            if ($dogfight->getSide() === 'RED') {
                $red++;
            }
            if ($dogfight->getSide() === 'BLUE') {
                $blue++;
            }
        }
        return ['RED' => $red, 'BLUE' => $blue];
    }


    public function getPointsBySides(): array
    {
        $red = 0;
        $blue = 0;
        /** @var Dogfight $dogfight */
        foreach ($this->dogfights as $dogfight) {
            if ($dogfight->getSide() === 'RED') {
                $red += $dogfight->getPoints();
            }
            if ($dogfight->getSide() === 'BLUE') {
                $blue += $dogfight->getPoints();
            }
        }
        /** @var Kill $kill */
        foreach ($this->kills as $kill) {
            if ($kill->getSide() === 'RED') {
                $red += $kill->getPoints();
            }
            if ($kill->getSide() === 'BLUE') {
                $blue += $kill->getPoints();
            }
        }
        return ['RED' => $red, 'BLUE' => $blue];
    }

    /**
     * @return array
     */
    #[ArrayShape(['RED' => "array", 'BLUE' => "array"])]
    public function getUniqueFlightsBySides(): array
    {
        $redFlights = [];
        $blueFlights = [];
        /** @var Sortie $sortie */
        foreach ($this->sorties as $sortie) {
            if ($sortie->getSide() === 'RED') {
                $flights[] = $sortie->getPilot()->getId();
            }
            if ($sortie->getSide() === 'BLUE') {
                $flights[] = $sortie->getPilot()->getId();
            }
        }
        $redFlights = array_unique($redFlights, SORT_NUMERIC);
        $blueFlights = array_unique($blueFlights, SORT_NUMERIC);
        return ['RED' => count($redFlights), 'BLUE' => count($blueFlights)];
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
            $raceRun->setMissionRegistry($this);
        }

        return $this;
    }

    public function removeRaceRun(RaceRun $raceRun): self
    {
        if ($this->raceRuns->removeElement($raceRun)) {
            // set the owning side to null (unless already changed)
            if ($raceRun->getMissionRegistry() === $this) {
                $raceRun->setMissionRegistry(null);
            }
        }

        return $this;
    }
}
