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

    public function getList() : array
    {
        $sql = "
            select q.id, q.status, q.added_at,
       sum(case when ssj.status = 'in_queue' then 1 else 0 end ) queued,
       sum(case when ssj.status = 'sent' then 1 else 0 end ) sent,
       sum(case when ssj.status = 'error' then 1 else 0 end ) error
            from sms_queue q
            left join public.sending_sms_job ssj on q.id = ssj.sms_queue_id
            group by q.id
            order by q.id desc 
            ;
        ";
        return $this->getEntityManager()->getConnection()->fetchAllAssociative($sql);
    }

    public function actualizeStatuses() : void
    {
        $sql ="
            update sms_queue
            set status = 'Отправлено'
            where id in (
            select q.id
            from sms_queue q
         join public.sending_sms_job ssj on q.id = ssj.sms_queue_id
        group by q.id
        HAVING count(case when ssj.status = 'sent' then 1 end) > 0
        and count(case when ssj.status = 'in_queue' then 1 end) = 0
            );
        ";
        $this->getEntityManager()->getConnection()->executeQuery($sql);
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
