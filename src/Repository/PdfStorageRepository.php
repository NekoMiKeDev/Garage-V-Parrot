<?php

namespace App\Repository;

use App\Entity\PdfStorage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PdfStorage>
 *
 * @method PdfStorage|null find($id, $lockMode = null, $lockVersion = null)
 * @method PdfStorage|null findOneBy(array $criteria, array $orderBy = null)
 * @method PdfStorage[]    findAll()
 * @method PdfStorage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PdfStorageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PdfStorage::class);
    }

//    /**
//     * @return PdfStorage[] Returns an array of PdfStorage objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?PdfStorage
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
