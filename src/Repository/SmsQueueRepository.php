<?php

namespace App\Repository;

use App\Entity\SmsQueue;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SmsQueue>
 *
 * @method SmsQueue|null find($id, $lockMode = null, $lockVersion = null)
 * @method SmsQueue|null findOneBy(array $criteria, array $orderBy = null)
 * @method SmsQueue[]    findAll()
 * @method SmsQueue[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SmsQueueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SmsQueue::class);
    }

    //    /**
    //     * @return SmsQueue[] Returns an array of SmsQueue objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('s.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?SmsQueue
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
