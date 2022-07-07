<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="elos", indexes={
 *     @ORM\Index(name="pilots_elo_ranking_idx", columns={"side", "type", "tour_id", "server_id"})
 * })
 * @ORM\Entity(repositoryClass="App\Repository\EloRepository")
 */
class Elo
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private $type;
    /**
     * @ORM\ManyToOne(targetEntity="Pilot", inversedBy="elos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $pilot;
    /**
     * @ORM\ManyToOne(targetEntity="Tour")
     * @ORM\JoinColumn(nullable=true)
     */
    private $tour;

    /**
     * @var Server
     * @ORM\ManyToOne(targetEntity="Server")
     * @ORM\JoinColumn(nullable=false)
     */
    private $server;
    /**
     * @var Plane
     * @ORM\ManyToOne(targetEntity="App\Entity\Plane")
     * @ORM\JoinColumn(nullable=true)
     */
    private $plane;
    /**
     * @var string
     *
     * @ORM\Column(name="side", type="string", nullable=true)
     */
    private $side;
    /**
     * @ORM\Column(type="float", options={"default":1000})
     */
    private $rating = 1000;

    /**
     * @ORM\Column(type="float", options={"default":40})
     */
    private $coefficient = 40;

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

    public function getTour(): ?Tour
    {
        return $this->tour;
    }

    public function setTour(?Tour $tour): self
    {
        $this->tour = $tour;

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

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * @return Plane
     */
    public function getPlane(): Plane
    {
        return $this->plane;
    }

    /**
     * @param Plane $plane
     */
    public function setPlane(Plane $plane): void
    {
        $this->plane = $plane;
    }

    /**
     * @return string
     */
    public function getSide(): string
    {
        return $this->side;
    }

    /**
     * @param string $side
     */
    public function setSide(string $side): void
    {
        $this->side = $side;
    }

    /**
     * @return int
     */
    public function getRating(): int
    {
        return $this->rating;
    }

    /**
     * @param int $rating
     */
    public function setRating(int $rating): void
    {
        $this->rating = $rating;
    }

    /**
     * @return int
     */
    public function getCoefficient(): int
    {
        return $this->coefficient;
    }

    /**
     * @param int $coefficient
     */
    public function setCoefficient(int $coefficient): void
    {
        $this->coefficient = $coefficient;
    }
}
