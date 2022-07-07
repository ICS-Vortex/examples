<?php

namespace App\Entity;

use App\Includes\Timestamp;
use App\Repository\SystemLogRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="system_logs")
 * @ORM\Entity(repositoryClass=SystemLogRepository::class)
 * @ORM\HasLifecycleCallbacks
 */
class SystemLog
{
    use Timestamp;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $initiator;
    /**
     * @ORM\Column(type="integer")
     */
    private $type;
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $level;
    /**
     * @ORM\Column(type="text")
     */
    private $message;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getInitiator(): ?string
    {
        return $this->initiator;
    }

    public function setInitiator(string $initiator): self
    {
        $this->initiator = $initiator;

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
     * @return int
     */
    public function getType() :int
    {
        return $this->type;
    }

    /**
     * @param int $type
     */
    public function setType(int $type): void
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * @param mixed $level
     */
    public function setLevel($level): void
    {
        $this->level = $level;
    }
}
