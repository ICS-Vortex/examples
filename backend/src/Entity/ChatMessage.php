<?php

namespace App\Entity;

use App\Includes\Timestamp;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="chat_messages")
 * @ORM\Entity(repositoryClass="App\Repository\ChatMessageRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class ChatMessage
{
    use Timestamp;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Pilot")
     * @ORM\JoinColumn(nullable=false)
     */
    private $sender;
    /**
     * @var Server
     * @ORM\ManyToOne(targetEntity="Server", inversedBy="chatMessages")
     * @ORM\JoinColumn(name="server_id", referencedColumnName="id", onDelete="CASCADE")
     */
    public $server;

    /**
     * @ORM\Column(type="text")
     */
    private $message;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSender(): ?Pilot
    {
        return $this->sender;
    }

    public function setSender(?Pilot $sender): self
    {
        $this->sender = $sender;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    /**
     * @return Server
     */
    public function getServer(): Server
    {
        return $this->server;
    }

    /**
     * @param Server $server
     */
    public function setServer(Server $server): void
    {
        $this->server = $server;
    }
}
