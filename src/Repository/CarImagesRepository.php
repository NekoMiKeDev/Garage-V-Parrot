<?php

namespace App\Repository;

use App\Entity\CarImages;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CarImages>
 *
 * @method CarImages|null find($id, $lockMode = null, $lockVersion = null)
 * @method CarImages|null findOneBy(array $criteria, array $orderBy = null)
 * @method CarImages[]    findAll()
 * @method CarImages[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CarImagesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CarImages::class);
    }

//    /**
//     * @return CarImages[] Returns an array of CarImages objects
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

//    public function findOneBySomeField($value): ?CarImages
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
