<?php

namespace App\Entity;

use App\Includes\Timestamp;
use App\Repository\TournamentRepository;
use App\Repository\TournamentStageRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=TournamentRepository::class)
 * @ORM\Table(name="tournaments")
 * @Vich\Uploadable
 * @ORM\HasLifecycleCallbacks
 */
class Tournament
{
    use Timestamp;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"api_tournaments", "admin_coupons","tournament_coupons"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"api_tournaments", "admin_coupons","tournament_coupons"})
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"api_tournaments", "admin_coupons","tournament_coupons"})
     */
    private $titleEn;

    /**
     * @ORM\Column(type="text")
     * @Groups({"api_tournaments"})
     */
    private $description;

    /**
     * @ORM\Column(type="text")
     * @Groups({"api_tournaments"})
     */
    private $descriptionEn;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"api_tournaments","tournament_coupons"})
     */
    private $start;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"api_tournaments","tournament_coupons"})
     */
    private $end;

    /**
     * @ORM\Column(type="boolean", options={"default":0})
     * @Groups({"api_tournaments","tournament_coupons"})
     */
    private $finished = false;

    /**
     * @ORM\OneToMany(targetEntity=TournamentRequest::class, mappedBy="tournament", orphanRemoval=true)
     */
    private $tournamentRequests;

    /**
     * @ORM\ManyToMany(targetEntity=Server::class, inversedBy="tournaments")
     * @Groups({"api_tournaments"})
     */
    private $servers;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"api_tournaments"})
     */
    private $banner;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"api_tournaments"})
     */
    private $bannerEn;
    /**
     * @Vich\UploadableField(mapping="tournaments_banners_images", fileNameProperty="banner")
     * @var File
     */
    private $bannerFile;
    /**
     * @Vich\UploadableField(mapping="tournaments_banners_images", fileNameProperty="bannerEn")
     * @var File
     */
    private $bannerEnFile;

    /**
     * @ORM\OneToMany(targetEntity=TournamentStage::class, mappedBy="tournament", orphanRemoval=true)
     * @Groups({"api_tournaments"})
     * @ORM\OrderBy({"position" = "ASC"})
     */
    private $stages;

    /**
     * @ORM\ManyToOne(targetEntity=AircraftClass::class, inversedBy="tournaments")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"api_tournaments"})
     */
    private $aircraftsClass;

    /**
     * @ORM\OneToMany(targetEntity=RaceRun::class, mappedBy="tournament", orphanRemoval=true)
     */
    private $raceRuns;

    /**
     * @ORM\OneToMany(targetEntity=Faq::class, mappedBy="tournament")
     * @Groups({"api_tournaments"})
     */
    private $faqs;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"api_tournaments"})
     */
    private $hidden;

    /**
     * @ORM\OneToMany(targetEntity=TournamentCoupon::class, mappedBy="tournament", orphanRemoval=true)
     */
    private $tournamentCoupons;

    /**
     * @ORM\Column(type="boolean", options={"default": false})
     * @Groups({"api_tournaments"})
     */
    private $provideCoupons = false;

    /**
     * @ORM\OneToMany(targetEntity=CustomPage::class, mappedBy="tournament")
     * @Groups({"api_tournaments"})
     */
    private $customPages;

    /**
     * @ORM\OneToMany(targetEntity=CouponFile::class, mappedBy="tournament", orphanRemoval=true)
     */
    private $couponFiles;

    /**
     * @ORM\Column(type="boolean", options={"default": false})
     */
    private $googleSheetExport = false;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $googleSheetId;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $googleSheetTab;

    /**
     * @ORM\OneToMany(targetEntity=TournamentCouponRequest::class, mappedBy="tournament", orphanRemoval=true)
     */
    private $tournamentCouponRequests;

    #[Pure]
    public function __construct()
    {
        $this->tournamentRequests = new ArrayCollection();
        $this->servers = new ArrayCollection();
        $this->stages = new ArrayCollection();
        $this->raceRuns = new ArrayCollection();
        $this->faqs = new ArrayCollection();
        $this->tournamentCoupons = new ArrayCollection();
        $this->customPages = new ArrayCollection();
        $this->couponFiles = new ArrayCollection();
        $this->tournamentCouponRequests = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->title . ' / ' . $this->titleEn;
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDescriptionEn(): ?string
    {
        return $this->descriptionEn;
    }

    public function setDescriptionEn(string $descriptionEn): self
    {
        $this->descriptionEn = $descriptionEn;

        return $this;
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

    public function getFinished(): ?bool
    {
        return $this->finished;
    }

    public function setFinished(bool $finished): self
    {
        $this->finished = $finished;

        return $this;
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
            $tournamentRequest->setTournament($this);
        }

        return $this;
    }

    public function removeTournamentRequest(TournamentRequest $tournamentRequest): self
    {
        if ($this->tournamentRequests->removeElement($tournamentRequest)) {
            // set the owning side to null (unless already changed)
            if ($tournamentRequest->getTournament() === $this) {
                $tournamentRequest->setTournament(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Server[]
     */
    public function getServers(): Collection
    {
        return $this->servers;
    }

    public function addServer(Server $server): self
    {
        if (!$this->servers->contains($server)) {
            $this->servers[] = $server;
        }

        return $this;
    }

    public function removeServer(Server $server): self
    {
        $this->servers->removeElement($server);

        return $this;
    }

    public function getBanner(): ?string
    {
        return $this->banner;
    }

    public function setBanner(?string $banner): self
    {
        $this->banner = $banner;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getBannerEn(): ?string
    {
        return $this->bannerEn;
    }

    /**
     * @param string|null $bannerEn
     */
    public function setBannerEn(?string $bannerEn): void
    {
        $this->bannerEn = $bannerEn;
    }

    public function setBannerFile(File $image = null): void
    {
        $this->bannerFile = $image;
        if ($image) {
            $this->updatedAt = new DateTime();
        }
    }

    public function getBannerFile(): ?File
    {
        return $this->bannerFile;
    }

    public function setBannerEnFile(File $image = null): void
    {
        $this->bannerEnFile = $image;
        if ($image) {
            $this->updatedAt = new DateTime();
        }
    }

    public function getBannerEnFile(): ?File
    {
        return $this->bannerEnFile;
    }

    /**
     * @return Collection
     */
    public function getStages(): Collection
    {
        $result = new ArrayCollection();
        /** @var TournamentStage $stage */
        foreach ($this->stages as $stage) {
            if (!$stage->isHidden()) {
                $result->add($stage);
            }
        }
        return $result;
    }

    public function addStage(TournamentStage $stage): self
    {
        if (!$this->stages->contains($stage)) {
            $this->stages[] = $stage;
            $stage->setTournament($this);
        }

        return $this;
    }

    public function removeStage(TournamentStage $stage): self
    {
        if ($this->stages->removeElement($stage)) {
            // set the owning side to null (unless already changed)
            if ($stage->getTournament() === $this) {
                $stage->setTournament(null);
            }
        }

        return $this;
    }

    public function getCurrentStage() :?TournamentStage
    {
        $now = new DateTime();
        /** @var TournamentStage $stage */
        foreach ($this->stages as $stage) {
            if ($now->getTimestamp() > $stage->getStart()->getTimestamp() && $now->getTimestamp() < $stage->getEnd()->getTimestamp()) {
                return $stage;
            }
        }
        return null;
    }

    public function getQualificationStage(): ?TournamentStage
    {
        /** @var TournamentStage $stage */
        foreach ($this->stages as $stage) {
            if ($stage->getCode() === TournamentStageRepository::STAGE_QUALIFICATION) {
                return $stage;
            }
        }
        return null;
    }

    public function getAircraftsClass(): ?AircraftClass
    {
        return $this->aircraftsClass;
    }

    public function setAircraftsClass(?AircraftClass $aircraftsClass): self
    {
        $this->aircraftsClass = $aircraftsClass;

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
            $raceRun->setTournament($this);
        }

        return $this;
    }

    public function removeRaceRun(RaceRun $raceRun): self
    {
        if ($this->raceRuns->removeElement($raceRun)) {
            // set the owning side to null (unless already changed)
            if ($raceRun->getTournament() === $this) {
                $raceRun->setTournament(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Faq[]
     */
    public function getFaqs(): Collection
    {
        return $this->faqs;
    }

    public function addFaq(Faq $faq): self
    {
        if (!$this->faqs->contains($faq)) {
            $this->faqs[] = $faq;
            $faq->setTournament($this);
        }

        return $this;
    }

    public function removeFaq(Faq $faq): self
    {
        if ($this->faqs->removeElement($faq)) {
            // set the owning side to null (unless already changed)
            if ($faq->getTournament() === $this) {
                $faq->setTournament(null);
            }
        }

        return $this;
    }

    public function getHidden(): ?bool
    {
        return $this->hidden;
    }

    public function setHidden(bool $hidden): self
    {
        $this->hidden = $hidden;

        return $this;
    }

    /**
     * @return Collection|TournamentCoupon[]
     */
    public function getTournamentCoupons(): Collection
    {
        return $this->tournamentCoupons;
    }

    public function addTournamentCoupon(TournamentCoupon $tournamentCoupon): self
    {
        if (!$this->tournamentCoupons->contains($tournamentCoupon)) {
            $this->tournamentCoupons[] = $tournamentCoupon;
            $tournamentCoupon->setTournament($this);
        }

        return $this;
    }

    public function removeTournamentCoupon(TournamentCoupon $tournamentCoupon): self
    {
        if ($this->tournamentCoupons->removeElement($tournamentCoupon)) {
            // set the owning side to null (unless already changed)
            if ($tournamentCoupon->getTournament() === $this) {
                $tournamentCoupon->setTournament(null);
            }
        }

        return $this;
    }

    public function getProvideCoupons(): ?bool
    {
        return $this->provideCoupons;
    }

    public function setProvideCoupons(bool $provideCoupons): self
    {
        $this->provideCoupons = $provideCoupons;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getCustomPages(): Collection
    {
        $result = new ArrayCollection();
        /** @var CustomPage $page */
        foreach ($this->customPages as $page) {
            if ($page->isPublic()) {
                $result->add($page);
            }
        }
        return $result;
    }

    public function addCustomPage(CustomPage $customPage): self
    {
        if (!$this->customPages->contains($customPage)) {
            $this->customPages[] = $customPage;
            $customPage->setTournament($this);
        }

        return $this;
    }

    public function removeCustomPage(CustomPage $customPage): self
    {
        if ($this->customPages->removeElement($customPage)) {
            // set the owning side to null (unless already changed)
            if ($customPage->getTournament() === $this) {
                $customPage->setTournament(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|CouponFile[]
     */
    public function getCouponFiles(): Collection
    {
        return $this->couponFiles;
    }

    public function addCouponFile(CouponFile $couponFile): self
    {
        if (!$this->couponFiles->contains($couponFile)) {
            $this->couponFiles[] = $couponFile;
            $couponFile->setTournament($this);
        }

        return $this;
    }

    public function removeCouponFile(CouponFile $couponFile): self
    {
        if ($this->couponFiles->removeElement($couponFile)) {
            // set the owning side to null (unless already changed)
            if ($couponFile->getTournament() === $this) {
                $couponFile->setTournament(null);
            }
        }

        return $this;
    }

    public function getGoogleSheetExport(): ?bool
    {
        return $this->googleSheetExport;
    }

    public function setGoogleSheetExport(bool $googleSheetExport): self
    {
        $this->googleSheetExport = $googleSheetExport;

        return $this;
    }

    public function getGoogleSheetId(): ?string
    {
        return $this->googleSheetId;
    }

    public function setGoogleSheetId(string $googleSheetId): self
    {
        $this->googleSheetId = $googleSheetId;

        return $this;
    }

    public function getGoogleSheetTab(): ?string
    {
        return $this->googleSheetTab;
    }

    public function setGoogleSheetTab(?string $googleSheetTab): self
    {
        $this->googleSheetTab = $googleSheetTab;

        return $this;
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
            $tournamentCouponRequest->setTournament($this);
        }

        return $this;
    }

    public function removeTournamentCouponRequest(TournamentCouponRequest $tournamentCouponRequest): self
    {
        if ($this->tournamentCouponRequests->removeElement($tournamentCouponRequest)) {
            // set the owning side to null (unless already changed)
            if ($tournamentCouponRequest->getTournament() === $this) {
                $tournamentCouponRequest->setTournament(null);
            }
        }

        return $this;
    }
}
