<?php

namespace App\Entity;

use App\Includes\Timestamp;
use App\Repository\WeatherLimitRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=WeatherLimitRepository::class)
 * @ORM\Table(name="weather_limits")
 * @ORM\HasLifecycleCallbacks
 */
class WeatherLimit
{
    use Timestamp;
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @var ?string
     *
     * @ORM\Column(name="title", type="string")
     */
    private $title;

    /**
     * @var ?int
     *
     * @ORM\Column(name="clouds_base_day", type="integer")
     */
    private $cloudsBaseDay;
    /**
     * @var ?int
     *
     * @ORM\Column(name="clouds_base_night", type="integer")
     */
    private $cloudsBaseNight;
    /**
     * @var ?int
     *
     * @ORM\Column(name="visibility_day", type="integer")
     */
    private $visibilityDay;
    /**
     * @var ?int
     *
     * @ORM\Column(name="visibility_night", type="integer")
     */
    private $visibilityNight;

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string|null $title
     */
    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return int|null
     */
    public function getCloudsBaseDay(): ?int
    {
        return $this->cloudsBaseDay;
    }

    /**
     * @param int|null $cloudsBaseDay
     */
    public function setCloudsBaseDay(?int $cloudsBaseDay): void
    {
        $this->cloudsBaseDay = $cloudsBaseDay;
    }

    /**
     * @return int|null
     */
    public function getCloudsBaseNight(): ?int
    {
        return $this->cloudsBaseNight;
    }

    /**
     * @param int|null $cloudsBaseNight
     */
    public function setCloudsBaseNight(?int $cloudsBaseNight): void
    {
        $this->cloudsBaseNight = $cloudsBaseNight;
    }

    /**
     * @return int|null
     */
    public function getVisibilityDay(): ?int
    {
        return $this->visibilityDay;
    }

    /**
     * @param int|null $visibilityDay
     */
    public function setVisibilityDay(?int $visibilityDay): void
    {
        $this->visibilityDay = $visibilityDay;
    }

    /**
     * @return int|null
     */
    public function getVisibilityNight(): ?int
    {
        return $this->visibilityNight;
    }

    /**
     * @param int|null $visibilityNight
     */
    public function setVisibilityNight(?int $visibilityNight): void
    {
        $this->visibilityNight = $visibilityNight;
    }

    public function __toString() : string
    {
        return $this->title;
    }
}
