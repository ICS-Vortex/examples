<?php

namespace App\MessageHandler;

use App\Entity\Log;
use App\Entity\SystemLog;
use App\Message\EmailMessage;
use Doctrine\ORM\EntityManagerInterface;
use Monolog\Logger;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Serializer\SerializerInterface;

class EmailsHandler implements MessageHandlerInterface
{
    /** @var SerializerInterface */
    private SerializerInterface $serializer;

    /** @var MailerInterface */
    private MailerInterface $mailer;
    /**
     * @var ParameterBagInterface
     */
    private ParameterBagInterface $bag;
    private EntityManagerInterface $manager;

    /**
     * @param MailerInterface $mailer
     * @param ParameterBagInterface $bag
     * @param EntityManagerInterface $manager
     */
    public function __construct(MailerInterface $mailer, ParameterBagInterface $bag, EntityManagerInterface $manager)
    {
        $this->mailer = $mailer;
        $this->bag = $bag;
        $this->manager = $manager;
    }

    public function __invoke(EmailMessage $emailMessage)
    {
        try {
            $email = new Email();
            $email->from($this->bag->get('mailer.address'));
            foreach ($emailMessage->getRecipients() as $address) {
                $email->addTo($address);
            }

            $email->subject($emailMessage->getSubject());
            if ($emailMessage->getIsHtml()) {
                $email->html($emailMessage->getBody());
                $email->getHeaders()->addHeader('Content-Type', 'text/html');
            } else {
                $email->text($emailMessage->getBody());
            }

            foreach ($emailMessage->getAttachments() as $attachment) {
                $email->attachFromPath($attachment['path'], $attachment['name'], $attachment['mime']);
            }

            $this->mailer->send($email);
            $message = 'Email was sent to ' . implode(',', $emailMessage->getRecipients()) .'. Subject: ' . $emailMessage->getSubject();
            $this->manager->getRepository(SystemLog::class)->log($message, Logger::INFO, 'amqp');
            echo 'Email sent ' . PHP_EOL;
            sleep(5);
        } catch (TransportExceptionInterface $e) {
            $this->manager->getRepository(SystemLog::class)->log($e->getTraceAsString(), Logger::EMERGENCY, 'amqp');
            echo 'Trace: ' . $e->getTraceAsString() . PHP_EOL;
        }
    }

    public function sendMessage(EmailMessage $emailMessage)
    {
        $this->__invoke($emailMessage);
    }
}
