<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Swift_Message;

class RegisterMailService
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    public function __construct(EntityManagerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * @param $email
     * @param $hash
     * @return string
     * @internal param bool $withoutBundle
     * @internal param bool $lowercase
     */
    public function sendRegistrationMail($email,$hash){
        $message = Swift_Message::newInstance()
            ->setSubject('AFS - AggressorsFlight School')
            ->setFrom('send@example.com')
            ->setTo($email)
            ->setBody(
                $this->renderView(
                    'MainCoreBundle:Register:mail.html.twig',
                    array('hash' => $hash)
                ),
                'text/html'
            )
        ;
        $this->mailer->send($message);
    }
}