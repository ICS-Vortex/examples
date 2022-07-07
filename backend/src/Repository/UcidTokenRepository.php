<?php

namespace App\Repository;

use App\Entity\UcidToken;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UcidToken|null find($id, $lockMode = null, $lockVersion = null)
 * @method UcidToken|null findOneBy(array $criteria, array $orderBy = null)
 * @method UcidToken[]    findAll()
 * @method UcidToken[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UcidTokenRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UcidToken::class);
    }

    // /**
    //  * @return UcidToken[] Returns an array of UcidToken objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UcidToken
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
