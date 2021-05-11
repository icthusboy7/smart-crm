<?php

namespace App\Repository;

use App\Entity\MasterFamilySub;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method MasterFamilySub|null find($id, $lockMode = null, $lockVersion = null)
 * @method MasterFamilySub|null findOneBy(array $criteria, array $orderBy = null)
 * @method MasterFamilySub[]    findAll()
 * @method MasterFamilySub[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MasterFamilySubRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, MasterFamilySub::class);
    }

    // /**
    //  * @return MasterFamilySub[] Returns an array of MasterFamilySub objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MasterFamilySub
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
