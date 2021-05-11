<?php

namespace App\Repository;

use App\Entity\ComercialTaskType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ComercialTaskType|null find($id, $lockMode = null, $lockVersion = null)
 * @method ComercialTaskType|null findOneBy(array $criteria, array $orderBy = null)
 * @method ComercialTaskType[]    findAll()
 * @method ComercialTaskType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ComercialTaskTypeRepository extends ServiceEntityRepository
{
    /**
     * ComercialTaskTypeRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ComercialTaskType::class);
    }

    // /**
    //  * @return ComercialTaskType[] Returns an array of ComercialTaskType objects
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
