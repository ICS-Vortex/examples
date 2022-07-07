<?php

namespace App\Entity;

use App\Includes\Timestamp;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * MobileDevice
 *
 * @ORM\Table(name="mobile_device")
 * @ORM\Entity(repositoryClass="App\Repository\MobileDeviceRepository")
 * @ORM\HasLifecycleCallbacks()
 *
 */
class MobileDevice
{
    use Timestamp;

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
     * @ORM\Column(name="title", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $androidDeviceName = 'Android';
    /**
     * @var string
     *
     * @ORM\Column(name="android_identifier", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $androidIdentifier;
    /**
     * @var string
     *
     * @ORM\Column(name="token", type="string", length=255)
     */
    private $token;

    /**
     * @var bool
     *
     * @ORM\Column(name="notice_device", type="boolean")
     *
     */
    private $notificate = true;
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="last_time", type="datetime")
     */
    private $lastTime;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get token
     *
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set token
     *
     * @param string $token
     *
     * @return MobileDevice
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * @return bool
     */
    public function getNotificate()
    {
        return $this->notificate;
    }

    /**
     * @param bool $notificate
     */
    public function setNotificate($notificate)
    {
        $this->notificate = $notificate;
    }

    /**
     * @return \DateTime
     */
    public function getLastTime()
    {
        return $this->lastTime;
    }

    /**
     * @param \DateTime $lastTime
     */
    public function setLastTime($lastTime)
    {
        $this->lastTime = $lastTime;
    }

    /**
     * @ORM\PrePersist
     */
    public function setLastTimeValue()
    {
        $this->lastTime = new \DateTime();
    }

    /**
     * @return string
     */
    public function getAndroidIdentifier()
    {
        return $this->androidIdentifier;
    }

    /**
     * @param string $androidIdentifier
     */
    public function setAndroidIdentifier($androidIdentifier)
    {
        $this->androidIdentifier = $androidIdentifier;
    }

    /**
     * @return string
     */
    public function getAndroidDeviceName()
    {
        return $this->androidDeviceName;
    }

    /**
     * @param string androidDeviceName
     */
    public function setAndroidDeviceName(string $androidDeviceName)
    {
        $this->androidDeviceName = $androidDeviceName;
    }


}

