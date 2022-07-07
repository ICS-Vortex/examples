<?php

namespace App\Entity;

use App\Repository\RaceTypeRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=RaceTypeRepository::class)
 * @ORM\Table(name="race_types")
 */
class RaceType
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"api_race_types"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"api_race_types"})
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"api_race_types"})
     */
    private $titleEn;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"api_race_types"})
     */
    private $type;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"api_race_types"})
     */
    private $position = 1;

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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(int $position): self
    {
        $this->position = $position;

        return $this;
    }
}
