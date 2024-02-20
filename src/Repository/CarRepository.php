<?php

namespace App\Repository;

use App\Entity\Car;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Car>
 *
 * @method Car|null find($id, $lockMode = null, $lockVersion = null)
 * @method Car|null findOneBy(array $criteria, array $orderBy = null)
 * @method Car[]    findAll()
 * @method Car[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CarRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Car::class);
    }

    /**
     * @param float $price
     * @return Car[] Returns an array of Car objects
     */
    public function findByPrice(float $price): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.price > :price')
            ->setParameter('price', $price)
            ->getQuery()
            ->getResult();
    }

    public function findByMileage(float $mileage): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.mileage > :mileage')
            ->setParameter('mileage', $mileage)
            ->getQuery()
            ->getResult();
    }

    public function findByManufactureYear(int $manufactureYear): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.yearOfManufacture = :manufactureYear')
            ->setParameter('manufactureYear', $manufactureYear)
            ->getQuery()
            ->getResult();
    }

    public function findBySearch($search)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.model LIKE :search')
            ->setParameter('search', '%' . $search . '%')
            ->getQuery()
            ->getResult();
    }
}