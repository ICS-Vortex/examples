<?php

namespace App\Repository;

use App\Entity\Setting;
use App\Entity\SystemLog;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping;
use Doctrine\ORM\ORMException;
use Exception;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Symfony\Component\Filesystem\Filesystem;

class BaseRepository extends EntityRepository
{
    /**
     * @param string $message
     * @param int $type
     * @param string $initiator
     * @return bool
     */
    public function log(string $message, int $type = Logger::INFO, string $initiator = 'repository') : bool
    {
        $em = $this->getEntityManager();
        $log = new SystemLog();
        $log->setInitiator($initiator);
        $log->setType($type);
        $log->setLevel($this->getLogLevelLabel($type));
        $log->setMessage($message);
        try {
            $em->persist($log);
            $em->flush();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function getLogLevelLabel(int $level) : string
    {
        return match ($level) {
            Logger::INFO => 'Info',
            Logger::NOTICE => 'Notice',
            Logger::WARNING => 'Warning',
            Logger::ERROR => 'Error',
            Logger::CRITICAL => 'Critical',
            Logger::ALERT => 'Alert',
            Logger::EMERGENCY => 'Emergency',
            default => 'Debug',
        };
    }

    /**
     * @param integer $time
     * @return string
     */
    public function formatTime(int $time): string
    {
        if ($time < 0) {
            return '00:00:00';
        }
        $sec = $time % 60;
        $time = floor($time / 60);
        $min = $time % 60;
        $time = floor($time / 60);
        if ($sec < 10) {
            $sec = "0" . $sec;
        }
        if ($min < 10) {
            $min = "0" . $min;
        }
        if ($time < 10) {
            $time = "0" . $time;
        }
        return $time . ":" . $min . ":" . $sec;
    }
}
