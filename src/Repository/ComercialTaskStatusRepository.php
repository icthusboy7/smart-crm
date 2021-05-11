<?php

namespace App\Repository;

use App\Entity\ComercialTaskStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ComercialTaskStatus|null find($id, $lockMode = null, $lockVersion = null)
 * @method ComercialTaskStatus|null findOneBy(array $criteria, array $orderBy = null)
 * @method ComercialTaskStatus[]    findAll()
 * @method ComercialTaskStatus[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ComercialTaskStatusRepository extends ServiceEntityRepository
{
    /**
     * ComercialTaskStatusRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ComercialTaskStatus::class);
    }

    // /**
    //  * @return ComercialTaskStatus[] Returns an array of ComercialTaskStatus objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ComercialTaskType
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
