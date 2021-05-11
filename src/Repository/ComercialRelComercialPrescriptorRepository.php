<?php

namespace App\Repository;

use App\Entity\ComercialRelComercialPrescriptor;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method ComercialRelComercialPrescriptor|null find($id, $lockMode = null, $lockVersion = null)
 * @method ComercialRelComercialPrescriptor|null findOneBy(array $criteria, array $orderBy = null)
 * @method ComercialRelComercialPrescriptor[]    findAll()
 * @method ComercialRelComercialPrescriptor[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ComercialRelComercialPrescriptorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ComercialRelComercialPrescriptor::class);
    }

    // /**
    //  * @return ComercialRelComercialPrescriptor[] Returns an array of ComercialRelComercialPrescriptor objects
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
    public function findOneBySomeField($value): ?ComercialRelComercialPrescriptor
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
