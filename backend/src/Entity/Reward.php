<?php

namespace App\Entity;

use App\Includes\Timestamp;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rewards")
 * @ORM\Entity(repositoryClass="App\Repository\RewardRepository")
 */
class Reward
{
    use Timestamp;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $titleEn;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $descriptionEn;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $image;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $func;

    /**
     * @ORM\Column(type="boolean", options={"default":0})
     */
    private $lifetimeReward = false;


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

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    public function getFunc(): ?string
    {
        return $this->func;
    }

    public function setFunc(string $func): self
    {
        $this->func = $func;

        return $this;
    }

    /**
     * @return mixed
     */
    public function isLifetimeReward()
    {
        return $this->lifetimeReward;
    }

    /**
     * @param mixed $lifetimeReward
     */
    public function setLifetimeReward($lifetimeReward): void
    {
        $this->lifetimeReward = $lifetimeReward;
    }
}
