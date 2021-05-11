<?php

namespace App\Repository;

use App\Entity\ComercialMuroTipo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ComercialMuroTipo|null find($id, $lockMode = null, $lockVersion = null)
 * @method ComercialMuroTipo|null findOneBy(array $criteria, array $orderBy = null)
 * @method ComercialMuroTipo[]    findAll()
 * @method ComercialMuroTipo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ComercialMuroTipoRepository extends ServiceEntityRepository
{
    /**
     * ComercialMuroTipoRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ComercialMuroTipo::class);
    }
}
