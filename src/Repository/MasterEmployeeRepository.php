<?php

namespace App\Repository;

use App\Entity\MasterEmployee;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method MasterEmployee|null find($id, $lockMode = null, $lockVersion = null)
 * @method MasterEmployee|null findOneBy(array $criteria, array $orderBy = null)
 * @method MasterEmployee[]    findAll()
 * @method MasterEmployee[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MasterEmployeeRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, MasterEmployee::class);
    }

    // /**
    //  * @return MasterEmployee[] Returns an array of MasterEmployee objects
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
    public function findOneBySomeField($value): ?MasterEmployee
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
