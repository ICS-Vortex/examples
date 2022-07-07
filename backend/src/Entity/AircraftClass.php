<?php

namespace App\Entity;

use App\Includes\Timestamp;
use App\Repository\AircraftClassRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=AircraftClassRepository::class)
 * @ORM\Table(name="aircraft_classes")
 * @Vich\Uploadable
 * @ORM\HasLifecycleCallbacks
 */
class AircraftClass
{
    use Timestamp;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"api_tournaments"})
     */
    private $id;

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
     * @ORM\ManyToMany(targetEntity=Plane::class, inversedBy="aircraftClasses")
     * @Groups({"api_tournaments"})
     */
    private $aircrafts;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"api_tournaments"})
     */
    private $image;
    /**
     * @Vich\UploadableField(mapping="aircrafts_classes_images", fileNameProperty="image")
     * @var File
     */
    private $imageFile;

    /**
     * @ORM\OneToMany(targetEntity=Tournament::class, mappedBy="aircraftsClass", orphanRemoval=true)
     */
    private $tournaments;

    /**
     * @ORM\OneToMany(targetEntity=RaceRun::class, mappedBy="aircraftClass", orphanRemoval=true)
     */
    private $raceRuns;

    public function __construct()
    {
        $this->aircrafts = new ArrayCollection();
        $this->tournaments = new ArrayCollection();
        $this->raceRuns = new ArrayCollection();
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
     * @return Collection|Plane[]
     */
    public function getAircrafts(): Collection
    {
        return $this->aircrafts;
    }

    public function addAircraft(Plane $aircraft): self
    {
        if (!$this->aircrafts->contains($aircraft)) {
            $this->aircrafts[] = $aircraft;
        }

        return $this;
    }

    public function removeAircraft(Plane $aircraft): self
    {
        $this->aircrafts->removeElement($aircraft);

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image = null): self
    {
        $this->image = $image;

        return $this;
    }

    public function setImageFile(File $image = null) : void
    {
        $this->imageFile = $image;
        if ($image) {
            $this->updatedAt = new \DateTime('now');
        }
    }

    public function getImageFile() : ?File
    {
        return $this->imageFile;
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
            $tournament->setAircraftsClass($this);
        }

        return $this;
    }

    public function removeTournament(Tournament $tournament): self
    {
        if ($this->tournaments->removeElement($tournament)) {
            // set the owning side to null (unless already changed)
            if ($tournament->getAircraftsClass() === $this) {
                $tournament->setAircraftsClass(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->title . ' | ' . $this->titleEn;
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
            $raceRun->setAircraftClass($this);
        }

        return $this;
    }

    public function removeRaceRun(RaceRun $raceRun): self
    {
        if ($this->raceRuns->removeElement($raceRun)) {
            // set the owning side to null (unless already changed)
            if ($raceRun->getAircraftClass() === $this) {
                $raceRun->setAircraftClass(null);
            }
        }

        return $this;
    }

}
