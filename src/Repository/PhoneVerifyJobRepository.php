<?php

namespace App\Repository;

use App\Entity\PhoneVerifyJob;
use App\Service\Auth\DTO\VerifyCodeRequest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PhoneVerifyJob>
 *
 * @method PhoneVerifyJob|null find($id, $lockMode = null, $lockVersion = null)
 * @method PhoneVerifyJob|null findOneBy(array $criteria, array $orderBy = null)
 * @method PhoneVerifyJob[]    findAll()
 * @method PhoneVerifyJob[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PhoneVerifyJobRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PhoneVerifyJob::class);
    }

    public function findActiveJob(string $sessionId, int $code, string $phone) : ?PhoneVerifyJob
    {
        $qb = $this->createQueryBuilder('phone_verify_job');

        $qb
            ->where('phone_verify_job.isActive = :is_active')
            ->andWhere('phone_verify_job.sessionId = :session_id')
            ->andWhere('phone_verify_job.code = :code')
            ->andWhere('phone_verify_job.phone = :phone')
            ->setParameter('is_active', true)
            ->setParameter('session_id', $sessionId)
            ->setParameter('code', $code)
            ->setParameter('phone', $phone)
            ;

        return $qb->getQuery()->getOneOrNullResult();
    }

    public function hasVerifiedJob(string $sessionId) : bool
    {
        return (bool)$this->createQueryBuilder('phone_verify_job')
            ->select('count(phone_verify_job.id)')
            ->where('phone_verify_job.isActive = 1')
            ->andWhere('phone_verify_job.sessionId = :session_id')
            ->andWhere('phone_verify_job.isVerified = 1')
            ->setParameter('session_id', $sessionId)
            ->getQuery()->getSingleScalarResult()
            ;
    }

    //    /**
    //     * @return PhoneVerifyJob[] Returns an array of PhoneVerifyJob objects
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

    //    public function findOneBySomeField($value): ?PhoneVerifyJob
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
