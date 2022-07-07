<?php


namespace App\Service;


use App\Entity\ChatMessage;
use App\Entity\Server;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

class ChatService
{
    /** @var EntityManager $em */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function getLastMessages(Server $server, $count = 10)
    {
        return $this->em->getRepository(ChatMessage::class)->findBy(['server' => $server], ['id' => 'DESC'], $count);
    }
}