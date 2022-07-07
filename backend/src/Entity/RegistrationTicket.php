<?php

namespace App\Entity;

use App\Helper\Helper;
use App\Includes\Timestamp;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * RegistrationTicket
 *
 * @ORM\Table(name="registration_tickets")
 * @ORM\Entity(repositoryClass="App\Repository\RegistrationTicketRepository")
 * @ORM\HasLifecycleCallbacks
 */
class RegistrationTicket
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
     * @ORM\Column(name="email", type="string")
     */
    private $email;
    /**
     * @var string
     *
     * @ORM\Column(name="token", type="string", length=64)
     */
    private $token;
    /**
     * @ORM\ManyToOne(targetEntity="Pilot", inversedBy="registrationTickets")
     * @ORM\JoinColumn(name="pilot_id", referencedColumnName="id",unique=true)
     */
    private $pilot;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="deadline", type="datetime")
     */
    private $deadline;
    /**
     * @var boolean
     *
     * @ORM\Column(name="accepted", type="boolean", options={"default":0})
     */
    private $accepted = false;
    /**
     * @var boolean
     *
     * @ORM\Column(name="issued", type="boolean", options={"default":0})
     */
    private $issued = false;

    public function __construct()
    {
        $this->token = Helper::generateRandomString(64);
    }


    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Pilot
     */
    public function getPilot()
    {
        return $this->pilot;
    }

    /**
     * @param mixed $pilot
     */
    public function setPilot($pilot)
    {
        $this->pilot = $pilot;
    }

    /**
     * @return DateTime
     */
    public function getDeadline()
    {
        return $this->deadline;
    }

    /**
     * @param DateTime $deadline
     */
    public function setDeadline($deadline)
    {
        $this->deadline = $deadline;
    }

    /**
     * @return bool
     */
    public function isAccepted()
    {
        return $this->accepted;
    }

    /**
     * @param bool $accepted
     */
    public function setAccepted($accepted = true)
    {
        $this->accepted = $accepted;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @param string $token
     */
    public function setToken(string $token): void
    {
        $this->token = $token;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email)
    {
        $this->email = $email;
    }

    public function isOutdated()
    {
        $deadline = strtotime($this->deadline->format('Y-m-d H:i:s'));
        if ($deadline > time()) {
            return false;
        }

        return true;
    }

    /**
     * @return bool
     */
    public function isIssued(): bool
    {
        return $this->issued;
    }

    /**
     * @param bool $issued
     */
    public function setIssued(bool $issued): void
    {
        $this->issued = $issued;
    }
}
