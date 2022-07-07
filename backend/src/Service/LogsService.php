<?php

namespace App\Service;

use App\Entity\Log;
use Doctrine\ORM\EntityManager;
use Psr\Log\LoggerInterface;

class LogsService
{
    /** @var EntityManager $entityManager */
    private $entityManager;

    private $logger;

    public function __construct(EntityManager $entityManager, LoggerInterface $logger) {
        $this->entityManager = $entityManager;
        $this->logger = $logger;
    }

    /**
     * @param $type
     * @param $message
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function log($message)
    {
        $this->logger->debug($message, ['dcs_api']);
//        $log = new Log();
//        $log->setType($type);
//        $log->setMessage($message);
//        $log->setCreateTime(new \DateTime(date('Y-m-d H:i:s')));
//        $this->entityManager->persist($log);
//        $this->entityManager->flush();

    }
}