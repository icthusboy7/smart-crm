<?php

namespace App\Repository;

use App\Entity\ComercialUsuarioZona;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method ComercialUsuarioZona|null find($id, $lockMode = null, $lockVersion = null)
 * @method ComercialUsuarioZona|null findOneBy(array $criteria, array $orderBy = null)
 * @method ComercialUsuarioZona[]    findAll()
 * @method ComercialUsuarioZona[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ComercialUsuarioZonaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ComercialUsuarioZona::class);
    }

    // /**
    //  * @return ComercialUsuarioZona[] Returns an array of ComercialUsuarioZona objects
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
    public function findOneBySomeField($value): ?ComercialUsuarioZona
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
