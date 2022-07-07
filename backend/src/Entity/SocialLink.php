<?php

namespace App\Entity;

use App\Repository\SocialLinkRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * SocialLink
 *
 * @ORM\Table(name="social_links")
 * @ORM\Entity(repositoryClass="App\Repository\SocialLinkRepository")
 */
class SocialLink
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
     * @var string
     *
     * @ORM\Column(name="icon", type="string")
     */
    private $icon = SocialLinkRepository::GOOGLE;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=255)
     */
    private $url;

    /**
     * @var string
     *
     * @ORM\Column(name="new_window", type="boolean")
     */
    private $newWindow = true;

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
     * Set icon
     *
     * @param string $icon
     * @return SocialLink
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * Get icon
     *
     * @return string
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * Set url
     *
     * @param string $url
     * @return SocialLink
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return string
     */
    public function getNewWindow()
    {
        return $this->newWindow;
    }

    /**
     * @param string $newWindow
     */
    public function setNewWindow($newWindow)
    {
        $this->newWindow = $newWindow;
    }

}
