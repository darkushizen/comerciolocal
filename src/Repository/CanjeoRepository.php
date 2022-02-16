<?php

namespace App\Repository;

use App\Entity\Canjeo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Canjeo|null find($id, $lockMode = null, $lockVersion = null)
 * @method Canjeo|null findOneBy(array $criteria, array $orderBy = null)
 * @method Canjeo[]    findAll()
 * @method Canjeo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CanjeoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Canjeo::class);
    }

    // /**
    //  * @return Canjeo[] Returns an array of Canjeo objects
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
    public function findOneBySomeField($value): ?Canjeo
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
