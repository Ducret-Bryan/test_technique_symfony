<?php

namespace App\Repository;

use App\Entity\Vehicles;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Vehicles>
 */
class VehiclesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Vehicles::class);
    }

    public function hasVehicle($vehicle)
    {
        $vehicle = $this->createQueryBuilder('v')
            ->andWhere('v.brand = :val')
            ->andWhere('v.model = :val2')
            ->setParameter('val', $vehicle->getBrand())
            ->setParameter('val2', $vehicle->getModel())
            ->getQuery()
            ->getResult();

        return ($vehicle) ? true : false;
    }

    //    /**
    //     * @return Vehicles[] Returns an array of Vehicles objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('v')
    //            ->andWhere('v.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('v.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Vehicles
    //    {
    //        return $this->createQueryBuilder('v')
    //            ->andWhere('v.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

}
