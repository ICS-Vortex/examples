<?php

namespace App\Service;

use App\Entity\Log;
use App\Entity\MobileDevice;
use App\Entity\Setting;
use App\Repository\LogRepository;
use App\Repository\SettingRepository;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

class NotificationService
{
    /** @var EntityManager */
    private $em;

    private $response;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    public function notifyMobileClients($event, $message)
    {
        $em = $this->getManager();
        $devicesSearch = $em->getRepository(MobileDevice::class)->findBy(array(
            'notificate' => true,
        ));
        $batchSize = 1000;
        $counter = 1;
        $devices = [];
        $updatePhones = [];
        /** @var $device MobileDevice */
        foreach ($devicesSearch as $device) {
            if (bcmod(strval($counter), strval($batchSize)) == 0) {
                $this->sendNotice($event, $message, $devices);
                $devices = [];
            }
            $devices[] = $device->getToken();
            $counter++;
            $updatePhones[] = $device->getAndroidIdentifier();
        }

        $this->sendNotice($event, $message, $devices);
        $now = date('Y-m-d H:i:s');
        $table = $this->getManager()->getClassMetadata(MobileDevice::class)->getTableName();
        try {
            $query = "
                UPDATE `{$table}` SET `last_time` = '{$now}'
                WHERE `android_identifier` IN ('" . implode('\',\'', $updatePhones) . "')
            ";
            $this->getManager()->getConnection()->query($query)->execute();
        } catch (DBALException $e) {
            $this->log($e->getMessage(), LogRepository::TYPE_ERROR);
        }
    }

    /**
     * @return EntityManager
     */
    public function getManager(): EntityManager
    {
        return $this->em;
    }

    private function sendNotice($event, $message, array $devices)
    {
        /** @var Setting $apiKeySetting */
        $apiKeySetting = $this->getManager()->getRepository(Setting::class)->findOneBy([
            'keyword' => SettingRepository::SETTING_FCM_API_KEY,
        ]);
        if (empty($devices) || empty($apiKeySetting)) {
            return;
        }
        $msg = array
        (
            'body' => $message,
            'title' => 'Burning Skies',
            'subtitle' => $message,
            'tickerText' => $message,
            'vibrate' => true,
        );
        $fields = array
        (
            'registration_ids' => $devices,
            'data' => $msg
        );

        $headers = array
        (
            'Authorization:key=' . $apiKeySetting->getValue(),
            'Content-Type:application/json'
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $this->response = json_decode(curl_exec($ch), true);
        curl_close($ch);
        $this->handleResponse();
    }

    private function handleResponse()
    {
    }

    private function log($message, $type = LogRepository::TYPE_OK)
    {
        $log = new Log();
        $log->setMessage($message);
        $log->setType($type);
        try {
            $this->em->persist($log);
            $this->em->flush();
            return true;
        } catch (\Exception $exception) {
            return false;
        }
    }
}