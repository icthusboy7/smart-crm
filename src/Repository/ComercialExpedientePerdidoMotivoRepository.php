<?php

namespace App\Repository;

use App\Entity\ComercialExpedientePerdidoMotivo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ComercialExpedientePerdidoMotivo|null find($id, $lockMode = null, $lockVersion = null)
 * @method ComercialExpedientePerdidoMotivo|null findOneBy(array $criteria, array $orderBy = null)
 * @method ComercialExpedientePerdidoMotivo[]    findAll()
 * @method ComercialExpedientePerdidoMotivo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ComercialExpedientePerdidoMotivoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ComercialExpedientePerdidoMotivo::class);
    }

    // /**
    //  * @return ComercialExpedientePerdidoMotivo[] Returns an array of ComercialExpedientePerdidoMotivo objects
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
    public function findOneBySomeField($value): ?ComercialExpedientePerdidoMotivo
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
