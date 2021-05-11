<?php

namespace App\Repository;

use App\Entity\TipoCanal;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method TipoCanal|null find($id, $lockMode = null, $lockVersion = null)
 * @method TipoCanal|null findOneBy(array $criteria, array $orderBy = null)
 * @method TipoCanal[]    findAll()
 * @method TipoCanal[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TipoCanalRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TipoCanal::class);
    }

    // /**
    //  * @return TipoCanal[] Returns an array of TipoCanal objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TipoCanal
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
