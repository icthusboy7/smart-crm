<?php

namespace App\Repository;

use App\Entity\ComercialExpedienteStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ComercialExpedienteStatus|null find($id, $lockMode = null, $lockVersion = null)
 * @method ComercialExpedienteStatus|null findOneBy(array $criteria, array $orderBy = null)
 * @method ComercialExpedienteStatus[]    findAll()
 * @method ComercialExpedienteStatus[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ComercialExpedienteStatusRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ComercialExpedienteStatus::class);
    }

    // /**
    //  * @return ComercialExpedienteStatus[] Returns an array of ComercialExpedienteStatus objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ComercialExpedienteStatus
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
