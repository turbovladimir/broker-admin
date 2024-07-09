<?php

namespace App\Repository;

use App\Enums\SendingJobStatus;
use App\Entity\SendingSmsJob;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SendingSmsJob>
 *
 * @method SendingSmsJob|null find($id, $lockMode = null, $lockVersion = null)
 * @method SendingSmsJob|null findOneBy(array $criteria, array $orderBy = null)
 * @method SendingSmsJob[]    findAll()
 * @method SendingSmsJob[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SendingSmsJobRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SendingSmsJob::class);
    }

    /**
     * @return SendingSmsJob[]
     */
    public function findEnqueuedJobs() : array
    {
        return $this->createQueryBuilder('sending_sms_job')
            ->where('sending_sms_job.status = :status')
            ->andWhere('sending_sms_job.sendingTime <= :now')
            ->setParameter('status', SendingJobStatus::InQueue)
            ->setParameter('now', new \DateTime())
            ->orderBy('sending_sms_job.sendingTime', 'ASC')
            ->getQuery()
            ->getResult();
    }

    //    /**
    //     * @return SendingSmsJob[] Returns an array of SendingSmsJob objects
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

    //    public function findOneBySomeField($value): ?SendingSmsJob
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
