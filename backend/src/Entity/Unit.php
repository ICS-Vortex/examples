<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * DcsUnits
 *
 * @ORM\Table(name="units", options={"collate"="utf8_general_ci", "charset"="utf8"})
 * @ORM\Entity(repositoryClass="App\Repository\UnitRepository")
 */
class Unit
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups({"api_dogfights"})
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     * @Groups({"api_dogfights"})
     */
    private $name;
    /**
     * @ORM\ManyToOne(targetEntity="UnitType")
     * @ORM\JoinColumn(name="unit_type_id", referencedColumnName="id")
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;
    /**
     * @ORM\Column(name="air_unit", type="boolean", nullable=true, options={"default" = 0})
     * @Groups({"api_mission", "api_dogfights"})
     */
    private $airUnit = false;
    /**
     * @ORM\Column(name="ground_unit", type="boolean", nullable=true, options={"default" = 0})
     * @Groups({"api_mission", "api_dogfights"})
     */
    private $groundUnit = false;
    /**
     * @ORM\Column(name="sea_unit", type="boolean", nullable=true, options={"default" = 0})
     * @Groups({"api_mission", "api_dogfights"})
     */
    private $seaUnit = false;

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
     * @return Unit
     */
    public function setName(string $name)
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
     * @return Unit
     */
    public function setDescription(string $description)
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
     * Set type
     *
     * @param UnitType $type
     *
     * @return Unit
     */
    public function setType(UnitType $type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return UnitType
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return bool
     */
    public function isAirUnit(): bool
    {
        return $this->airUnit;
    }

    /**
     * @param bool $airUnit
     */
    public function setAirUnit(bool $airUnit): void
    {
        $this->airUnit = $airUnit;
    }

    /**
     * @return bool
     */
    public function isGroundUnit(): bool
    {
        return $this->groundUnit;
    }

    /**
     * @param bool $groundUnit
     */
    public function setGroundUnit(bool $groundUnit): void
    {
        $this->groundUnit = $groundUnit;
    }

    /**
     * @return bool
     */
    public function isSeaUnit(): bool
    {
        return $this->seaUnit;
    }

    /**
     * @param bool $seaUnit
     */
    public function setSeaUnit(bool $seaUnit): void
    {
        $this->seaUnit = $seaUnit;
    }
}
