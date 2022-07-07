<?php

namespace App\Entity\Model;

class RegistrationModel
{
    /** @var string $callsign */
    private $callsign;

    /** @var string $email */
    private $email;

    /**
     * @return string
     */
    public function getCallsign()
    {
        return $this->callsign;
    }

    /**
     * @param string $callsign
     */
    public function setCallsign($callsign)
    {
        $this->callsign = $callsign;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }
}