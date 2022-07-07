<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SenderConfigRepository")
 */
class SenderConfig
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $active;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Server", inversedBy="senderConfigs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $server;

    /**
     * @ORM\Column(type="boolean", options={"default":0})
     */
    private $greenieBoard = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

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

    public function getGreenieBoard(): ?bool
    {
        return $this->greenieBoard;
    }

    public function setGreenieBoard(bool $greenieBoard): self
    {
        $this->greenieBoard = $greenieBoard;

        return $this;
    }
}
