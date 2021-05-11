<?php

namespace App\Repository;

use App\Entity\ComercialCotizacionFavoritos;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ComercialCotizacionFavoritos|null find($id, $lockMode = null, $lockVersion = null)
 * @method ComercialCotizacionFavoritos|null findOneBy(array $criteria, array $orderBy = null)
 * @method ComercialCotizacionFavoritos[]    findAll()
 * @method ComercialCotizacionFavoritos[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ComercialCotizacionFavoritosRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ComercialCotizacionFavoritos::class);
    }

    // /**
    //  * @return ComercialCotizacionFavoritos[] Returns an array of ComercialCotizacionFavoritos objects
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
    public function findOneBySomeField($value): ?ComercialCotizacionFavoritos
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
