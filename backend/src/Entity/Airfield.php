<?php

namespace App\Entity;

use App\Includes\Timestamp;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Airfield
 *
 * @ORM\Table(name="airfields")
 * @ORM\Entity(repositoryClass="App\Repository\AirfieldRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Airfield
{
    use Timestamp;
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups({"api_online", "api_sorties"})
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     * @Groups({"api_online", "api_sorties"})
     */
    private $title;
    /**
     * @var string
     *
     * @ORM\Column(name="tcn", type="string", length=8, nullable=true)
     */
    private $tcn;
    /**
     * @var string
     *
     * @ORM\Column(name="ils", type="string", length=16, nullable=true)
     */
    private $ils;
    /**
     * @var string
     *
     * @ORM\Column(name="latitude", type="string", length=32, nullable=true)
     */
    private $latitude;
    /**
     * @var string
     *
     * @ORM\Column(name="longitude", type="string", length=32, nullable=true)
     */
    private $longitude;
    /**
     * @var string
     *
     * @ORM\Column(name="elevation_feet", type="string", length=10, nullable=true)
     */
    private $elevationFeet;
    /**
     * @var string
     *
     * @ORM\Column(name="elevation_meters", type="string", length=10, nullable=true)
     */
    private $elevationMeters;
    /**
     * @var string
     *
     * @ORM\Column(name="atc", type="string", length=64, nullable=true)
     */
    private $atc;
    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;
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
     * Set title
     *
     * @param string $title
     *
     * @return Airfield
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getTcn()
    {
        return $this->tcn;
    }

    /**
     * @param string $tcn
     */
    public function setTcn($tcn)
    {
        $this->tcn = $tcn;
    }

    /**
     * @return string
     */
    public function getIls()
    {
        return $this->ils;
    }

    /**
     * @param string $ils
     */
    public function setIls($ils)
    {
        $this->ils = $ils;
    }

    /**
     * @return string
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * @param string $latitude
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
    }

    /**
     * @return string
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * @param string $longitude
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;
    }

    /**
     * @return string
     */
    public function getElevationFeet()
    {
        return $this->elevationFeet;
    }

    /**
     * @param string $elevationFeet
     */
    public function setElevationFeet($elevationFeet)
    {
        $this->elevationFeet = $elevationFeet;
    }

    /**
     * @return string
     */
    public function getElevationMeters()
    {
        return $this->elevationMeters;
    }

    /**
     * @param string $elevationMeters
     */
    public function setElevationMeters($elevationMeters)
    {
        $this->elevationMeters = $elevationMeters;
    }

    /**
     * @return string
     */
    public function getAtc()
    {
        return $this->atc;
    }

    /**
     * @param string $atc
     */
    public function setAtc($atc)
    {
        $this->atc = $atc;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }
}
