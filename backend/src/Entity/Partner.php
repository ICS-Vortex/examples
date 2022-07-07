<?php

namespace App\Entity;

use App\Includes\Timestamp;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * Partner
 *
 * @ORM\Table(name="partners")
 * @ORM\Entity(repositoryClass="App\Repository\PartnerRepository")
 * @Vich\Uploadable
 * @ORM\HasLifecycleCallbacks()
 */
class Partner
{
    use Timestamp;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $url;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=255)
     */
    private $image;
    /**
     * @Vich\UploadableField(mapping="partners_images", fileNameProperty="image")
     * @var File
     */
    private $imageFile;

    /**
     * Get id
     *
     * @return int
     */
    public function getId() : ?int
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Partner
     */
    public function setTitle($title) : self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle() : ?string
    {
        return $this->title;
    }

    /**
     * Set url
     *
     * @param string $url
     *
     * @return Partner
     */
    public function setUrl($url) : self
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl() : ?string
    {
        return $this->url;
    }

    /**
     * Set image
     *
     * @param string $image
     *
     * @return Partner
     */
    public function setImage($image) : self
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage() : ?string
    {
        return $this->image;
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
}
