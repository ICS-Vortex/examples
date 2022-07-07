<?php

namespace App\Message;

use App\Entity\Pilot;
use App\Entity\RegistrationTicket;

class ConfirmRegistration
{
    private RegistrationTicket $ticket;
    private Pilot $pilot;
    private string $html;

    public function __construct(Pilot $pilot, RegistrationTicket $ticket, string $html)
    {
        $this->ticket = $ticket;
        $this->pilot = $pilot;
        $this->html = $html;
    }

    /**
     * @return RegistrationTicket
     */
    public function getTicket(): RegistrationTicket
    {
        return $this->ticket;
    }

    /**
     * @param RegistrationTicket $ticket
     */
    public function setTicket(RegistrationTicket $ticket): void
    {
        $this->ticket = $ticket;
    }

    /**
     * @return Pilot
     */
    public function getPilot(): Pilot
    {
        return $this->pilot;
    }

    /**
     * @param Pilot $pilot
     */
    public function setPilot(Pilot $pilot): void
    {
        $this->pilot = $pilot;
    }

    /**
     * @return string
     */
    public function getHtml(): string
    {
        return $this->html;
    }

    /**
     * @param string $html
     */
    public function setHtml(string $html): void
    {
        $this->html = $html;
    }
}