<?php

namespace App\Repository;

use App\Entity\ComercialProducto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ComercialProducto|null find($id, $lockMode = null, $lockVersion = null)
 * @method ComercialProducto|null findOneBy(array $criteria, array $orderBy = null)
 * @method ComercialProducto[]    findAll()
 * @method ComercialProducto[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ComercialProductoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ComercialProducto::class);
    }

    // /**
    //  * @return ComercialProducto[] Returns an array of ComercialProducto objects
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
    public function findOneBySomeField($value): ?ComercialProducto
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
