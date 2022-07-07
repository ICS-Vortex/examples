<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as PHPMailerException;

class MailingService
{
    /** @var ParameterBagInterface */
    private ParameterBagInterface $bag;

    /** @var EntityManagerInterface */
    private EntityManagerInterface $manager;

    private PHPMailer $mail;

    public function __construct(EntityManagerInterface $manager, ParameterBagInterface $bag)
    {
        $this->bag = $bag;
        $this->manager = $manager;
    }

    public function createEmail()
    {
        $this->mail = new PHPMailer;
        $this->mail->From = "no-reply@{$this->bag->get('frontendHost')}";
        $this->mail->FromName = $this->bag->get('title');
        $this->mail->isHTML(true);
        $this->mail->isSendmail();
    }

    public function addAttachment($filepath, $filename)
    {
        $this->mail->addAttachment($filepath, $filename);
    }

    /**
     * @param string $email
     * @param string|null $name
     * @return bool
     */
    public function addAddress(string $email, ?string $name = null)
    {
        try {
            $this->mail->addAddress($email, $name);
            return true;
        } catch (PHPMailerException $e) {
            return false;
        }
    }

    /**
     * @param $subject string email subject
     * @param $body string email text
     */
    public function addMessage(string $subject, string $body)
    {
        $this->mail->Subject = $subject;
        $this->mail->Body = $body;
    }

    /**
     * @return bool|array
     */
    public function send()
    {
        try {
            return $this->mail->send() === true ? true : $this->mail->ErrorInfo;
        } catch (Exception $e) {
            return $this->mail->ErrorInfo;
        }
    }
}