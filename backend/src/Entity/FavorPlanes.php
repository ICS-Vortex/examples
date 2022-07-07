<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FavorPlanes
 *
 * @ORM\Table(name="favor_planes")
 * @ORM\Entity(repositoryClass="App\Repository\FavorPlaneRepository")
 */
class FavorPlanes
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @ORM\ManyToOne(targetEntity="Pilot")
     * @ORM\JoinColumn(name="pilot_id", referencedColumnName="id")
     */
    public $pilot;
    /**
     * @ORM\ManyToOne(targetEntity="Server", inversedBy="favorPlanes")
     * @ORM\JoinColumn(name="server_id", referencedColumnName="id")
     */
    public $server;
    /**
     * @ORM\ManyToOne(targetEntity="Plane")
     * @ORM\JoinColumn(name="plane_id", referencedColumnName="id")
     */
    public $plane;

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
     * Set pilot
     *
     * @param \App\Entity\Pilot $pilot
     *
     * @return FavorPlanes
     */
    public function setPilot(\App\Entity\Pilot $pilot = null)
    {
        $this->pilot = $pilot;

        return $this;
    }

    /**
     * Get pilot
     *
     * @return \App\Entity\Pilot
     */
    public function getPilot()
    {
        return $this->pilot;
    }

    /**
     * Set server
     *
     * @param \App\Entity\Server $server
     *
     * @return FavorPlanes
     */
    public function setServer(\App\Entity\Server $server = null)
    {
        $this->server = $server;

        return $this;
    }

    /**
     * Get server
     *
     * @return \App\Entity\Server
     */
    public function getServer()
    {
        return $this->server;
    }

    /**
     * Set plane
     *
     * @param \App\Entity\Plane $plane
     *
     * @return FavorPlanes
     */
    public function setPlane(\App\Entity\Plane $plane = null)
    {
        $this->plane = $plane;

        return $this;
    }

    /**
     * Get plane
     *
     * @return \App\Entity\Plane
     */
    public function getPlane()
    {
        return $this->plane;
    }
}
