<?php

namespace App\Entity;

use App\Includes\Timestamp;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;


/**
 * @ORM\Table(name="slides")
 * @ORM\Entity(repositoryClass="App\Repository\SlideRepository")
 * @Vich\Uploadable
 * @ORM\HasLifecycleCallbacks()
 */
class Slide
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
     * @Vich\UploadableField(mapping="slider_images", fileNameProperty="image")
     * @var File
     */
    private $imageFile;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $url;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $urlTitle;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $urlTitleEn;

    /**
     * @ORM\Column(type="integer")
     */
    private $orderNumber = 1;

    public function getId()
    {
        return $this->id;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;

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

    public function getUrl()
    {
        return $this->url;
    }

    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getUrlTitle()
    {
        return $this->urlTitle;
    }

    /**
     * @param mixed $urlTitle
     */
    public function setUrlTitle($urlTitle)
    {
        $this->urlTitle = $urlTitle;
    }

    /**
     * @return mixed
     */
    public function getTitleEn()
    {
        return $this->titleEn;
    }

    /**
     * @param mixed $titleEn
     */
    public function setTitleEn($titleEn)
    {
        $this->titleEn = $titleEn;
    }

    /**
     * @return mixed
     */
    public function getDescriptionEn()
    {
        return $this->descriptionEn;
    }

    /**
     * @param mixed $descriptionEn
     */
    public function setDescriptionEn($descriptionEn)
    {
        $this->descriptionEn = $descriptionEn;
    }

    /**
     * @return mixed
     */
    public function getUrlTitleEn()
    {
        return $this->urlTitleEn;
    }

    /**
     * @param mixed $urlTitleEn
     */
    public function setUrlTitleEn($urlTitleEn)
    {
        $this->urlTitleEn = $urlTitleEn;
    }

    public function getImageFile()
    {
        return $this->imageFile;
    }

    public function setImageFile(File $image = null)
    {
        $this->imageFile = $image;

        if ($image) {
            $this->updatedAt = new DateTime();
        }
    }

    /**
     * @return int
     */
    public function getOrderNumber(): int
    {
        return $this->orderNumber;
    }

    /**
     * @param int $orderNumber
     */
    public function setOrderNumber(int $orderNumber): void
    {
        $this->orderNumber = $orderNumber;
    }
}
