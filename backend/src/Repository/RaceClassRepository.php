<?php

namespace App\Repository;

use App\Entity\RaceClass;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RaceClass|null find($id, $lockMode = null, $lockVersion = null)
 * @method RaceClass|null findOneBy(array $criteria, array $orderBy = null)
 * @method RaceClass[]    findAll()
 * @method RaceClass[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RaceClassRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RaceClass::class);
    }

    // /**
    //  * @return RaceClass[] Returns an array of RaceClass objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?RaceClass
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
