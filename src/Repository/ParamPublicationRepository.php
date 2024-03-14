<?php

namespace App\Repository;

use App\Entity\ParamPublication;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ParamPublication>
 *
 * @method ParamPublication|null find($id, $lockMode = null, $lockVersion = null)
 * @method ParamPublication|null findOneBy(array $criteria, array $orderBy = null)
 * @method ParamPublication[]    findAll()
 * @method ParamPublication[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ParamPublicationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ParamPublication::class);
    }

    //    /**
    //     * @return ParamPublication[] Returns an array of ParamPublication objects
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

    //    public function findOneBySomeField($value): ?ParamPublication
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
