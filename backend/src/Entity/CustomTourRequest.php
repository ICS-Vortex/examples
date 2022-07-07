<?php

namespace App\Entity;

use App\Includes\Timestamp;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="custom_tour_requests")
 * @ORM\Entity(repositoryClass="App\Repository\CustomTourRequestRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class CustomTourRequest
{
    use Timestamp;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank
     */
    private $start;

    /**
     * @ORM\Column(type="boolean")
     */
    private $started = false;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $title;
    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $titleEn;

    public function getId()
    {
        return $this->id;
    }

    /**
     * @return \DateTime
     */
    public function getStart()
    {
        return $this->start;
    }

    public function setStart($start)
    {
        $this->start = $start;

        return $this;
    }

    public function getStarted()
    {
        return $this->started;
    }

    public function setStarted($started)
    {
        $this->started = $started;

        return $this;
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
}
