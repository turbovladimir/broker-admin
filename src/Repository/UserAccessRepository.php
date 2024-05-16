<?php

namespace App\Repository;

use App\Entity\UserAccess;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserAccess>
 *
 * @method UserAccess|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserAccess|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserAccess[]    findAll()
 * @method UserAccess[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserAccessRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserAccess::class);
    }

    public function findLimitToday(string $ip) : ?UserAccess
    {
        $qb = $this->createQueryBuilder('user_access');
        $today = new \DateTime();
        $from = (clone $today)->setTime(0,0,0);
        $to = (clone $today)->setTime(23,59);
//        $format = 'YYYY-MM-DD HH:MI:SS';
        $qb
            ->where($qb->expr()->between('user_access.addedAt', ':from', ':to'))
            ->andWhere('user_access.ip = :ip')
            ->setParameter('ip', $ip)
            ->setParameter('from', $from)
            ->setParameter('to', $to)
        ;

        return $qb->getQuery()->getOneOrNullResult();
    }

    //    /**
    //     * @return UserAccess[] Returns an array of UserAccess objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('u.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?UserAccess
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
