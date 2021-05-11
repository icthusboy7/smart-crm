<?php

namespace App\Repository;

use App\Entity\ComercialUsuarioTipo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method ComercialUsuarioTipo|null find($id, $lockMode = null, $lockVersion = null)
 * @method ComercialUsuarioTipo|null findOneBy(array $criteria, array $orderBy = null)
 * @method ComercialUsuarioTipo[]    findAll()
 * @method ComercialUsuarioTipo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ComercialUsuarioTipoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ComercialUsuarioTipo::class);
    }

    // /**
    //  * @return ComercialUsuarioTipo[] Returns an array of ComercialUsuarioTipo objects
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
    public function findOneBySomeField($value): ?ComercialUsuarioTipo
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
