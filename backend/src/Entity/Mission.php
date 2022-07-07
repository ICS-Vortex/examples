<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Mission
 *
 * @ORM\Table(name="missions",indexes={@ORM\Index(name="idx_name", columns={"name"})})
 * @ORM\Entity(repositoryClass="App\Repository\MissionRepository")
 */
class Mission
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups({"api_open_servers", "api_tournaments", "api_open_servers", "api_mission"})
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=128)
     * @Groups({"api_open_servers", "api_tournaments", "api_open_servers", "api_mission"})
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     * @Groups({"api_open_servers", "api_tournaments", "api_open_servers", "api_mission"})
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="description_en", type="text", nullable=true)
     * @Groups({"api_open_servers", "api_tournaments", "api_open_servers", "api_mission"})
     */
    private $descriptionEn;

    /**
     * @var string
     *
     * @ORM\Column(name="general_task", type="text", nullable=true)
     * @Groups({"api_open_servers", "api_tournaments", "api_open_servers", "api_mission"})
     */
    private $generalTask;
    /**
     * @var string
     *
     * @ORM\Column(name="red_task", type="text", nullable=true)
     * @Groups({"api_open_servers", "api_tournaments", "api_open_servers", "api_mission"})
     */
    private $redTask;
    /**
     * @var string
     *
     * @ORM\Column(name="blue_task", type="text", nullable=true)
     * @Groups({"api_open_servers", "api_tournaments", "api_open_servers", "api_mission"})
     */
    private $blueTask;
    /**
     * @var string
     *
     * @ORM\Column(name="neutral_task", type="text", nullable=true)
     * @Groups({"api_open_servers", "api_tournaments", "api_open_servers", "api_mission"})
     */
    private $neutralTask;
    /**
     * @var bool
     * @ORM\Column(name="is_event", type="boolean",nullable=true)
     * @Groups({"api_open_servers", "api_tournaments", "api_open_servers", "api_mission"})
     */
    private $isEvent;
    /**
     * @var Server
     * @ORM\ManyToOne(targetEntity="Server")
     * @ORM\JoinColumn(name="server_id", referencedColumnName="id", nullable=true, onDelete="CASCADE")
     */
    public $server;
    /**
     * @var Theatre
     * @ORM\ManyToOne(targetEntity="Theatre")
     * @ORM\JoinColumn(name="theatre_id", referencedColumnName="id", nullable=true)
     * @Groups({"api_open_servers", "api_mission"})
     */
    public $theatre;

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
     * Set name
     *
     * @param string $name
     *
     * @return Mission
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Mission
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set descriptionEn
     *
     * @param string $descriptionEn
     *
     * @return Mission
     */
    public function setDescriptionEn($descriptionEn)
    {
        $this->descriptionEn = $descriptionEn;

        return $this;
    }

    /**
     * Get descriptionEn
     *
     * @return string
     */
    public function getDescriptionEn()
    {
        return $this->descriptionEn;
    }

    /**
     * Set isEvent
     *
     * @param boolean $isEvent
     *
     * @return Mission
     */
    public function setIsEvent($isEvent)
    {
        $this->isEvent = $isEvent;

        return $this;
    }

    /**
     * Get isEvent
     *
     * @return boolean
     */
    public function getIsEvent()
    {
        return $this->isEvent;
    }

    /**
     * Set server
     *
     * @param Server|null $server
     *
     * @return Mission
     */
    public function setServer(Server $server = null)
    {
        $this->server = $server;

        return $this;
    }

    /**
     * Get server
     *
     * @return Server
     */
    public function getServer()
    {
        return $this->server;
    }

    public function __toString()
    {
        return $this->getName();
    }

    /**
     * @return string
     */
    public function getGeneralTask(): ?string
    {
        return $this->generalTask;
    }

    /**
     * @param string|null $generalTask
     */
    public function setGeneralTask(?string $generalTask): void
    {
        $this->generalTask = $generalTask;
    }

    /**
     * @return string
     */
    public function getRedTask(): ?string
    {
        return $this->redTask;
    }

    /**
     * @param string|null $redTask
     */
    public function setRedTask(?string $redTask): void
    {
        $this->redTask = $redTask;
    }

    /**
     * @return string
     */
    public function getBlueTask(): ?string
    {
        return $this->blueTask;
    }

    /**
     * @param string|null $blueTask
     */
    public function setBlueTask(?string $blueTask): void
    {
        $this->blueTask = $blueTask;
    }

    /**
     * @return string
     */
    public function getNeutralTask(): ?string
    {
        return $this->neutralTask;
    }

    /**
     * @param string|null $neutralTask
     */
    public function setNeutralTask(?string $neutralTask): void
    {
        $this->neutralTask = $neutralTask;
    }

    /**
     * @return ?Theatre
     */
    public function getTheatre(): ?Theatre
    {
        return $this->theatre;
    }

    /**
     * @param Theatre|null $theatre
     */
    public function setTheatre(?Theatre $theatre): void
    {
        $this->theatre = $theatre;
    }
}
