<?php

namespace App\Repository;

use App\Entity\OfferCheckerRelation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<OfferCheckerRelation>
 *
 * @method OfferCheckerRelation|null find($id, $lockMode = null, $lockVersion = null)
 * @method OfferCheckerRelation|null findOneBy(array $criteria, array $orderBy = null)
 * @method OfferCheckerRelation[]    findAll()
 * @method OfferCheckerRelation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OfferCheckerRelationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OfferCheckerRelation::class);
    }

    //    /**
    //     * @return OfferCheckerRelation[] Returns an array of OfferCheckerRelation objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('o')
    //            ->andWhere('o.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('o.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?OfferCheckerRelation
    //    {
    //        return $this->createQueryBuilder('o')
    //            ->andWhere('o.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
