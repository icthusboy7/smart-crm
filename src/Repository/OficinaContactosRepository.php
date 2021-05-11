<?php
/**
 * OficinaContactos Repository
 */
namespace App\Repository;

use App\Entity\OficinaContactos;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * OficinaContactos Repository class
 * @method OficinaContactos|null find($id, $lockMode = null, $lockVersion = null)
 * @method OficinaContactos|null findOneBy(array $criteria, array $orderBy = null)
 * @method OficinaContactos[]    findAll()
 * @method OficinaContactos[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OficinaContactosRepository extends ServiceEntityRepository
{
    /**
     * OficinaContactosRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, OficinaContactos::class);
    }
}
