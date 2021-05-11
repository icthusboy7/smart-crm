<?php

namespace App\Repository;

use App\Entity\MasterRequest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method MasterRequest|null find($id, $lockMode = null, $lockVersion = null)
 * @method MasterRequest|null findOneBy(array $criteria, array $orderBy = null)
 * @method MasterRequest[]    findAll()
 * @method MasterRequest[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MasterRequestRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, MasterRequest::class);
    }

    // /**
    //  * @return MasterRequest[] Returns an array of MasterRequest objects
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
    public function findOneBySomeField($value): ?MasterRequest
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
