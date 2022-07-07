<?php

namespace App\Repository;

class TournamentCouponRepository extends BaseRepository
{
    /**
     * @return mixed
     */
    public function getUsersWithCoupons(): mixed
    {
        $qb = $this->createQueryBuilder('c')
            ->where('c.pilot IS NOT NULL')
            ->orderBy('c.id', 'ASC');
        $query = $qb->getQuery();

        return $query->execute();
    }
}
