<?php

namespace App\Entity;

use App\Includes\Timestamp;
use App\Repository\PilotRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * DcsPlanes
 *
 * @ORM\Table(name="planes")
 * @ORM\Entity(repositoryClass="App\Repository\PlaneRepository")
 * @Vich\Uploadable
 * @ORM\HasLifecycleCallbacks()
 */
class Plane
{
    use Timestamp;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups({"api_open_servers", "api_manuals", "api_online", "api_sorties", "api_dogfights","api_profile", "api_races", "api_tournaments"})
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     * @Groups({"api_open_servers", "api_manuals", "api_online", "api_sorties", "api_dogfights","api_profile", "api_races", "api_tournaments"})
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=255, nullable=true)
     * @Groups({"api_open_servers", "api_manuals", "api_sorties", "api_dogfights","api_profile", "api_races", "api_tournaments"})
     */
    private $image;
    /**
     * @Vich\UploadableField(mapping="planes_images", fileNameProperty="image")
     * @var File
     */
    private $imageFile;
    /**
     * @var string
     *
     * @ORM\Column(name="red_image", type="string", length=255, nullable=true)
     * @Groups({"api_open_servers", "api_sorties", "api_dogfights","api_profile", "api_races", "api_tournaments"})
     */
    private $redImage;
    /**
     * @Vich\UploadableField(mapping="planes_images", fileNameProperty="redImage")
     * @var File
     */
    private $redImageFile;
    /**
     * @var string
     *
     * @ORM\Column(name="blue_image", type="string", length=255, nullable=true)
     * @Groups({"api_open_servers", "api_sorties", "api_dogfights","api_profile", "api_races", "api_tournaments"})
     */
    private $blueImage;
    /**
     * @Vich\UploadableField(mapping="planes_images", fileNameProperty="blueImage")
     * @var File
     */
    private $blueImageFile;
    /**
     * @var bool
     *
     * @ORM\Column(name="is_mod", type="boolean", options={"default" : 1})
     * @Groups({"api_open_servers", "api_tournaments", "api_manuals", "api_online", "api_sorties", "api_dogfights","api_profile"})
     *
     */
    private $mod = true;
    /**
     * @var bool
     *
     * @ORM\Column(name="is_helicopter", type="boolean", options={"default" : 0})
     * @Groups({"api_open_servers", "api_tournaments", "api_manuals", "api_online", "api_sorties", "api_dogfights","api_profile", "api_races"})
     */
    private $helicopter = false;
    /**
     * @var WeatherLimit
     * @ORM\ManyToOne(targetEntity="App\Entity\WeatherLimit")
     * @ORM\JoinColumn(nullable=true)
     */
    private $weatherLimit;
    /**
     * @ORM\OneToMany(targetEntity="Kill", mappedBy="plane")
     */
    private $kills;
    /**
     * @ORM\OneToMany(targetEntity="Sortie", mappedBy="plane")
     */
    private $flights;
    /**
     * @ORM\OneToMany(targetEntity="Dogfight", mappedBy="plane")
     */
    private $dogfights;
    /**
     * @ORM\OneToMany(targetEntity="Dogfight", mappedBy="victimPlane")
     */
    private $loses;
    /**
     * @ORM\OneToMany(targetEntity="Event", mappedBy="plane")
     */
    private $events;

    /**
     * @ORM\OneToMany(targetEntity=TournamentRequest::class, mappedBy="aircraft", orphanRemoval=true)
     */
    private $tournamentRequests;

    /**
     * @ORM\ManyToMany(targetEntity=AircraftClass::class, mappedBy="aircrafts")
     */
    private $aircraftClasses;

    /**
     * @ORM\OneToMany(targetEntity=RaceRun::class, mappedBy="plane", orphanRemoval=true)
     */
    private $raceRuns;

    public function __toString() : string
    {
        return $this->name;
    }

    /**
     * Get kills
     *
     * @param Server $server
     * @return array|ArrayCollection
     */
    public function getKills(Server $server = null)
    {
        if(empty($server)){
            return $this->kills;
        }
        $resultArray = array();

        $kills = $this->kills;
        foreach ($kills as $kill){
            if($server === $kill->getServer()){
                $resultArray[] = $kill;
            }
        }

        return $resultArray;
    }

    /**
     * Get flights
     *
     * @param Server $server
     * @return array|Collection
     */
    public function getFlights(Server $server = null)
    {
        if(empty($server)){
            return $this->flights;
        }

        $resultArray = array();
        $flights = $this->flights;

        foreach ($flights as $flight){
            if($server === $flight->getServer()){
                $resultArray[] = $flight;
            }
        }

        return $resultArray;
    }

    /**
     * Get dogfights
     *
     * @param Server $server
     * @return array|Collection
     */
    public function getDogfights(Server $server = null)
    {
        $dogfights = $this->dogfights;
        $resultArray = array();
        if(empty($server)){
            return $dogfights;
        }

        foreach ($dogfights as $dogfight){
            if($server === $dogfight->getServer()){
                $resultArray[] = $dogfight;
            }
        }
        return $resultArray;
    }

    /**
     * Get events
     *
     * @param Server $server
     * @return array|Collection
     */
    public function getEvents(Server $server = null)
    {
        $events = $this->events;
        $returnArray = array();
        foreach ($events as $event){
            if($event->getServer() === $server){
                $returnArray[] = $event;
            }
        }
        return $returnArray;
    }

    /**
     * Returns total flight time for server(if passed)
     * @param Server|null $server
     * @return string
     */
    public function getTotalFlightTime(Server $server = null){
        $total = 0;
        if(!empty($this->flights)){
            foreach ($this->flights as $flight){
                if(!empty($server) && $server !== $flight->getServer()){
                    continue;
                }
                $time = $flight->getTotalTime();
                $total += $time;
            }
        }

        return PilotRepository::calculateFlightTime($total);
    }

    /**
     * Get loses
     *
     * @param Server $server|null
     * @return array|Collection
     */
    public function getLoses(Server $server = null)
    {
        $returnArray = array();
        $loses = $this->loses;
        if(empty($server)){
            return $loses;
        }
        foreach ($loses as $lose){
            if($server === $lose->getServer()){
                $returnArray[] = $lose;
            }
        }
        return $returnArray;
    }

    /**
     * Returns info about events for plane and server (if passed)
     * @param Server $server
     * @return array
     */
    public function getEventsInfo(Server $server = null){
        $events = array();
        $eventsReturn = array();
        foreach ($this->events as $event){
            $events[] = $event;
        }
        if(!empty($events)){
            $eventsReturn[Event::TAKEOFF] = array_filter($events, function($ev) use($server){
                if(!empty($server)){
                    if($ev->getServer() === $server){
                        return $ev->getEvent() == Event::TAKEOFF;
                    }else{
                        return;
                    }
                }
                return $ev->getEvent() == Event::TAKEOFF;
            });
            $eventsReturn[Event::LANDING] = array_filter($events, function($ev) use($server){
                if(!empty($server)){
                    if($ev->getServer() === $server){
                        return $ev->getEvent() == Event::LANDING;
                    }else{
                        return;
                    }
                }
                return $ev->getEvent() == Event::LANDING;
            });
            $eventsReturn[Event::CRASH] = array_filter($events, function($ev) use($server){
                if(!empty($server)){
                    if($ev->getServer() === $server){
                        return $ev->getEvent() == Event::CRASH;
                    }else{
                        return;
                    }
                }
                return $ev->getEvent() == Event::CRASH;
            });
            $eventsReturn[Event::DEATH] = array_filter($events, function($ev) use($server){
                if(!empty($server)){
                    if($ev->getServer() === $server){
                        return $ev->getEvent() == Event::CRASH;
                    }else{
                        return;
                    }
                }
                return $ev->getEvent() == Event::DEATH;
            });
            $eventsReturn[Event::EJECT] = array_filter($events, function($ev) use($server){
                if(!empty($server)){
                    if($ev->getServer() === $server){
                        return $ev->getEvent() == Event::EJECT;
                    }else{
                        return;
                    }
                }
                return $ev->getEvent() == Event::EJECT;
            });
            $eventsReturn[Event::DISCONNECT] = array_filter($events, function($ev) use($server){
                if(!empty($server)){
                    if($ev->getServer() === $server){
                        return $ev->getEvent() == Event::DISCONNECT;
                    }else{
                        return;
                    }
                }
                return $ev->getEvent() == Event::DISCONNECT;
            });
            $eventsReturn[Event::CRASHLANDING] = array_filter($events, function($ev) use($server){
                if(!empty($server)){
                    if($ev->getServer() === $server){
                        return $ev->getEvent() == Event::CRASHLANDING;
                    }else{
                        return;
                    }
                }
                return $ev->getEvent() == Event::CRASHLANDING;
            });
        }

        return $eventsReturn;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->kills = new ArrayCollection();
        $this->flights = new ArrayCollection();
        $this->dogfights = new ArrayCollection();
        $this->loses = new ArrayCollection();
        $this->events = new ArrayCollection();
        $this->tournamentRequests = new ArrayCollection();
        $this->aircraftClasses = new ArrayCollection();
        $this->raceRuns = new ArrayCollection();
    }

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
     * Set name
     *
     * @param string $name
     *
     * @return Plane
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Plane
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set image
     *
     * @param string $image
     *
     * @return Plane
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Add kill
     *
     * @param Kill $kill
     *
     * @return Plane
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
     * Add flight
     *
     * @param Sortie $flight
     *
     * @return Plane
     */
    public function addFlight(Sortie $flight)
    {
        $this->flights[] = $flight;

        return $this;
    }

    /**
     * Remove flight
     *
     * @param Sortie $flight
     */
    public function removeFlight(Sortie $flight)
    {
        $this->flights->removeElement($flight);
    }

    /**
     * Add dogfight
     *
     * @param Dogfight $dogfight
     *
     * @return Plane
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
     * Add lose
     *
     * @param Dogfight $lose
     *
     * @return Plane
     */
    public function addLose(Dogfight $lose)
    {
        $this->loses[] = $lose;

        return $this;
    }

    /**
     * Remove lose
     *
     * @param Dogfight $lose
     */
    public function removeLose(Dogfight $lose)
    {
        $this->loses->removeElement($lose);
    }

    /**
     * Add event
     *
     * @param Event $event
     *
     * @return Plane
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
     * @return string
     */
    public function getRedImage()
    {
        return $this->redImage;
    }

    /**
     * @param string $redImage
     */
    public function setRedImage($redImage)
    {
        $this->redImage = $redImage;
    }

    /**
     * @return string
     */
    public function getBlueImage()
    {
        return $this->blueImage;
    }

    /**
     * @param string $blueImage
     */
    public function setBlueImage($blueImage)
    {
        $this->blueImage = $blueImage;
    }

    public function setImageFile(File $image = null)
    {
        $this->imageFile = $image;

        if ($image) {
            // if 'updatedAt' is not defined in your entity, use another property
            $this->updatedAt = new DateTime('now');
        }
    }

    public function getImageFile()
    {
        return $this->imageFile;
    }

    public function setRedImageFile(File $image = null)
    {
        $this->redImageFile = $image;

        if ($image) {
            $this->updatedAt = new DateTime('now');
        }
    }

    public function getRedImageFile()
    {
        return $this->redImageFile;
    }

    public function setBlueImageFile(File $image = null)
    {
        $this->blueImageFile = $image;
        if ($image) {
            $this->updatedAt = new DateTime('now');
        }
    }

    public function getBlueImageFile()
    {
        return $this->blueImageFile;
    }

    /**
     * @return bool
     */
    public function isMod(): bool
    {
        return $this->mod;
    }

    /**
     * @return bool
     */
    public function getMod(): bool
    {
        return $this->mod;
    }


    /**
     * @param bool $mod
     */
    public function setMod(bool $mod): void
    {
        $this->mod = $mod;
    }

    /**
     * @return ?WeatherLimit
     */
    public function getWeatherLimit(): ?WeatherLimit
    {
        return $this->weatherLimit;
    }

    /**
     * @param ?WeatherLimit $weatherLimit
     */
    public function setWeatherLimit(?WeatherLimit $weatherLimit): void
    {
        $this->weatherLimit = $weatherLimit;
    }

    /**
     * @return bool
     */
    public function isHelicopter(): bool
    {
        return $this->helicopter;
    }

    /**
     * @param bool $helicopter
     */
    public function setHelicopter(bool $helicopter): void
    {
        $this->helicopter = $helicopter;
    }

    /**
     * @return Collection|TournamentRequest[]
     */
    public function getTournamentRequests(): Collection
    {
        return $this->tournamentRequests;
    }

    public function addTournamentRequest(TournamentRequest $tournamentRequest): self
    {
        if (!$this->tournamentRequests->contains($tournamentRequest)) {
            $this->tournamentRequests[] = $tournamentRequest;
            $tournamentRequest->setAircraft($this);
        }

        return $this;
    }

    public function removeTournamentRequest(TournamentRequest $tournamentRequest): self
    {
        if ($this->tournamentRequests->removeElement($tournamentRequest)) {
            // set the owning side to null (unless already changed)
            if ($tournamentRequest->getAircraft() === $this) {
                $tournamentRequest->setAircraft(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|AircraftClass[]
     */
    public function getAircraftClasses(): Collection
    {
        return $this->aircraftClasses;
    }

    public function addAircraftClass(AircraftClass $aircraftClass): self
    {
        if (!$this->aircraftClasses->contains($aircraftClass)) {
            $this->aircraftClasses[] = $aircraftClass;
            $aircraftClass->addAircraft($this);
        }

        return $this;
    }

    public function removeAircraftClass(AircraftClass $aircraftClass): self
    {
        if ($this->aircraftClasses->removeElement($aircraftClass)) {
            $aircraftClass->removeAircraft($this);
        }

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
            $raceRun->setPlane($this);
        }

        return $this;
    }

    public function removeRaceRun(RaceRun $raceRun): self
    {
        if ($this->raceRuns->removeElement($raceRun)) {
            // set the owning side to null (unless already changed)
            if ($raceRun->getPlane() === $this) {
                $raceRun->setPlane(null);
            }
        }

        return $this;
    }
}
