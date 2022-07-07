<?php

namespace App\Repository;

use App\Entity\CouponFile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CouponFile|null find($id, $lockMode = null, $lockVersion = null)
 * @method CouponFile|null findOneBy(array $criteria, array $orderBy = null)
 * @method CouponFile[]    findAll()
 * @method CouponFile[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CouponFileRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CouponFile::class);
    }

    // /**
    //  * @return CouponFile[] Returns an array of CouponFile objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CouponFile
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
