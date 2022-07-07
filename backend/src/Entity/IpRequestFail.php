<?php

namespace App\Entity;

use App\Includes\Timestamp;
use App\Repository\IpRequestFailRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=IpRequestFailRepository::class)
 * @ORM\Table(name="ips_request_fails")
 * @ORM\HasLifecycleCallbacks
 */
class IpRequestFail
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
}
