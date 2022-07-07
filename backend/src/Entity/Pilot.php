<?php

namespace App\Entity;

use App\Entity\Location\Region;
use App\Repository\BaseUserRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use JetBrains\PhpStorm\Pure;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\Ignore;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Table(name="pilots",indexes={
 *     @ORM\Index(name="idx_ucid", columns={"ucid"}),
 *     @ORM\Index(name="idx_username", columns={"username"}),
 * }, options={"collate"="utf8_general_ci", "charset"="utf8"})
 * @ORM\Entity(repositoryClass="App\Repository\PilotRepository")
 * @Vich\Uploadable
 * @ORM\HasLifecycleCallbacks
 *
 */
class Pilot extends BaseUser
{
    /**
     * @var string
     * @ORM\Column(name="ip_address", type="string", nullable=true)
     * @Groups({"api_admin","api_profile", "api_tournaments"})
     */
    protected $ipAddress;
    /**
     * @var string
     * @Assert\Url(
     *    message = "message.invalid_url",
     * )
     * @ORM\Column(name="youtube_channel_url", type="string", nullable=true)
     * @Groups({"api_admin","api_profile", "api_tournaments"})
     */
    protected $youtubeChannelUrl;
    /**
     * @var string
     * @Assert\Url(
     *    message = "message.invalid_url",
     * )
     * @ORM\Column(name="twitch_channel_url", type="string", nullable=true)
     * @Groups({"api_admin","api_profile", "api_tournaments"})
     */
    protected $twitchChannelUrl;
    /**
     * @var boolean
     *
     * @ORM\Column(name="checked", type="boolean", options={"default"=0})
     */
    protected $checked = false;
    /**
     * @var string
     * @ORM\Column(name="ucid", type="string")
     * @Groups({"api_admin"})
     */
    protected $ucid;
    /**
     * @var
     * @Ignore()
     * @Groups({"api_admin","api_open_servers", "api_tournaments", "api_profile"})
     */
    protected $shortcode;
    /**
     * @var boolean
     * @ORM\Column(name="online", type="boolean", options={"default"=0}, nullable=true)
     * @Groups({"api_admin","api_open_servers", "api_tournaments", "api_profile"})
     */
    protected $online = false;
    /**
     * @var boolean
     * @ORM\Column(name="is_banned", type="boolean", options={"default"=0})
     * @Groups({"api_admin"})
     */
    protected $banned = false;
    /**
     * @var int
     * @ORM\Column(name="views", type="integer", options={"default"=0}, nullable=false)
     * @Groups({"api_admin","api_open_servers", "api_profile"})
     */
    protected $views = 0;
    /**
     * @var string|null
     * @ORM\Column(name="hardware", type="text", nullable=true)
     * @Groups({"api_admin","api_open_servers", "api_profile"})
     */
    protected $hardware;
    /**
     * @var string|null
     * @ORM\Column(name="about", type="text", nullable=true)
     * @Groups({"api_admin","api_open_servers", "api_tournaments", "api_profile"})
     */
    protected $about;
    /**
     * @var DateTime|null
     * @ORM\Column(name="birthday", type="date", nullable=true)
     * @Groups({"api_admin","api_open_servers", "api_profile"})
     */
    protected $birthday;
    /**
     * @var Plane
     * @ORM\ManyToOne(targetEntity="Plane")
     * @ORM\JoinColumn(name="favourite_plane_id", referencedColumnName="id", nullable=true)
     * @Groups({"api_admin","api_open_servers","api_profile"})
     */
    protected $favouritePlane;
    /**
     * @ORM\ManyToMany(targetEntity="GameDevice", cascade={"remove"})
     * @ORM\JoinTable(name="pilot_game_devices",
     *      joinColumns={@ORM\JoinColumn(name="pilot_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="game_device_id", referencedColumnName="id")}
     *      )
     * @Groups({"api_open_servers", "api_profile"})
     */
    private $devices;
    /**
     * @ORM\ManyToMany(targetEntity=Server::class, mappedBy="admins", cascade={"persist"})
     * @Groups({"server", "admin_profile"})
     */
    private $servers;

    /**
     * @ORM\OneToMany(targetEntity="Online", mappedBy="pilot", orphanRemoval=true)
     */
    private $onlineRecord;
    /**
     * @ORM\OneToMany(targetEntity="Elo", mappedBy="pilot", orphanRemoval=true)
     */
    private $elos;
    /**
     * @ORM\OneToMany(targetEntity="Dogfight", mappedBy="pilot", orphanRemoval=true)
     */
    private $dogfights;
    /**
     * @ORM\OneToMany(targetEntity="Kill", mappedBy="pilot", orphanRemoval=true)
     */
    private $kills;
    /**
     * @ORM\OneToMany(targetEntity="CurrentKill", mappedBy="pilot", orphanRemoval=true)
     */
    private $currentKills;
    /**
     * @var Collection
     * @ORM\OneToMany(targetEntity="BestStreak", mappedBy="pilot", orphanRemoval=true)
     */
    private $bestStreaks;
    /**
     * @ORM\OneToMany(targetEntity="Sortie", mappedBy="pilot", orphanRemoval=true)
     */
    private $sorties;
    /**
     * @ORM\OneToMany(targetEntity="Visitor", mappedBy="pilot", orphanRemoval=true)
     */
    private $visits;
    /**
     * @var Collection
     * @ORM\OneToMany(targetEntity="Ban", mappedBy="pilot", orphanRemoval=true)
     */
    private $banRecords;
    /**
     * @var Collection
     * @ORM\OneToMany(targetEntity="RegistrationTicket", mappedBy="pilot", orphanRemoval=true)
     */
    private $registrationTickets;
    /**
     * @var Collection
     * @ORM\OneToMany(targetEntity="Event", mappedBy="pilot", orphanRemoval=true)
     */
    private $events;
    /**
     * @var Collection
     * @ORM\OneToMany(targetEntity="Streak", mappedBy="pilot", orphanRemoval=true)
     */
    private $streaks;

    /**
     * @ORM\ManyToMany(targetEntity=TournamentStage::class, mappedBy="participants")
     */
    private $tournamentStages;

    /**
     * @ORM\OneToMany(targetEntity=RaceRun::class, mappedBy="pilot", orphanRemoval=true)
     */
    private $raceRuns;

    /**
     * @ORM\OneToMany(targetEntity=TournamentCoupon::class, mappedBy="pilot", orphanRemoval=true)
     */
    private $tournamentCoupons;

    /**
     * @ORM\Column(type="boolean", options={"default": false})
     */
    private $acceptedRules = false;

    /**
     * @ORM\OneToMany(targetEntity=UcidToken::class, mappedBy="pilot", orphanRemoval=true)
     */
    private $ucidTokens;

    /**
     * @ORM\ManyToOne(targetEntity=Region::class, inversedBy="pilots")
     * @Groups({"api_admin","api_open_servers", "api_tournaments", "api_profile"})
     */
    private $region;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"api_admin","api_open_servers", "api_tournaments", "api_profile"})
     */
    private $ipCountry;

    /**
     * @ORM\OneToMany(targetEntity=TournamentCouponRequest::class, mappedBy="pilot", orphanRemoval=true)
     * @Groups({"api_profile"})
     */
    private $tournamentCouponRequests;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"api_admin","api_open_servers", "api_tournaments", "api_profile"})
     */
    private $squad;
    /**
     * @var string|null
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"api_admin","api_open_servers", "api_tournaments", "api_profile"})
     */
    private $squadLogo;
    /**
     * @Vich\UploadableField(mapping="squad_images", fileNameProperty="squadLogo")
     * @var File
     */
    private $squadLogoFile;
    /**
     * @ORM\Column(type="string", length=2, nullable=true)
     * @Groups({"api_admin","api_open_servers", "api_tournaments", "api_profile"})
     */
    private $language;

    /**
     * @ORM\Column(type="boolean", options={"default": false})
     * @Groups({"api_admin","api_open_servers", "api_tournaments", "api_profile"})
     */
    private $publishPhoto = false;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"api_admin","api_profile", "api_tournaments"})
     */
    private $vkProfileUrl;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"api_admin","api_open_servers", "api_tournaments", "api_profile"})
     */
    private $photo;
    /**
     * @Vich\UploadableField(mapping="photo_images", fileNameProperty="photo")
     * @var File
     */
    private $photoFile;

    public function __toString(): string
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * @return bool
     */
    public function isOnline()
    {
        return $this->online;
    }

    /**
     * @param bool $online
     */
    public function setOnline(bool $online)
    {
        $this->online = $online;
    }

    public function getOnline(): bool
    {
        return $this->online;
    }

    /**
     * @return Online
     */
    public function getOnlineRecord()
    {
        return $this->onlineRecord;
    }

    /**
     * @param Online $onlineRecord
     */
    public function setOnlineRecord(Online $onlineRecord)
    {
        $this->onlineRecord = $onlineRecord;
    }

    public function isRegistered()
    {
        if ($this->checked && $this->enabled && !empty($this->email)) {
            return true;
        }
        return false;
    }

    /**
     * @return Collection|Elo[]
     */
    public function getElos(): Collection
    {
        return $this->elos;
    }

    public function addElo(Elo $elo): self
    {
        if (!$this->elos->contains($elo)) {
            $this->elos[] = $elo;
            $elo->setPilot($this);
        }

        return $this;
    }

    public function removeElo(Elo $elo): self
    {
        if ($this->elos->contains($elo)) {
            $this->elos->removeElement($elo);
            // set the owning side to null (unless already changed)
            if ($elo->getPilot() === $this) {
                $elo->setPilot(null);
            }
        }

        return $this;
    }
//
//    /**
//     * @return int
//     */
//    public function getBestAirStreak(): int
//    {
//        if ($this->bestStreaks->count() > 0) {
//            /** @var BestStreak $streak */
//            foreach ($this->bestStreaks as $streak) {
//                if ($streak->getStreakType() === StreakRepository::TYPE_AIR) {
//                    return $streak->getStreak();
//                }
//            }
//        }
//
//        return 0;
//    }

    #[Pure]
    public function __construct()
    {
        $this->events = new ArrayCollection();
        $this->registrationTickets = new ArrayCollection();
        $this->bestStreaks = new ArrayCollection();
        $this->dogfights = new ArrayCollection();
        $this->elos = new ArrayCollection();
        $this->servers = new ArrayCollection();
        $this->currentKills = new ArrayCollection();
        $this->kills = new ArrayCollection();
        $this->banRecords = new ArrayCollection();
        $this->sorties = new ArrayCollection();
        $this->visits = new ArrayCollection();
        $this->tournamentStages = new ArrayCollection();
        $this->raceRuns = new ArrayCollection();
        $this->tournamentCoupons = new ArrayCollection();
        $this->ucidTokens = new ArrayCollection();
        $this->tournamentCouponRequests = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getViews(): int
    {
        return $this->views;
    }

    /**
     * @param int $views
     */
    public function setViews(int $views): void
    {
        $this->views = $views;
    }

    /**
     * @return string
     */
    public function getIpAddress(): ?string
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
     * @return bool
     */
    public function isChecked(): bool
    {
        return $this->checked;
    }

    /**
     * @param bool $checked
     */
    public function setChecked(bool $checked): void
    {
        $this->checked = $checked;
    }

    /**
     * @return string|null
     */
    public function getHardware(): ?string
    {
        return $this->hardware;
    }

    /**
     * @param string|null $hardware
     */
    public function setHardware(?string $hardware): void
    {
        $this->hardware = $hardware;
    }

    /**
     * @return string|null
     */
    public function getAbout(): ?string
    {
        return $this->about;
    }

    /**
     * @param string|null $about
     */
    public function setAbout(?string $about): void
    {
        $this->about = $about;
    }

    /**
     * @return Plane|null
     */
    public function getFavouritePlane(): ?Plane
    {
        return $this->favouritePlane;
    }

    /**
     * @param Plane|null $favouritePlane
     */
    public function setFavouritePlane(?Plane $favouritePlane): void
    {
        $this->favouritePlane = $favouritePlane;
    }

    /**
     * @return DateTime
     */
    public function getBirthday(): ?DateTime
    {
        return $this->birthday;
    }

    /**
     * @param DateTime|string $birthday
     * @throws Exception
     */
    public function setBirthday($birthday): void
    {
        if (is_string($birthday)) {
            $this->birthday = new DateTime($birthday);
        } else {
            $this->birthday = $birthday;
        }
    }

    /**
     * @return ArrayCollection
     */
    public function getDevices()
    {
        return $this->devices;
    }

    /**
     * @param $devices
     * @return ArrayCollection
     */
    public function setDevices($devices)
    {
        return $this->devices = $devices;
    }


    public function addDevice(GameDevice $device): void
    {
        $this->devices->add($device);
    }

    public function removeDevice(GameDevice $device): void
    {
        $this->devices->removeElement($device);
    }

    /**
     * @return string
     */
    public function getYoutubeChannelUrl(): ?string
    {
        return $this->youtubeChannelUrl;
    }

    /**
     * @param string|null $youtubeChannelUrl
     */
    public function setYoutubeChannelUrl(?string $youtubeChannelUrl): void
    {
        $this->youtubeChannelUrl = $youtubeChannelUrl;
    }

    /**
     * @return string
     */
    public function getTwitchChannelUrl(): ?string
    {
        return $this->twitchChannelUrl;
    }

    /**
     * @param string|null $twitchChannelUrl
     */
    public function setTwitchChannelUrl(?string $twitchChannelUrl): void
    {
        $this->twitchChannelUrl = $twitchChannelUrl;
    }

    /**
     * @param MissionRegistry $registry
     * @return bool
     * @Ignore
     */
    public function hasAccessToMission(MissionRegistry $registry): bool
    {
        if (in_array(BaseUserRepository::ROLE_ROOT, $this->roles)) {
            return true;
        }

        $servers = $this->getServers();
        /** @var Server $server */
        foreach ($servers as $server) {
            $missions = $server->getMissionRegistry(0);
            if ($missions->contains($registry)) {
                return true;
            }
        }
        return false;
    }


    /**
     * @return Collection
     */
    public function getServers(): Collection
    {
        return $this->servers;
    }

    public function add(Server $server): Pilot
    {
        $this->servers->add($server);
        return $this;
    }

    public function removeServer(Server $server): Pilot
    {
        $this->servers->removeElement($server);
        return $this;
    }

    public function hasAccessToServer($object) : bool
    {
        if (in_array(BaseUserRepository::ROLE_ROOT, $this->roles)) {
            return true;
        }

        if ($object instanceof Server) {
            $servers = $this->getServers();
            foreach ($servers as $serv) {
                if($serv === $object) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * @return string|null
     */
    public function getUcid(): ?string
    {
        return $this->ucid;
    }

    /**
     * @param string $ucid
     */
    public function setUcid(string $ucid): void
    {
        $this->ucid = $ucid;
    }

    /**
     * @return ArrayCollection
     */
    public function getDogfights(): Collection
    {
        return $this->dogfights;
    }

    /**
     * @param Collection $dogfights
     */
    public function setDogfights(Collection $dogfights): void
    {
        $this->dogfights = $dogfights;
    }

    /**
     * @return Collection
     */
    public function getKills(): Collection
    {
        return $this->kills;
    }

    /**
     * @param Collection $kills
     */
    public function setKills(Collection $kills): void
    {
        $this->kills = $kills;
    }

    /**
     * @return Collection
     */
    public function getCurrentKills(): Collection
    {
        return $this->currentKills;
    }

    /**
     * @param Collection $currentKills
     */
    public function setCurrentKills(Collection $currentKills): void
    {
        $this->currentKills = $currentKills;
    }

    /**
     * @return Collection
     */
    public function getBestStreaks(): Collection
    {
        return $this->bestStreaks;
    }

    /**
     * @param Collection $bestStreaks
     */
    public function setBestStreaks(Collection $bestStreaks): void
    {
        $this->bestStreaks = $bestStreaks;
    }

    /**
     * @return Collection
     */
    public function getSorties(): Collection
    {
        return $this->sorties;
    }

    /**
     * @param Collection $sorties
     */
    public function setSorties(Collection $sorties): void
    {
        $this->sorties = $sorties;
    }

    /**
     * @return Collection
     */
    public function getVisits(): Collection
    {
        return $this->visits;
    }

    /**
     * @param Collection $visits
     */
    public function setVisits(Collection $visits): void
    {
        $this->visits = $visits;
    }

    /**
     */
    public function getBanRecords(): Collection
    {
        return $this->banRecords;
    }

    /**
     * @param Collection $banRecords
     */
    public function setBanRecords(Collection $banRecords): void
    {
        $this->banRecords = $banRecords;
    }

    /**
     * @return Collection
     */
    public function getRegistrationTickets(): Collection
    {
        return $this->registrationTickets;
    }

    /**
     * @param Collection $registrationTickets
     */
    public function setRegistrationTickets(Collection $registrationTickets): void
    {
        $this->registrationTickets = $registrationTickets;
    }

    /**
     * @return Collection
     */
    public function getEvents(): Collection
    {
        return $this->events;
    }

    /**
     * @param Collection $events
     */
    public function setEvents(Collection $events): void
    {
        $this->events = $events;
    }

    /**
     * @return Collection|null
     */
    public function getStreaks(): ?Collection
    {
        return $this->streaks;
    }

    /**
     * @param Collection $streaks
     */
    public function setStreaks(Collection $streaks): void
    {
        $this->streaks = $streaks;
    }

    /**
     * @return bool
     */
    public function isBanned(): bool
    {
        return $this->banned;
    }

    /**
     * @param bool $banned
     */
    public function setBanned(bool $banned): void
    {
        $this->banned = $banned;
    }

    /**
     * @return Collection
     */
    public function getTournamentStages(): Collection
    {
        return $this->tournamentStages;
    }

    public function addTournamentStage(TournamentStage $tournamentStage): self
    {
        if (!$this->tournamentStages->contains($tournamentStage)) {
            $this->tournamentStages[] = $tournamentStage;
            $tournamentStage->addParticipant($this);
        }

        return $this;
    }

    public function removeTournamentStage(TournamentStage $tournamentStage): self
    {
        if ($this->tournamentStages->removeElement($tournamentStage)) {
            $tournamentStage->removeParticipant($this);
        }

        return $this;
    }

    /**
     * @return Collection
     */
    public function getRaceRuns(): Collection
    {
        return $this->raceRuns;
    }

    public function addRaceRun(RaceRun $raceRun): self
    {
        if (!$this->raceRuns->contains($raceRun)) {
            $this->raceRuns[] = $raceRun;
            $raceRun->setPilot($this);
        }

        return $this;
    }

    public function removeRaceRun(RaceRun $raceRun): self
    {
        if ($this->raceRuns->removeElement($raceRun)) {
            // set the owning side to null (unless already changed)
            if ($raceRun->getPilot() === $this) {
                $raceRun->setPilot(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection
     */
    public function getTournamentCoupons(): Collection
    {
        return $this->tournamentCoupons;
    }

    public function addTournamentCoupon(TournamentCoupon $tournamentCoupon): self
    {
        if (!$this->tournamentCoupons->contains($tournamentCoupon)) {
            $this->tournamentCoupons[] = $tournamentCoupon;
            $tournamentCoupon->setPilot($this);
        }

        return $this;
    }

    public function removeTournamentCoupon(TournamentCoupon $tournamentCoupon): self
    {
        if ($this->tournamentCoupons->removeElement($tournamentCoupon)) {
            // set the owning side to null (unless already changed)
            if ($tournamentCoupon->getPilot() === $this) {
                $tournamentCoupon->setPilot(null);
            }
        }

        return $this;
    }

    public function getAcceptedRules(): ?bool
    {
        return $this->acceptedRules;
    }

    public function setAcceptedRules(bool $acceptedRules): self
    {
        $this->acceptedRules = $acceptedRules;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getUcidTokens(): Collection
    {
        return $this->ucidTokens;
    }

    public function addUcidToken(UcidToken $ucidToken): self
    {
        if (!$this->ucidTokens->contains($ucidToken)) {
            $this->ucidTokens[] = $ucidToken;
            $ucidToken->setPilot($this);
        }

        return $this;
    }

    public function removeUcidToken(UcidToken $ucidToken): self
    {
        if ($this->ucidTokens->removeElement($ucidToken)) {
            // set the owning side to null (unless already changed)
            if ($ucidToken->getPilot() === $this) {
                $ucidToken->setPilot(null);
            }
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getShortcode(): string
    {
        if (!empty($this->shortcode)) {
            return substr($this->shortcode, -6);
        }
        return $this->shortcode;
    }

    public function getRegion(): ?Region
    {
        return $this->region;
    }

    public function setRegion(?Region $region): self
    {
        $this->region = $region;

        return $this;
    }

    public function getIpCountry(): ?string
    {
        return $this->ipCountry;
    }

    public function setIpCountry(?string $ipCountry): self
    {
        $this->ipCountry = $ipCountry;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getTournamentCouponRequests(): Collection
    {
        return $this->tournamentCouponRequests;
    }

    public function addTournamentCouponRequest(TournamentCouponRequest $tournamentCouponRequest): self
    {
        if (!$this->tournamentCouponRequests->contains($tournamentCouponRequest)) {
            $this->tournamentCouponRequests[] = $tournamentCouponRequest;
            $tournamentCouponRequest->setPilot($this);
        }

        return $this;
    }

    public function removeTournamentCouponRequest(TournamentCouponRequest $tournamentCouponRequest): self
    {
        if ($this->tournamentCouponRequests->removeElement($tournamentCouponRequest)) {
            // set the owning side to null (unless already changed)
            if ($tournamentCouponRequest->getPilot() === $this) {
                $tournamentCouponRequest->setPilot(null);
            }
        }

        return $this;
    }

    public function getSquad(): ?string
    {
        return $this->squad;
    }

    public function setSquad(?string $squad): self
    {
        $this->squad = $squad;

        return $this;
    }

    public function getLanguage(): ?string
    {
        return $this->language;
    }

    public function setLanguage(?string $language): self
    {
        $this->language = $language;

        return $this;
    }

    public function getPublishPhoto(): ?bool
    {
        return $this->publishPhoto;
    }

    public function setPublishPhoto(bool $publishPhoto): self
    {
        $this->publishPhoto = $publishPhoto;

        return $this;
    }

    public function getVkProfileUrl(): ?string
    {
        return $this->vkProfileUrl;
    }

    public function setVkProfileUrl(?string $vkProfileUrl): self
    {
        $this->vkProfileUrl = $vkProfileUrl;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): self
    {
        $this->photo = $photo;
        return $this;
    }

    public function setPhotoFile(File $image = null): void
    {
        $this->photoFile = $image;
        if ($image) {
            $this->setUpdatedAt(new DateTime('now'));
        }
    }

    public function getPhotoFile(): ?File
    {
        return $this->photoFile;
    }

    /**
     * @return string|null
     */
    public function getSquadLogo(): ?string
    {
        return $this->squadLogo;
    }

    /**
     * @param string|null $squadLogo
     */
    public function setSquadLogo(?string $squadLogo): void
    {
        $this->squadLogo = $squadLogo;
    }

    /**
     * @return File|null
     */
    public function getSquadLogoFile(): ?File
    {
        return $this->squadLogoFile;
    }

    /**
     * @param File|null $image
     */
    public function setSquadLogoFile(File $image = null): void
    {
        $this->squadLogoFile = $image;
        if ($image) {
            $this->setUpdatedAt(new DateTime('now'));
        }
    }

    public function getAvatar()
    {
        return ($this->publishPhoto) ? $this->photo : 'avatar.png';
    }
}
