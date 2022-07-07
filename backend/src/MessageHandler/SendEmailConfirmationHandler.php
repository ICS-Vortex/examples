<?php

namespace App\MessageHandler;

use App\Constant\Parameter;
use App\Message\ConfirmRegistration;
use Swift_Mailer;

class SendEmailConfirmationHandler
{
    private Swift_Mailer $mailer;

    /**
     * SendEmailConfirmationHandler constructor.
     * @param Swift_Mailer $mailer
     */
    public function __construct(Swift_Mailer $mailer)
    {

        $this->mailer = $mailer;
    }

    public function __invoke(ConfirmRegistration $message)
    {
        $message = (new \Swift_Message(Parameter::EMAIL_PREFIX . 'Confirm your registration'))
            ->setFrom('no-reply@vfpteam.com')
            ->setTo($message->getTicket()->getEmail())
            ->setBody($message->getHtml());

        $this->mailer->send($message);
    }
}