<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MapUnitRepository")
 * @ORM\Table(name="map_units",
 *  uniqueConstraints={
 *        @ORM\UniqueConstraint(name="unique_identifier_server_id", columns={"identifier", "server_id"})
 *    }
 * )
 */
class MapUnit
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $identifier;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @var boolean
     * @ORM\Column(type="boolean")
     */
    private $isHuman;
    /**
     * @var boolean
     * @ORM\Column(type="boolean")
     */
    private $isStatic;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $country;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $side;

    /**
     * @ORM\Column(type="float")
     */
    private $latitude;

    /**
     * @ORM\Column(type="float")
     */
    private $longitude;

    /**
     * @ORM\Column(type="integer")
     */
    private $altitude;

    /**
     * @ORM\Column(type="integer")
     */
    private $heading;
    /**
     * @var Server
     * @ORM\ManyToOne(targetEntity="Server", inversedBy="mapUnits")
     * @ORM\JoinColumn(name="server_id", referencedColumnName="id")
     */
    public $server;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdentifier(): ?int
    {
        return $this->identifier;
    }

    public function setIdentifier($identifier): self
    {
        $this->identifier = (int) $identifier;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle($title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry($country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getSide(): ?string
    {
        return $this->side;
    }

    public function setSide($side): self
    {
        $this->side = $side;

        return $this;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude($latitude): self
    {
        $this->latitude = (float) $latitude;

        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude($longitude): self
    {
        $this->longitude = (float) $longitude;

        return $this;
    }

    public function getAltitude(): ?int
    {
        return $this->altitude;
    }

    public function setAltitude($altitude): self
    {
        $this->altitude = (int) $altitude;

        return $this;
    }

    public function getHeading(): ?int
    {
        return $this->heading;
    }

    public function setHeading($heading): self
    {
        $this->heading = (int) $heading;

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
    public function getType() : string
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type): void
    {
        $this->type = $type;
    }

    /**
     * @return bool
     */
    public function isHuman(): bool
    {
        return $this->isHuman;
    }

    /**
     * @param bool $isHuman
     */
    public function setIsHuman(bool $isHuman): void
    {
        $this->isHuman = $isHuman;
    }

    /**
     * @return bool
     */
    public function isStatic(): bool
    {
        return $this->isStatic;
    }

    /**
     * @param bool $isStatic
     */
    public function setIsStatic(bool $isStatic): void
    {
        $this->isStatic = $isStatic;
    }
}
