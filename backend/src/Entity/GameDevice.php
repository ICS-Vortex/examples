<?php

namespace App\Entity;

use App\Includes\Timestamp;
use App\Repository\GameDeviceRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Table(name="game_devices")
 * @ORM\Entity(repositoryClass=GameDeviceRepository::class)
 * @ORM\HasLifecycleCallbacks()
 * @Vich\Uploadable
 */
class GameDevice
{
    use Timestamp;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"api_game_devices", "api_open_servers", "api_profile"})
     */
    private $id;
    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"api_game_devices", "api_open_servers", "api_profile"})
     */
    private $name;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"api_game_devices", "api_open_servers", "api_profile"})
     */
    private $image;
    /**
     * @Vich\UploadableField(mapping="devices_images", fileNameProperty="image")
     * @var File
     */
    private $imageFile;

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

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
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

    public function __toString() : string
    {
       return $this->name;
    }


}
