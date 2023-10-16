<?php

namespace App\Repository;

use App\Entity\Caliber;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Caliber>
 *
 * @method Caliber|null find($id, $lockMode = null, $lockVersion = null)
 * @method Caliber|null findOneBy(array $criteria, array $orderBy = null)
 * @method Caliber[]    findAll()
 * @method Caliber[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CaliberRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Caliber::class);
    }

//    /**
//     * @return Caliber[] Returns an array of Caliber objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Caliber
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
