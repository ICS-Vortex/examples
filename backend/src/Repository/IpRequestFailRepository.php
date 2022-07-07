<?php

namespace App\Repository;

use App\Entity\IpJail;
use App\Entity\IpRequestFail;
use DateInterval;
use DateTime;
use Symfony\Component\HttpFoundation\Request;

class IpRequestFailRepository extends BaseRepository
{
    public function clearFails($ip): bool
    {
        $this->_em->createQuery('DELETE FROM App\Entity\IpRequestFail irf WHERE irf.ip = :clientIp')->setParameter('clientIp', $ip)->execute();
        return true;
    }

    public function addFails(Request $request)
    {
        $em = $this->getEntityManager();
        $attempts = $em->getRepository(IpRequestFail::class)->findBy([
            'ip' => $request->getClientIp(),
        ]);
        if (count($attempts) < 3) {
            $fail = new IpRequestFail();
            $fail->setIp($request->getClientIp());
            $em->persist($fail);
        } else {
            // Clear all fails
            $this->clearFails($request->getClientIp());
            //Add to jail
            $jail = new IpJail();
            $jail->setIp($request->getClientIp());
            $until = new DateTime();
            $until->add(new DateInterval('PT3600S'));
            $jail->setUntil($until);
            $em->persist($jail);
        }
        $em->flush();
    }
}
