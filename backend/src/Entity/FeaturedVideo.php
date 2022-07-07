<?php

namespace App\Entity;

use App\Includes\Timestamp;
use App\Repository\FeaturedVideoRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=FeaturedVideoRepository::class)
 * @ORM\Table(name="featured_videos")
 * @ORM\HasLifecycleCallbacks
 */
class FeaturedVideo
{
    use Timestamp;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"api_featured_video", "api_open_servers"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"api_featured_video", "api_open_servers"})
     */
    private $url;

    /**
     * @ORM\Column(type="string", length=64)
     * @Groups({"api_featured_video", "api_open_servers"})
     */
    private $code;

    /**
     * @ORM\ManyToOne(targetEntity=Server::class, inversedBy="featuredVideos")
     */
    private $server;
    /**
     * @var int|null
     * @ORM\Column(type="integer")
     */
    private $orderNumber = 100;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getServer(): ?Server
    {
        return $this->server;
    }

    public function setServer(?Server $server): self
    {
        $this->server = $server;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getOrderNumber(): ?int
    {
        return $this->orderNumber;
    }

    /**
     * @param int|null $orderNumber
     */
    public function setOrderNumber(?int $orderNumber): void
    {
        $this->orderNumber = $orderNumber;
    }
}
