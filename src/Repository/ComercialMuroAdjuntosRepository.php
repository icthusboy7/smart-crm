<?php

namespace App\Repository;

use App\Entity\ComercialMuroAdjuntos;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ComercialMuroAdjuntosRepository|null find($id, $lockMode = null, $lockVersion = null)
 * @method ComercialMuroAdjuntosRepository|null findOneBy(array $criteria, array $orderBy = null)
 * @method ComercialMuroAdjuntosRepository[]    findAll()
 * @method ComercialMuroAdjuntosRepository[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ComercialMuroAdjuntosRepository extends ServiceEntityRepository
{
    /**
     * ComercialMuroAdjuntosRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ComercialMuroAdjuntos::class);
    }
}
