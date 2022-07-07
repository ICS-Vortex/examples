<?php

namespace App\Repository;

class CustomTourRequestRepository extends BaseRepository
{
    /**
     * @return array
     */
    public function getTourFromQueue()
    {
        $search = $this->createQueryBuilder('tour')
            ->where('tour.started = :not_started')
            ->setParameter('not_started', false)
            ->orderBy('tour.start', 'ASC')
            ->setMaxResults(1)
            ->getQuery()
            ->getResult();

        return $search;
    }
}
