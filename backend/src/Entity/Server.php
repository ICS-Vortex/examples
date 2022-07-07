<?php

namespace App\Entity;

use App\Constant\Parameter;
use App\Includes\Timestamp;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * Server
 * @ORM\Table(name="servers")
 * @ORM\Entity(repositoryClass="App\Repository\ServerRepository")
 * @ORM\HasLifecycleCallbacks()
 * @Vich\Uploadable
 */
class Server
{
    use Timestamp;

    /**
     * @Groups({"api_tournaments"})
     */
    private $currentMissionRegistry = null;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups({"api_tournaments","server", "api_open_servers", "api_instances", "api_articles", "api_sorties", "api_dogfights"})
     */
    private $id;
    /**
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     * @Assert\NotBlank(message="Server name can't be empty")
     * @Groups({"api_tournaments","server", "api_open_servers", "api_instances", "api_articles", "api_sorties", "api_dogfights"})
     */
    private $name;
    /**
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     * @Assert\NotBlank(message="Admin email can't be empty")
     * @Groups({"server", "api_open_servers", "api_instances"})
     */
    private $email = 'email@email.com';
    /**
     * @ORM\Column(name="identifier", type="string", length=64)
     * @Groups({"server", "api_instances"})
     */
    private $identifier;
    /**
     * @ORM\Column(name="version", type="string", length=64, nullable=true)
     * @Groups({"api_tournaments","server", "api_open_servers"})
     */
    private $version;
    /**
     * @ORM\Column(name="order_position", type="integer", options={"default":100000})
     * @Groups({"server", "api_open_servers"})
     */
    private $orderPosition = 100000;
    /**
     * @ORM\Column(name="image", type="string", length=255, nullable=true)
     * @Groups({"api_tournaments","server", "api_open_servers"})
     */
    private $image;
    /**
     * @ORM\Column(name="discord_server_id", type="string", length=255, nullable=true)
     * @Groups({"server", "api_instances", "api_open_servers"})
     */
    private $discordServerId;
    /**
     * @ORM\Column(name="discord_bot_token", type="string", length=255, nullable=true)
     * @Groups({"server", "api_instances"})
     */
    private $discordBotToken;
    /**
     * @var boolean
     *
     * @ORM\Column(name="send_discord_notifications", type="boolean", options={"default":0})
     * @Groups({"server", "api_instances"})
     */
    private $sendDiscordNotifications = false;
    /**
     * @var boolean
     *
     * @ORM\Column(name="send_discord_server_notifications", type="boolean", options={"default":0})
     * @Groups({"server", "api_instances"})
     */
    private $sendDiscordServerNotifications = false;
    /**
     * @var boolean
     *
     * @ORM\Column(name="send_discord_flight_notifications", type="boolean", options={"default":0})
     * @Groups({"server", "api_instances"})
     */
    private $sendDiscordFlightNotifications = false;
    /**
     * @var boolean
     *
     * @ORM\Column(name="send_discord_friendly_fire_notifications", type="boolean", options={"default":0})
     * @Groups({"server", "api_instances"})
     */
    private $sendDiscordFriendlyFireNotifications = false;
    /**
     * @var boolean
     *
     * @ORM\Column(name="send_discord_combat_notifications", type="boolean", options={"default":0})
     * @Groups({"server", "api_instances"})
     */
    private $sendDiscordCombatNotifications = false;
    /**
     * @ORM\Column(name="discord_webhook", type="string", length=255, nullable=true)
     * @Groups({"api_instances"})
     */
    private $discordWebHook;
    /**
     * @ORM\Column(name="background_image", type="string", length=255, nullable=true)
     * @Groups({"api_tournaments","server", "api_open_servers", "api_instances"})
     */
    private $backgroundImage;
    /**
     * @Vich\UploadableField(mapping="servers_images", fileNameProperty="backgroundImage")
     * @var File
     */
    private $backgroundImageFile;

    /**
     * @ORM\Column(name="description", type="text", nullable=true)
     * @Groups({"api_tournaments","server", "api_open_servers"})
     */
    private $description;
    /**
     * @ORM\Column(name="description_en", type="text", nullable=true)
     * @Groups({"api_tournaments","server", "api_open_servers"})
     */
    private $descriptionEn;
    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=255, options={"default":"0.0.0.0"})
     * @Assert\NotBlank(message="Server address can't be empty")
     * @Groups({"api_tournaments","server", "api_open_servers", "api_instances", "api_sorties", "api_dogfights"})
     */
    private $address = '0.0.0.0';
    /**
     * @var string
     *
     * @ORM\Column(name="port", type="string", options={"default":"10308"}, nullable=true)
     * @Assert\NotBlank(message="Server port can't be empty")
     * @Groups({"api_tournaments","server", "api_open_servers", "api_instances", "api_sorties", "api_dogfights"})
     */
    private $port = Parameter::DEFAULT_PORT;
    /**
     * @var string
     *
     * @ORM\Column(name="folder", type="string", length=255, nullable=true)
     * @Groups({"server", "api_open_servers", "api_instances"})
     */
    private $folder;
    /**
     * @var string
     *
     * @ORM\Column(name="ts3_address", type="string", length=255, nullable=true)
     * @Groups({"api_tournaments","server", "api_open_servers", "api_instances"})
     */
    private $teamSpeakAddress = '0.0.0.0';
    /**
     * @var string
     *
     * @ORM\Column(name="srs_address", type="string", length=255, nullable=true)
     * @Groups({"api_tournaments","server", "api_open_servers", "api_instances"})
     */
    private $srsAddress = '0.0.0.0';
    /**
     * @var string
     *
     * @ORM\Column(name="discord_address", type="string", nullable=true)
     * @Groups({"api_tournaments","server", "api_open_servers", "api_instances"})
     */
    private $discordAddress;
    /**
     * @var string
     *
     * @ORM\Column(name="mumble_address", type="string", nullable=true)
     * @Groups({"api_tournaments","server", "api_open_servers", "api_instances"})
     */
    private $mumbleAddress;
    /**
     * @var string
     *
     * @ORM\Column(name="srs_file", type="string", length=255, nullable=true)
     * @Groups({"server", "api_instances"})
     */
    private $srsFile;
    /**
     * @var string
     *
     * @ORM\Column(name="reports_location", type="string", length=255, options={"default":"C:\"})
     * @Assert\NotBlank(message="Reports location should be set")
     * @Groups({"server", "api_instances"})
     */
    private $reportsLocation = 'C:\\';
    /**
     * @var boolean
     *
     * @ORM\Column(name="active", type="boolean", options={"default":0})
     * @Groups({"api_tournaments","server", "api_instances"})
     */
    private $active = false;
    /**
     * @var boolean
     *
     * @ORM\Column(name="has_sender", type="boolean", options={"default":1})
     */
    private $hasSender = true;
    /**
     * @var boolean
     *
     * @ORM\Column(name="is_online", type="boolean", options={"default":0})
     * @Groups({"api_tournaments","server", "api_open_servers", "api_instances"})
     */
    private $isOnline = false;
    /**
     * @var boolean
     *
     * @ORM\Column(name="show_map", type="boolean", options={"default":0})
     * @Groups({"api_tournaments","server", "api_open_servers"})
     */
    private $showMap = false;
    /**
     * @var boolean
     *
     * @ORM\Column(name="show_banlist", type="boolean", options={"default":0})
     * @Groups({"api_tournaments","server", "api_open_servers"})
     */
    private $showBanList = false;
    /**
     * @ORM\Column(name="start_time", type="datetime", nullable=true)
     * @Groups({"api_tournaments","api_open_servers", "api_instances"})
     */
    private $startTime;
    /**
     * @var DateTime
     * @ORM\Column(name="last_activity",type="datetime", nullable=true)
     * @Groups({"api_tournaments","api_open_servers"})
     */
    private $lastActivity;
    /**
     * @var Mission
     * @ORM\ManyToOne(targetEntity="Mission")
     * @ORM\JoinColumn(name="mission_id", referencedColumnName="id", nullable=true, onDelete="CASCADE")
     * @Groups({"api_open_servers"})
     */
    private $mission;
    /**
     * @ORM\OneToMany(targetEntity="Kill", mappedBy="server", orphanRemoval=true, cascade={"remove"})
     */
    private $kills;
    /**
     * @ORM\OneToMany(targetEntity="Streak", mappedBy="server", orphanRemoval=true, cascade={"remove"})
     */
    private $tempStreaks;
    /**
     * @ORM\OneToMany(targetEntity="Visitor", mappedBy="server", orphanRemoval=true, cascade={"remove"})
     */
    private $visitors;
    /**
     * @ORM\OneToMany(targetEntity="Sortie", mappedBy="server", orphanRemoval=true, cascade={"remove"})
     */
    private $flightHours;
    /**
     * @ORM\OneToMany(targetEntity="FavorPlanes", mappedBy="server", orphanRemoval=true, cascade={"remove"})
     */
    private $favorPlanes;
    /**
     * @ORM\OneToMany(targetEntity="Event", mappedBy="server", orphanRemoval=true, cascade={"remove"})
     */
    private $events;
    /**
     * @ORM\OneToMany(targetEntity="Dogfight", mappedBy="server", orphanRemoval=true, cascade={"remove"})
     */
    private $dogfights;
    /**
     * @ORM\OneToMany(targetEntity="CurrentKill", mappedBy="server", orphanRemoval=true, cascade={"remove"})
     */
    private $currentKills;
    /**
     * @ORM\OneToMany(targetEntity="MissionRegistry", mappedBy="server", orphanRemoval=true, cascade={"remove"})
     */
    private $missionRegistry;
    /**
     * @ORM\OneToMany(targetEntity="Flight", mappedBy="server", orphanRemoval=true, cascade={"remove"})
     */
    private $currentFlights;
    /**
     * @ORM\OneToMany(targetEntity="BestStreak", mappedBy="server", orphanRemoval=true, cascade={"remove"})
     */
    private $bestStreaks;
    /**
     * @var Collection
     * @ORM\OneToMany(targetEntity="MapUnit", mappedBy="server", orphanRemoval=true, cascade={"remove"})
     */
    private $mapUnits;
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\SenderConfig", mappedBy="server", orphanRemoval=true, cascade={"remove"})
     */
    private $senderConfigs;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Pilot", inversedBy="servers", cascade={"remove"})
     * @ORM\JoinTable(name="server_admins",
     *      joinColumns={@ORM\JoinColumn(name="server_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="admin_id", referencedColumnName="id")}
     *      )
     * @Groups({"server"})
     */
    private $admins;
    /**
     * @var Collection
     * @ORM\OneToMany(targetEntity="ChatMessage", mappedBy="server", orphanRemoval=true, cascade={"remove"})
     */
    private $chatMessages;
    /**
     * Collection
     * @ORM\OneToMany(targetEntity="App\Entity\Online", mappedBy="server", orphanRemoval=true, cascade={"remove"})
     * @Groups({"api_open_servers", "api_tournaments"})
     */
    private $pilotsOnline;
    /**
     * @ORM\Column(name="is_aerobatics", type="boolean")
     * @Groups({"api_open_servers"})
     */
    private $isAerobatics = false;
    /**
     * @ORM\Column(name="is_pvp", type="boolean")
     * @Groups({"api_open_servers"})
     */
    private $isPvp = false;
    /**
     * @ORM\Column(name="is_modern", type="boolean")
     * @Groups({"api_open_servers"})
     */
    private $isModern = false;
    /**
     * @var boolean
     * @ORM\Column(name="is_beta", type="boolean")
     * @Groups({"api_open_servers", "api_tournaments"})
     */
    private $beta = false;
    /**
     * @var boolean
     * @ORM\Column(name="send_version_update_emails", type="boolean")
     * @Groups({"api_open_servers"})
     */
    private $sendVersionUpdateEmails = false;

    /**
     * @ORM\OneToMany(targetEntity=FeaturedVideo::class, mappedBy="server", orphanRemoval=true, cascade={"remove"})
     * @Groups({"api_open_servers"})
     */
    private $featuredVideos;

    /**
     * @ORM\ManyToMany(targetEntity=Tournament::class, mappedBy="servers")
     */
    private $tournaments;

    /**
     * @ORM\OneToMany(targetEntity=RaceRun::class, mappedBy="server", orphanRemoval=true)
     */
    private $raceRuns;

    /**
     * @var bool
     * @Groups({"api_open_servers", "api_tournaments"})
     */
    private bool $inTournament = false;

    /**
     * @ORM\OneToMany(targetEntity=TournamentCouponRequest::class, mappedBy="server")
     */
    private $tournamentCouponRequests;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->identifier = $this->generateIdentifier();
        $this->startTime = new DateTime();
        $this->lastActivity = new DateTime();
        $this->mapUnits = new ArrayCollection();
        $this->kills = new ArrayCollection();
        $this->tempStreaks = new ArrayCollection();
        $this->visitors = new ArrayCollection();
        $this->flightHours = new ArrayCollection();
        $this->favorPlanes = new ArrayCollection();
        $this->events = new ArrayCollection();
        $this->dogfights = new ArrayCollection();
        $this->currentKills = new ArrayCollection();
        $this->currentFlights = new ArrayCollection();
        $this->bestStreaks = new ArrayCollection();
        $this->missionRegistry = new ArrayCollection();
        $this->senderConfigs = new ArrayCollection();
        $this->admins = new ArrayCollection();
        $this->chatMessages = new ArrayCollection();
        $this->pilotsOnline = new ArrayCollection();
        $this->featuredVideos = new ArrayCollection();
        $this->tournaments = new ArrayCollection();
        $this->raceRuns = new ArrayCollection();
        $this->tournamentCouponRequests = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Server
     */
    public function setName($name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Set image
     *
     * @param string $image
     *
     * @return Server
     */
    public function setImage($image): self
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage(): ?string
    {
        return $this->image;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Server
     */
    public function setDescription($description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Set descriptionEn
     *
     * @param string $descriptionEn
     *
     * @return Server
     */
    public function setDescriptionEn($descriptionEn): self
    {
        $this->descriptionEn = $descriptionEn;

        return $this;
    }

    /**
     * Get descriptionEn
     *
     * @return string
     */
    public function getDescriptionEn(): ?string
    {
        return $this->descriptionEn;
    }

    /**
     * Set address
     *
     * @param string $address
     *
     * @return Server
     */
    public function setAddress($address): self
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress(): ?string
    {
        return $this->address;
    }

    /**
     * Set port
     *
     * @param string $port
     *
     * @return Server
     */
    public function setPort($port): self
    {
        $this->port = $port;

        return $this;
    }

    /**
     * Get port
     *
     * @return string
     */
    public function getPort(): ?string
    {
        return $this->port;
    }

    /**
     * Set teamSpeakAddress
     *
     * @param string $teamSpeakAddress
     *
     * @return Server
     */
    public function setTeamSpeakAddress($teamSpeakAddress): self
    {
        $this->teamSpeakAddress = $teamSpeakAddress;

        return $this;
    }

    /**
     * Get teamSpeakAddress
     *
     * @return string
     */
    public function getTeamSpeakAddress(): ?string
    {
        return $this->teamSpeakAddress;
    }

    /**
     * Set srsAddress
     *
     * @param string $srsAddress
     *
     * @return Server
     */
    public function setSrsAddress($srsAddress): self
    {
        $this->srsAddress = $srsAddress;

        return $this;
    }

    /**
     * Get srsAddress
     *
     * @return string
     */
    public function getSrsAddress(): ?string
    {
        return $this->srsAddress;
    }

    /**
     * Set active
     *
     * @param bool $active
     *
     * @return Server
     */
    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return boolean
     */
    public function getActive(): ?bool
    {
        return $this->active;
    }

    /**
     * Set startTime
     *
     * @param DateTime $startTime
     *
     * @return Server
     */
    public function setStartTime($startTime): self
    {
        $this->startTime = $startTime;

        return $this;
    }

    /**
     * Get startTime
     *
     * @return DateTime
     */
    public function getStartTime(): ?DateTime
    {
        return $this->startTime;
    }

    /**
     * Set mission
     *
     * @param Mission $mission
     *
     * @return Server
     */
    public function setMission(Mission $mission = null): self
    {
        $this->mission = $mission;

        return $this;
    }

    /**
     * Get mission
     *
     * @return Mission
     */
    public function getMission(): ?Mission
    {
        return $this->mission;
    }

    /**
     * Add kill
     *
     * @param Kill $kill
     *
     * @return Server
     */
    public function addKill(Kill $kill): self
    {
        $this->kills[] = $kill;

        return $this;
    }

    /**
     * Remove kill
     *
     * @param Kill $kill
     */
    public function removeKill(Kill $kill): void
    {
        $this->kills->removeElement($kill);
    }

    /**
     * Get kills
     *
     * @return Collection
     */
    public function getKills(): ?Collection
    {
        return $this->kills;
    }

    /**
     * Add tempStreak
     *
     * @param Streak $tempStreak
     *
     * @return Server
     */
    public function addTempStreak(Streak $tempStreak): self
    {
        $this->tempStreaks[] = $tempStreak;

        return $this;
    }

    /**
     * Remove tempStreak
     *
     * @param Streak $tempStreak
     */
    public function removeTempStreak(Streak $tempStreak): void
    {
        $this->tempStreaks->removeElement($tempStreak);
    }

    /**
     * Get tempStreaks
     *
     * @return Collection
     */
    public function getTempStreaks(): ?Collection
    {
        return $this->tempStreaks;
    }

    /**
     * Add visitor
     *
     * @param Visitor $visitor
     *
     * @return Server
     */
    public function addVisitor(Visitor $visitor): self
    {
        $this->visitors[] = $visitor;

        return $this;
    }

    /**
     * Remove visitor
     *
     * @param Visitor $visitor
     */
    public function removeVisitor(Visitor $visitor): void
    {
        $this->visitors->removeElement($visitor);
    }

    /**
     * Get visitors
     *
     * @return Collection
     */
    public function getVisitors(): ?Collection
    {
        return $this->visitors;
    }

    /**
     * Add flightHour
     *
     * @param Sortie $flightHour
     *
     * @return Server
     */
    public function addFlightHour(Sortie $flightHour): self
    {
        $this->flightHours[] = $flightHour;

        return $this;
    }

    /**
     * Remove flightHour
     *
     * @param Sortie $flightHour
     */
    public function removeFlightHour(Sortie $flightHour): void
    {
        $this->flightHours->removeElement($flightHour);
    }

    /**
     * Get flightHours
     *
     * @return Collection
     */
    public function getFlightHours(): ?Collection
    {
        return $this->flightHours;
    }

    /**
     * Add favorPlane
     *
     * @param FavorPlanes $favorPlane
     *
     * @return Server
     */
    public function addFavorPlane(FavorPlanes $favorPlane): self
    {
        $this->favorPlanes[] = $favorPlane;

        return $this;
    }

    /**
     * Remove favorPlane
     *
     * @param FavorPlanes $favorPlane
     */
    public function removeFavorPlane(FavorPlanes $favorPlane): void
    {
        $this->favorPlanes->removeElement($favorPlane);
    }

    /**
     * Get favorPlanes
     *
     * @return Collection
     */
    public function getFavorPlanes(): Collection
    {
        return $this->favorPlanes;
    }

    /**
     * Add event
     *
     * @param Event $event
     *
     * @return Server
     */
    public function addEvent(Event $event): self
    {
        $this->events[] = $event;

        return $this;
    }

    /**
     * Remove event
     *
     * @param Event $event
     */
    public function removeEvent(Event $event): void
    {
        $this->events->removeElement($event);
    }

    /**
     * Get events
     *
     * @return Collection
     */
    public function getEvents(): ?Collection
    {
        return $this->events;
    }

    /**
     * Add dogfight
     *
     * @param Dogfight $dogfight
     *
     * @return Server
     */
    public function addDogfight(Dogfight $dogfight): self
    {
        $this->dogfights[] = $dogfight;

        return $this;
    }

    /**
     * Remove dogfight
     *
     * @param Dogfight $dogfight
     */
    public function removeDogfight(Dogfight $dogfight): void
    {
        $this->dogfights->removeElement($dogfight);
    }

    /**
     * Get dogfights
     *
     * @return Collection|null
     */
    public function getDogfights(): ?Collection
    {
        return $this->dogfights;
    }

    /**
     * Add currentKill
     *
     * @param CurrentKill $currentKill
     *
     * @return Server
     */
    public function addCurrentKill(CurrentKill $currentKill): self
    {
        $this->currentKills[] = $currentKill;

        return $this;
    }

    /**
     * Remove currentKill
     *
     * @param CurrentKill $currentKill
     */
    public function removeCurrentKill(CurrentKill $currentKill): void
    {
        $this->currentKills->removeElement($currentKill);
    }

    /**
     * Get currentKills
     *
     * @return Collection
     */
    public function getCurrentKills(): Collection
    {
        return $this->currentKills;
    }

    /**
     * Add currentFlight
     *
     * @param Flight $currentFlight
     *
     * @return Server
     */
    public function addCurrentFlight(Flight $currentFlight): self
    {
        $this->currentFlights[] = $currentFlight;

        return $this;
    }

    /**
     * Remove currentFlight
     *
     * @param Flight $currentFlight
     */
    public function removeCurrentFlight(Flight $currentFlight): void
    {
        $this->currentFlights->removeElement($currentFlight);
    }

    /**
     * Get currentFlights
     *
     * @return Collection| null
     */
    public function getCurrentFlights(): ?Collection
    {
        return $this->currentFlights;
    }

    /**
     * Add bestStreak
     *
     * @param BestStreak $bestStreak
     *
     * @return Server
     */
    public function addBestStreak(BestStreak $bestStreak): self
    {
        $this->bestStreaks[] = $bestStreak;

        return $this;
    }

    /**
     * Remove bestStreak
     *
     * @param BestStreak $bestStreak
     */
    public function removeBestStreak(BestStreak $bestStreak): void
    {
        $this->bestStreaks->removeElement($bestStreak);
    }

    /**
     * Get bestStreaks
     *
     * @return Collection
     */
    public function getBestStreaks(): Collection
    {
        return $this->bestStreaks;
    }

    /**
     * Add online
     *
     * @param MissionRegistry $missionRegistry
     * @return Server
     */
    public function addMissionRegistry(MissionRegistry $missionRegistry): self
    {
        $this->missionRegistry[] = $missionRegistry;

        return $this;
    }

    /**
     * Remove online
     *
     * @param MissionRegistry $missionRegistry
     */
    public function removeMissionRegistry(MissionRegistry $missionRegistry): void
    {
        $this->missionRegistry->removeElement($missionRegistry);
    }

    /**
     * @return mixed
     */
    public function getBackgroundImage(): ?string
    {
        return $this->backgroundImage;
    }

    /**
     * @param mixed $backgroundImage
     */
    public function setBackgroundImage($backgroundImage): void
    {
        $this->backgroundImage = $backgroundImage;
    }

    public function isOnline(): ?bool
    {
        return $this->isOnline;
    }

    public function setIsOnline($flag = true): void
    {
        $this->isOnline = $flag;
    }

    public function getIsOnline(): ?bool
    {
        return $this->isOnline;
    }

    /**
     * @return mixed
     */
    public function getIdentifier(): ?string
    {
        return $this->identifier;
    }

    /**
     * @param mixed $identifier
     */
    public function setIdentifier($identifier): void
    {
        $this->identifier = $identifier;
    }

    /**
     * @return bool
     */
    public function isShowMap(): bool
    {
        return $this->showMap;
    }

    /**
     * @param bool $showMap
     */
    public function setShowMap($showMap): void
    {
        $this->showMap = $showMap;
    }

    /**
     * @return string|null
     */
    public function getTheatre(): ?string
    {
        if ($this->getMission() === null) {
            return null;
        }
        $theatre = $this->getMission()->getTheatre();

        if (empty($theatre)) {
            return null;
        }

        return $theatre->getName();
    }

    /**
     * @return Collection|SenderConfig[]
     */
    public function getSenderConfigs(): Collection
    {
        return $this->senderConfigs;
    }

    public function addSenderConfig(SenderConfig $senderConfig): self
    {
        if (!$this->senderConfigs->contains($senderConfig)) {
            $this->senderConfigs[] = $senderConfig;
            $senderConfig->setServer($this);
        }

        return $this;
    }

    public function removeSenderConfig(SenderConfig $senderConfig): self
    {
        if ($this->senderConfigs->contains($senderConfig)) {
            $this->senderConfigs->removeElement($senderConfig);
            // set the owning side to null (unless already changed)
            if ($senderConfig->getServer() === $this) {
                $senderConfig->setServer(null);
            }
        }

        return $this;
    }

    /**
     * @return bool
     */
    public function hasSender(): bool
    {
        return $this->hasSender;
    }

    /**
     * @param bool $hasSender
     */
    public function setHasSender(bool $hasSender): void
    {
        $this->hasSender = $hasSender;
    }

    /**
     * @param int $number
     * @return array|Collection
     */
    public function getMissionRegistry(int $number = 0)
    {
        if ($number === 0) {
            return $this->missionRegistry;
        }

        $result = [];
        $count = 1;
        foreach ($this->missionRegistry as $mission) {
            if ($count > $number) {
                break;
            }
            $result[] = $mission;
            $count++;
        }

        return $result;
    }

    /**
     * @param mixed $missionRegistry
     */
    public function setMissionRegistry($missionRegistry): void
    {
        $this->missionRegistry = $missionRegistry;
    }

    /**
     * @return Collection
     */
    public function getAdmins(): Collection
    {
        return $this->admins;
    }

    public function isAvailableForAdmin(Pilot $admin)
    {
        return $this->admins->contains($admin);
    }

    /**
     * @return Collection
     */
    public function getPilotsOnline(): Collection
    {
        return $this->pilotsOnline;
    }

    public function setOnline($pilotsOnline): void
    {
        $this->online = $pilotsOnline;
    }

    /**
     * @param Online $pilotsOnline
     */
    public function addPilotOnline(Online $pilotsOnline): void
    {
        $this->pilotsOnline->add($pilotsOnline);
    }

    public function removePilotOnline(Online $pilotsOnline): void
    {
        $this->pilotsOnline->remove($pilotsOnline);
    }

    /**
     * @return mixed
     */
    public function getDiscordWebHook()
    {
        return $this->discordWebHook;
    }

    /**
     * @param mixed $discordWebHook
     */
    public function setDiscordWebHook($discordWebHook): void
    {
        $this->discordWebHook = $discordWebHook;
    }


    public function removeAdmins(Pilot $admin): void
    {
        $this->admins->removeElement($admin);
    }

    /**
     * @return string
     */
    public function getFolder(): ?string
    {
        return $this->folder;
    }

    /**
     * @param string $folder
     */
    public function setFolder($folder): void
    {
        $this->folder = $folder;
    }

    public function hasFolder(): bool
    {
        if (empty($this->folder)) {
            return false;
        }
        if (is_dir($this->folder) && file_exists($this->folder)) {
            return true;
        }
        return false;
    }

    /**
     * @return DateTime|null
     */
    public function getLastActivity(): ?DateTime
    {
        return $this->lastActivity;
    }

    /**
     * @param DateTime $lastActivity
     */
    public function setLastActivity(DateTime $lastActivity): void
    {
        $this->lastActivity = $lastActivity;
    }

    public function __toString(): string
    {
        return $this->name;
    }

    /**
     * @return string|null
     */
    public function getVersion(): ?string
    {
        return $this->version;
    }

    /**
     * @param mixed $version
     */
    public function setVersion($version): void
    {
        $this->version = $version;
    }


    /**
     * Add bestStreak
     *
     * @param MapUnit $mapUnit
     *
     * @return Server
     */
    public function addMapUnit(MapUnit $mapUnit): self
    {
        $this->mapUnits[] = $mapUnit;

        return $this;
    }

    /**
     * Remove mapUnit
     *
     * @param MapUnit $mapUnit
     */
    public function removeMapUnit(MapUnit $mapUnit): void
    {
        $this->mapUnits->removeElement($mapUnit);
    }

    /**
     * Get mapUnits
     *
     * @return Collection
     */
    public function getMapUnits(): Collection
    {
        return $this->mapUnits;
    }

    public function setBackgroundImageFile(File $image = null): void
    {
        $this->backgroundImageFile = $image;
        if ($image) {
            $this->updatedAt = new DateTime();
        }
    }

    public function getBackgroundImageFile(): ?File
    {
        return $this->backgroundImageFile;
    }

    /**
     * @param int|null $number
     * @return array|Collection
     */
    public function getChatMessages(int $number = 0)
    {
        if ($number === null) {
            return $this->chatMessages;
        }

        $result = [];
        $count = 1;
        foreach ($this->chatMessages as $message) {
            if ($count > $number) {
                break;
            }
            $result[] = $message;
            $count++;
        }

        return $result;
    }

    /**
     * @param ChatMessage $chatMessage
     */
    public function addChatMessage(ChatMessage $chatMessage): void
    {
        $this->chatMessages[] = $chatMessage;
    }

    public function removeChatMessage(ChatMessage $chatMessage): void
    {
        $this->chatMessages->removeElement($chatMessage);
    }

    public function generateIdentifier()
    {
        try {
            return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
                // 32 bits for "time_low"
                random_int(0, 0xffff), random_int(0, 0xffff),

                // 16 bits for "time_mid"
                random_int(0, 0xffff),

                // 16 bits for "time_hi_and_version",
                // four most significant bits holds version number 4
                random_int(0, 0x0fff) | 0x4000,

                // 16 bits, 8 bits for "clk_seq_hi_res",
                // 8 bits for "clk_seq_low",
                // two most significant bits holds zero and one for variant DCE1.1
                random_int(0, 0x3fff) | 0x8000,

                // 48 bits for "node"
                random_int(0, 0xffff), random_int(0, 0xffff), random_int(0, 0xffff)
            );
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * @return string
     */
    public function getSrsFile(): ?string
    {
        return $this->srsFile;
    }

    /**
     * @param string $srsFile
     */
    public function setSrsFile($srsFile): void
    {
        $this->srsFile = $srsFile;
    }

    /**
     * @return string
     */
    public function getReportsLocation(): ?string
    {
        return $this->reportsLocation;
    }

    /**
     * @param string $reportsLocation
     */
    public function setReportsLocation($reportsLocation): void
    {
        $this->reportsLocation = $reportsLocation;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email): void
    {
        $this->email = $email;
    }

    /**
     * @return bool
     */
    public function isSendDiscordNotifications(): bool
    {
        return $this->sendDiscordNotifications;
    }

    /**
     * @return bool
     */
    public function getSendDiscordNotifications(): bool
    {
        return $this->sendDiscordNotifications;
    }

    /**
     * @param bool $sendDiscordNotifications
     */
    public function setSendDiscordNotifications(bool $sendDiscordNotifications): void
    {
        $this->sendDiscordNotifications = $sendDiscordNotifications;
    }

    /**
     * @return bool
     */
    public function isSendDiscordServerNotifications(): bool
    {
        return $this->sendDiscordServerNotifications;
    }

    /**
     * @param bool $sendDiscordServerNotifications
     */
    public function setSendDiscordServerNotifications(bool $sendDiscordServerNotifications): void
    {
        $this->sendDiscordServerNotifications = $sendDiscordServerNotifications;
    }

    /**
     * @return bool
     */
    public function isSendDiscordFlightNotifications(): bool
    {
        return $this->sendDiscordFlightNotifications;
    }

    /**
     * @param bool $sendDiscordFlightNotifications
     */
    public function setSendDiscordFlightNotifications(bool $sendDiscordFlightNotifications): void
    {
        $this->sendDiscordFlightNotifications = $sendDiscordFlightNotifications;
    }

    /**
     * @return bool
     */
    public function isSendDiscordCombatNotifications(): bool
    {
        return $this->sendDiscordCombatNotifications;
    }

    /**
     * @param bool $sendDiscordCombatNotifications
     */
    public function setSendDiscordCombatNotifications(bool $sendDiscordCombatNotifications): void
    {
        $this->sendDiscordCombatNotifications = $sendDiscordCombatNotifications;
    }

    /**
     * @return int
     */
    public function getOrderPosition(): int
    {
        return $this->orderPosition;
    }

    /**
     * @param int $orderPosition
     */
    public function setOrderPosition(int $orderPosition): void
    {
        $this->orderPosition = $orderPosition;
    }

    /**
     * @return mixed
     */
    public function getDiscordServerId()
    {
        return $this->discordServerId;
    }

    /**
     * @param mixed $discordServerId
     */
    public function setDiscordServerId($discordServerId): void
    {
        $this->discordServerId = $discordServerId;
    }

    /**
     * @return bool
     */
    public function isSendDiscordFriendlyFireNotifications(): bool
    {
        return $this->sendDiscordFriendlyFireNotifications;
    }

    /**
     * @param bool $sendDiscordFriendlyFireNotifications
     */
    public function setSendDiscordFriendlyFireNotifications(bool $sendDiscordFriendlyFireNotifications): void
    {
        $this->sendDiscordFriendlyFireNotifications = $sendDiscordFriendlyFireNotifications;
    }

    /**
     * @return mixed
     */
    public function getDiscordBotToken()
    {
        return $this->discordBotToken;
    }

    /**
     * @param mixed $discordBotToken
     */
    public function setDiscordBotToken($discordBotToken): void
    {
        $this->discordBotToken = $discordBotToken;
    }

    /**
     * @return int
     */
    public function getLastActivityInHours(): int
    {
        if (empty($this->lastActivity)) {
            return 0;
        }
        $now = (new DateTime())->format('Y-m-d H:i:s');
        $last = $this->lastActivity->format('Y-m-d H:i:s');
        return round((strtotime($now) - strtotime($last)) / 3600, 1);
    }

    /**
     * @return bool
     */
    public function getIsAerobatics(): bool
    {
        return $this->isAerobatics;
    }

    /**
     * @param bool $isAerobatics
     */
    public function setIsAerobatics(bool $isAerobatics = true): void
    {
        $this->isAerobatics = $isAerobatics;
    }

    /**
     * @return bool
     */
    public function getIsPvp(): bool
    {
        return $this->isPvp;
    }

    /**
     * @param bool $isPvp
     */
    public function setIsPvp(bool $isPvp = true): void
    {
        $this->isPvp = $isPvp;
    }

    /**
     * @return bool
     */
    public function getIsModern(): bool
    {
        return $this->isModern;
    }

    /**
     * @param bool $isModern
     */
    public function setIsModern(bool $isModern = true): void
    {
        $this->isModern = $isModern;
    }

    /**
     * @return bool
     */
    public function getIsShowBanList(): bool
    {
        return $this->showBanList;
    }

    public function getShowBanList(): bool
    {
        return $this->showBanList;
    }

    public function showBanList(): bool
    {
        return $this->showBanList;
    }

    /**
     * @param bool $showBanList
     */
    public function setShowBanList(bool $showBanList): void
    {
        $this->showBanList = $showBanList;
    }

    /**
     * @return Collection|FeaturedVideo[]
     */
    public function getFeaturedVideos(): Collection
    {
        return $this->featuredVideos;
    }

    public function addFeaturedVideo(FeaturedVideo $featuredVideo): self
    {
        if (!$this->featuredVideos->contains($featuredVideo)) {
            $this->featuredVideos[] = $featuredVideo;
            $featuredVideo->setServer($this);
        }

        return $this;
    }

    public function removeFeaturedVideo(FeaturedVideo $featuredVideo): self
    {
        if ($this->featuredVideos->contains($featuredVideo)) {
            $this->featuredVideos->removeElement($featuredVideo);
            // set the owning side to null (unless already changed)
            if ($featuredVideo->getServer() === $this) {
                $featuredVideo->setServer(null);
            }
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getDiscordAddress(): ?string
    {
        return $this->discordAddress;
    }

    /**
     * @param string $discordAddress
     */
    public function setDiscordAddress(string $discordAddress): void
    {
        $this->discordAddress = $discordAddress;
    }

    /**
     * @return string
     */
    public function getMumbleAddress(): ?string
    {
        return $this->mumbleAddress;
    }

    /**
     * @param string $mumbleAddress
     */
    public function setMumbleAddress(string $mumbleAddress): void
    {
        $this->mumbleAddress = $mumbleAddress;
    }

    /**
     * @return bool
     */
    public function isBeta(): bool
    {
        return $this->beta;
    }

    /**
     * @param bool $beta
     * @return Server
     */
    public function setBeta(bool $beta): Server
    {
        $this->beta = $beta;
        return $this;
    }

    /**
     * @return bool
     */
    public function isSendVersionUpdateEmails(): bool
    {
        return $this->sendVersionUpdateEmails;
    }

    /**
     * @param bool $sendVersionUpdateEmails
     * @return Server
     */
    public function setSendVersionUpdateEmails(bool $sendVersionUpdateEmails): Server
    {
        $this->sendVersionUpdateEmails = $sendVersionUpdateEmails;
        return $this;
    }

    /**
     * @return Collection|Tournament[]
     */
    public function getTournaments(): Collection
    {
        return $this->tournaments;
    }

    public function addTournament(Tournament $tournament): self
    {
        if (!$this->tournaments->contains($tournament)) {
            $this->tournaments[] = $tournament;
            $tournament->addServer($this);
        }

        return $this;
    }

    public function removeTournament(Tournament $tournament): self
    {
        if ($this->tournaments->removeElement($tournament)) {
            $tournament->removeServer($this);
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
            $raceRun->setServer($this);
        }

        return $this;
    }

    public function removeRaceRun(RaceRun $raceRun): self
    {
        if ($this->raceRuns->removeElement($raceRun)) {
            // set the owning side to null (unless already changed)
            if ($raceRun->getServer() === $this) {
                $raceRun->setServer(null);
            }
        }

        return $this;
    }

    /**
     * @return null
     */
    public function getCurrentMissionRegistry()
    {
        return $this->currentMissionRegistry;
    }

    /**
     * @param null $currentMissionRegistry
     */
    public function setCurrentMissionRegistry($currentMissionRegistry): void
    {
        $this->currentMissionRegistry = $currentMissionRegistry;
    }

    public function isInTournament(): bool
    {
        /** @var Tournament $tournament */
        foreach ($this->tournaments as $tournament) {
            if ($tournament->getServers()->contains($this)) {
                $this->inTournament = true;
            }
        }
        return $this->inTournament;
    }

    public function setInTournament(bool $inTournament) : self
    {
        $this->inTournament = $inTournament;
        return $this;
    }

    /**
     * @return Tournament|null
     */
    public function getCurrentTournament(): ?Tournament
    {
        /** @var Tournament $tournament */
        foreach ($this->tournaments as $tournament) {
            if (!$tournament->getFinished()) {
                return $tournament;
            }
        }
        return null;
    }

    /**
     * @return Collection|TournamentCouponRequest[]
     */
    public function getTournamentCouponRequests(): Collection
    {
        return $this->tournamentCouponRequests;
    }

    public function addTournamentCouponRequest(TournamentCouponRequest $tournamentCouponRequest): self
    {
        if (!$this->tournamentCouponRequests->contains($tournamentCouponRequest)) {
            $this->tournamentCouponRequests[] = $tournamentCouponRequest;
            $tournamentCouponRequest->setServer($this);
        }

        return $this;
    }

    public function removeTournamentCouponRequest(TournamentCouponRequest $tournamentCouponRequest): self
    {
        if ($this->tournamentCouponRequests->removeElement($tournamentCouponRequest)) {
            // set the owning side to null (unless already changed)
            if ($tournamentCouponRequest->getServer() === $this) {
                $tournamentCouponRequest->setServer(null);
            }
        }

        return $this;
    }
}
