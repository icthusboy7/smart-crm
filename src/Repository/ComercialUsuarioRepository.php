<?php

namespace App\Repository;

use App\Entity\ComercialUsuario;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method ComercialUsuario|null find($id, $lockMode = null, $lockVersion = null)
 * @method ComercialUsuario|null findOneBy(array $criteria, array $orderBy = null)
 * @method ComercialUsuario[]    findAll()
 * @method ComercialUsuario[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ComercialUsuarioRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ComercialUsuario::class);
    }

    // /**
    //  * @return ComercialUsuario[] Returns an array of ComercialUsuario objects
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
