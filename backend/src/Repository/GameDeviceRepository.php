<?php

namespace App\Repository;

use App\Entity\GameDevice;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method GameDevice|null find($id, $lockMode = null, $lockVersion = null)
 * @method GameDevice|null findOneBy(array $criteria, array $orderBy = null)
 * @method GameDevice[]    findAll()
 * @method GameDevice[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GameDeviceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GameDevice::class);
    }

    // /**
    //  * @return GameDevice[] Returns an array of GameDevice objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?GameDevice
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
