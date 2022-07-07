<?php

namespace App\Repository;

use App\Entity\IpJail;
use Symfony\Component\HttpFoundation\Request;

class IpJailRepository extends BaseRepository
{
    public function clientIsInJail(Request $request): bool
    {
        $em = $this->getEntityManager();
        $jail = $em->getRepository(IpJail::class)->findOneBy([
            'ip' => $request->getClientIp(),
        ]);
        if (!empty($jail)) {
            $now = time();
            $till = strtotime($jail->getUntil()->format('Y-m-d H:i:s'));
            if ($now <= $till) {
                return true;
            }
            $em->remove($jail);
            $em->flush();
        }
        return false;
    }
}
