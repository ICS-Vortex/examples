<?php

namespace App\Repository;

use App\Entity\TournamentRequest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TournamentRequest|null find($id, $lockMode = null, $lockVersion = null)
 * @method TournamentRequest|null findOneBy(array $criteria, array $orderBy = null)
 * @method TournamentRequest[]    findAll()
 * @method TournamentRequest[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TournamentRequestRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TournamentRequest::class);
    }

    // /**
    //  * @return TournamentRequest[] Returns an array of TournamentRequest objects
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
    public function findOneBySomeField($value): ?TournamentRequest
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
