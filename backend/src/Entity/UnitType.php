<?php

namespace App\Entity;

use App\Includes\Timestamp;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * DcsUnitsTypes
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="unit_types")
 * @ORM\Entity(repositoryClass="App\Repository\UnitTypeRepository")
 */
class UnitType
{
    use Timestamp;
    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue
     * @Groups({"api_open_servers"})
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=64, unique=true)
     * @Groups({"api_open_servers"})
     */
    private $title;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return UnitType
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    public function __toString() : string
    {
        return $this->title ?? (string)$this->id;
    }
}
