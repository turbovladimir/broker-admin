<?php

namespace App\Repository;

use App\Entity\DistributionJob;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DistributionJob>
 *
 * @method DistributionJob|null find($id, $lockMode = null, $lockVersion = null)
 * @method DistributionJob|null findOneBy(array $criteria, array $orderBy = null)
 * @method DistributionJob[]    findAll()
 * @method DistributionJob[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DistributionJobRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DistributionJob::class);
    }

    //    /**
    //     * @return DistributionJob[] Returns an array of DistributionJob objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('d.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?DistributionJob
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
