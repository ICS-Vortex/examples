<?php

namespace App\Entity;

use App\Includes\Timestamp;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * Tour
 *
 * @ORM\Table(name="tours")
 * @ORM\Entity(repositoryClass="App\Repository\TourRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Tour
{
    use Timestamp;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups({"api_tour"})
     */
    private $id;
    /**
     * @var DateTime
     *
     * @ORM\Column(name="start_time", type="datetime")
     * @Groups({"api_tour"})
     */
    private $start;
    /**
     * @var DateTime
     *
     * @ORM\Column(name="end_time", type="datetime", nullable=true)
     * @Groups({"api_tour"})
     */
    private $end;
    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     * @Groups({"api_tour"})
     */
    private $title;
    /**
     * @var string
     *
     * @ORM\Column(name="title_en", type="string", length=255)
     * @Groups({"api_tour"})
     */
    private $titleEn;
    /**
     * @var bool
     *
     * @ORM\Column(name="finished", type="boolean", options={"default":0})
     * @Groups({"api_tour"})
     */
    private $finished = false;

    /**
     * @var Collection
     * @ORM\OneToMany(targetEntity="App\Entity\MissionRegistry", mappedBy="tour", cascade={"remove"})
     */
    private $missionRegistries;

    #[Pure]
    public function __construct()
    {
        $this->missionRegistries = new ArrayCollection();
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
     * Set start
     *
     * @param DateTime $start
     *
     * @return Tour
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

    public function getTourStart()
    {
        return $this->start->format('Y-m-d H:i:s');
    }

    public function getTourEnd()
    {
        if (empty($this->end)) {
            return date('Y-m-t 23:59:59');
        }
        return $this->end->format('Y-m-d H:i:s');
    }

    /**
     * Set end
     *
     * @param DateTime $end
     *
     * @return Tour
     */
    public function setEnd($end): static
    {
        $this->end = $end;

        return $this;
    }

    /**
     * Get end
     *
     * @return DateTime|null
     */
    public function getEnd(): ?DateTime
    {
        return $this->end;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Tour
     */
    public function setTitle($title): static
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Set finished
     *
     * @param boolean $finished
     *
     * @return Tour
     */
    public function setFinished($finished): static
    {
        $this->finished = $finished;

        return $this;
    }

    /**
     * Get finished
     *
     * @return boolean
     */
    public function getFinished(): bool
    {
        return $this->finished;
    }

    /**
     * @return string
     */
    public function getTitleEn(): ?string
    {
        return $this->titleEn;
    }

    /**
     * @param string $titleEn
     */
    public function setTitleEn(string $titleEn): void
    {
        $this->titleEn = $titleEn;
    }

    public function __toString(): string
    {
        return $this->title . ' | ' . $this->titleEn;
    }
}
