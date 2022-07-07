<?php

namespace App\Entity;

use App\Includes\Timestamp;
use App\Repository\UcidTokenRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UcidTokenRepository::class)
 * @ORM\Table(name="ucid_tokens")
 * @ORM\HasLifecycleCallbacks
 */
class UcidToken
{
    use Timestamp;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Pilot::class, inversedBy="ucidTokens")
     * @ORM\JoinColumn(nullable=false)
     */
    private $pilot;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $token;

    /**
     * @ORM\Column(type="bigint")
     */
    private $expires;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPilot(): ?Pilot
    {
        return $this->pilot;
    }

    public function setPilot(?Pilot $pilot): self
    {
        $this->pilot = $pilot;

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(string $token): self
    {
        $this->token = $token;

        return $this;
    }

    public function getExpires(): ?string
    {
        return $this->expires;
    }

    public function setExpires(string $expires): self
    {
        $this->expires = $expires;

        return $this;
    }
}
