<?php

namespace App\Entity\Location;

use App\Entity\Pilot;
use App\Includes\Timestamp;
use App\Repository\Location\RegionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=RegionRepository::class)
 * @ORM\Table(name="location_regions")
 * @ORM\HasLifecycleCallbacks
 */
class Region
{
    use Timestamp;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"api_admin","api_open_servers", "api_tournaments", "api_profile"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"api_admin","api_open_servers", "api_tournaments", "api_profile"})
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"api_admin","api_open_servers", "api_tournaments", "api_profile"})
     */
    private $titleEn;
    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"api_admin","api_open_servers", "api_tournaments", "api_profile"})
     */
    private $couponDescription;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"api_admin","api_open_servers", "api_tournaments", "api_profile"})
     */
    private $couponDescriptionEn;


    /**
     * @ORM\OneToMany(targetEntity=Pilot::class, mappedBy="region")
     */
    private $pilots;

    public function __construct()
    {
        $this->pilots = new ArrayCollection();
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

    public function __toString()
    {
        return $this->titleEn . ' | ' . $this->title;
    }

    /**
     * @return Collection|Pilot[]
     */
    public function getPilots(): Collection
    {
        return $this->pilots;
    }

    public function addPilot(Pilot $pilot): self
    {
        if (!$this->pilots->contains($pilot)) {
            $this->pilots[] = $pilot;
            $pilot->setRegion($this);
        }

        return $this;
    }

    public function removePilot(Pilot $pilot): self
    {
        if ($this->pilots->removeElement($pilot)) {
            if ($pilot->getRegion() === $this) {
                $pilot->setRegion(null);
            }
        }

        return $this;
    }

    public function getCouponDescription(): ?string
    {
        return $this->couponDescription;
    }

    public function setCouponDescription(?string $couponDescription): self
    {
        $this->couponDescription = $couponDescription;

        return $this;
    }

    public function getCouponDescriptionEn(): ?string
    {
        return $this->couponDescriptionEn;
    }

    public function setCouponDescriptionEn(?string $couponDescriptionEn): self
    {
        $this->couponDescriptionEn = $couponDescriptionEn;

        return $this;
    }
}
