<?php

namespace App\Service;

use App\Constant\Parameter;
use App\Entity\Instance;
use App\Entity\Server;
use App\Entity\SystemLog;
use App\Helper\Helper;
use App\Repository\LogRepository;
use Doctrine\ORM\EntityManagerInterface;
use Monolog\Logger;
use Symfony\Component\HttpFoundation\RequestStack;

class ApiAccessService
{
    /** @var RequestStack $requestStack */
    protected RequestStack $requestStack;

    /** @var EntityManagerInterface $em */
    protected EntityManagerInterface $em;

    public function __construct(RequestStack $requestStack, EntityManagerInterface $em)
    {
        $this->requestStack = $requestStack;
        $this->em = $em;
    }

    public function isSerialNumberValid(): bool
    {
        $request = $this->requestStack->getCurrentRequest();
        if ($request === null) {
            return false;
        }

        $serial = $request->headers->get(Parameter::DCS_SERIAL_HEADER, null);
        if ($serial === null) {
            return false;
        }

        /** @var Instance $instance */
        $instance = $this->em->getRepository(Instance::class)->findOneBy([
            'serialNumber' => $serial,
        ]);

        if (empty($instance)) {
            return false;
        }

        if (!$instance->isEnabled()) {
            return false;
        }

        return true;
    }

    public function isServerIdentifierValid(): bool
    {
        $request = $this->requestStack->getCurrentRequest();
        if ($request === null) {
            $this->em->getRepository(SystemLog::class)->log('Empty current request in isServerIdentifierValid()', Logger::EMERGENCY);
            return false;
        }
        if (empty($request->headers->get('X-DCS-SERVER', null))) {
            $this->em->getRepository(SystemLog::class)->log('Missing X-DCS-SERVER header in isServerIdentifierValid()', Logger::EMERGENCY);
            return false;
        }
        if (!Helper::isValidData($request->getContent())) {
            $this->em->getRepository(SystemLog::class)->log('Invalid ServerID ( base64 content ) received in isServerIdentifierValid()', Logger::EMERGENCY);
        }
        $data = Helper::jsonToArray($request->getContent());

        $identifier = !empty($data['server']) ? base64_decode($data['server']) : null;
        if (is_null($identifier)) {
            $this->em->getRepository(SystemLog::class)->log('Empty server ID arrived in isServerIdentifierValid() from base64 REQUEST', Logger::EMERGENCY);
            return false;
        }
        /** @var Server $server */
        $server = $this->em->getRepository(Server::class)->findOneBy([
            'identifier' => $identifier,
        ]);
        if (empty($server)) {
            $this->em->getRepository(SystemLog::class)->log(sprintf('Server ID %s not found in isServerIdentifierValid()', $data['server']), Logger::EMERGENCY);
            return false;
        }

        if (!$server->getActive()) {
            $this->em->getRepository(SystemLog::class)->log('Server is not active', Logger::NOTICE);
            return false;
        }

        return true;
    }
}
