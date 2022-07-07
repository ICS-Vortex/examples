<?php

namespace App\Entity;

use App\Includes\Timestamp;
use App\Repository\TheatreRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=TheatreRepository::class)
 * @ORM\Table(name="theatres")
 * @ORM\HasLifecycleCallbacks
 */
class Theatre
{
    use Timestamp;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"api_mission", "api_tournaments"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"api_mission", "api_tournaments"})
     */
    private $name;
    /**
     * @ORM\Column(name="night_start", type="time", nullable=true)
     * @Groups({"api_mission", "api_tournaments"})
     */
    private $nightStart;
    /**
     * @ORM\Column(name="night_end", type="time", nullable=true)
     * @Groups({"api_mission", "api_tournaments"})
     */
    private $nightEnd;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getNightStart()
    {
        return $this->nightStart;
    }

    /**
     * @param mixed $nightStart
     */
    public function setNightStart($nightStart): void
    {
        $this->nightStart = $nightStart;
    }

    /**
     * @return mixed
     */
    public function getNightEnd()
    {
        return $this->nightEnd;
    }

    /**
     * @param mixed $nightEnd
     */
    public function setNightEnd($nightEnd): void
    {
        $this->nightEnd = $nightEnd;
    }
}
