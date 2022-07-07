<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Includes\Timestamp;
use App\Repository\CustomPageRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=CustomPageRepository::class)
 * @ORM\HasLifecycleCallbacks
 */
class CustomPage
{
    use Timestamp;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"api_open_custom_pages", "api_tournaments"})
     */
    private $id;

    /**
     * @ORM\Column(name="url", type="string", length=255)
     * @Assert\NotBlank
     * @Groups({"api_open_custom_pages", "api_tournaments"})
     */
    private $url;
    /**
     * @ORM\Column(name="title_en", type="string", length=255)
     * @Assert\NotBlank
     * @Groups({"api_open_custom_pages", "api_tournaments"})
     */
    private $titleEn;
    /**
     * @ORM\Column(name="title_ru", type="string", length=255)
     * @Assert\NotBlank
     * @Groups({"api_open_custom_pages", "api_tournaments"})
     */
    private $titleRu;

    /**
     * @ORM\Column(name="content_en", type="text", nullable=true)
     * @Groups({"api_open_custom_pages", "api_tournaments"})
     */
    private $contentEn;

    /**
     * @ORM\Column(name="content_ru", type="text", nullable=true)
     * @Groups({"api_open_custom_pages", "api_tournaments"})
     */
    private $contentRu;

    /**
     * @ORM\Column(name="is_public", type="boolean", options={"default" : false})
     */
    private $public = false;

    /**
     * @ORM\ManyToOne(targetEntity=Tournament::class, inversedBy="customPages")
     */
    private $tournament;

    /**
     * @ORM\Column(type="integer", options={"default": 0})
     * @ORM\OrderBy({"positin"="ASC"})
     */
    private $position = 0;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getContentEn(): ?string
    {
        return $this->contentEn;
    }

    public function setContentEn(?string $content_en): self
    {
        $this->contentEn = $content_en;

        return $this;
    }

    public function getContentRu(): ?string
    {
        return $this->contentRu;
    }

    public function setContentRu(?string $content_ru): self
    {
        $this->contentRu = $content_ru;

        return $this;
    }

    public function getPublic(): ?bool
    {
        return $this->public;
    }

    public function isPublic(): ?bool
    {
        return $this->public;
    }

    public function setPublic(bool $public): self
    {
        $this->public = $public;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTitleEn() : string
    {
        return $this->titleEn;
    }

    /**
     * @param mixed $titleEn
     */
    public function setTitleEn($titleEn): void
    {
        $this->titleEn = $titleEn;
    }

    /**
     * @return mixed
     */
    public function getTitleRu() : string
    {
        return $this->titleRu;
    }

    /**
     * @param mixed $titleRu
     */
    public function setTitleRu($titleRu): void
    {
        $this->titleRu = $titleRu;
    }

    public function getTournament(): ?Tournament
    {
        return $this->tournament;
    }

    public function setTournament(?Tournament $tournament): self
    {
        $this->tournament = $tournament;

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
