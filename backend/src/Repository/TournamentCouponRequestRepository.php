<?php

namespace App\Repository;

use App\Entity\TournamentCouponRequest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TournamentCouponRequest|null find($id, $lockMode = null, $lockVersion = null)
 * @method TournamentCouponRequest|null findOneBy(array $criteria, array $orderBy = null)
 * @method TournamentCouponRequest[]    findAll()
 * @method TournamentCouponRequest[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TournamentCouponRequestRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TournamentCouponRequest::class);
    }

    // /**
    //  * @return TournamentCouponRequest[] Returns an array of TournamentCouponRequest objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TournamentCouponRequest
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
