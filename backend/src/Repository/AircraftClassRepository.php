<?php

namespace App\Repository;

use App\Entity\AircraftClass;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AircraftClass|null find($id, $lockMode = null, $lockVersion = null)
 * @method AircraftClass|null findOneBy(array $criteria, array $orderBy = null)
 * @method AircraftClass[]    findAll()
 * @method AircraftClass[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AircraftClassRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AircraftClass::class);
    }

    // /**
    //  * @return AircraftClass[] Returns an array of AircraftClass objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AircraftClass
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
