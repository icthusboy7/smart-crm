<?php
/**
 * GestorResponsable Repository
 */
namespace App\Repository;

use App\Entity\GestorResponsable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * GestorResponsable Repository class
 * @method GestorResponsable|null find($id, $lockMode = null, $lockVersion = null)
 * @method GestorResponsable|null findOneBy(array $criteria, array $orderBy = null)
 * @method GestorResponsable[]    findAll()
 * @method GestorResponsable[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GestorResponsableRepository extends ServiceEntityRepository
{
    /**
     * GestorResponsableRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, GestorResponsable::class);
    }
}
