<?php


namespace App\Includes;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

trait Timestamp
{
    /**
     * @ORM\Column(type="datetime", options={"default":"2000-01-01 00:00:00"})
     * @Groups({"api_articles"})
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", options={"default":"2000-01-01 00:00:00"})
     * @Groups({"api_articles"})
     */
    private $updatedAt;

    /**
     * @ORM\PrePersist()
     */
    public function createdAt()
    {
        if (empty($this->createdAt)) {
            $this->createdAt = new DateTime();
        }
        if (empty($this->updatedAt)) {
            $this->updatedAt = new DateTime();
        }
    }

    /**
     * @ORM\PreUpdate()
     */
    public function updatedAt()
    {
        $this->updatedAt = new DateTime();
    }

    /**
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     */
    public function setCreatedAt($createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     */
    public function setUpdatedAt($updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }
}
