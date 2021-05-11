<?php

namespace App\Repository;

use App\Entity\ComercialExpedienteFavoritos;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ComercialExpedienteFavoritos|null find($id, $lockMode = null, $lockVersion = null)
 * @method ComercialExpedienteFavoritos|null findOneBy(array $criteria, array $orderBy = null)
 * @method ComercialExpedienteFavoritos[]    findAll()
 * @method ComercialExpedienteFavoritos[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ComercialExpedienteFavoritosRepository extends ServiceEntityRepository
{
    /**
     * ComercialExpedienteFavoritosRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ComercialExpedienteFavoritos::class);
    }

    /**
     * @param int $author
     * @param int $pipeline
     *
     * @return mixed
     */
    public function findUsersFavouritePipeline(int $author, int $pipeline)
    {
        return $this->createQueryBuilder('e')
            ->distinct()
            ->where('e.expediente = :expID')
            ->andWhere('e.user != :autor')
            ->setParameter(':expID', $pipeline)
            ->setParameter(':autor', $author)
            ->getQuery()
            ->getResult();
    }
}
