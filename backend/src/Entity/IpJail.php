<?php

namespace App\Entity;

use App\Includes\Timestamp;
use App\Repository\IpJailRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=IpJailRepository::class)
 * @ORM\Table(name="ips_jail")
 * @ORM\HasLifecycleCallbacks
 */
class IpJail
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
    private $ip;

    /**
     * @ORM\Column(type="datetime")
     */
    private $until;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIp(): ?string
    {
        return $this->ip;
    }

    public function setIp(string $ip): self
    {
        $this->ip = $ip;

        return $this;
    }

    public function getUntil(): ?\DateTimeInterface
    {
        return $this->until;
    }

    public function setUntil(\DateTimeInterface $until): self
    {
        $this->until = $until;

        return $this;
    }
}
