<?php
/**
 * Reason Repository
 */
namespace App\Repository;

use App\Entity\Reason;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Reason Repository class
 * @method Reason|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reason|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reason[]    findAll()
 * @method Reason[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReasonRepository extends ServiceEntityRepository
{
    /**
     * ReasonRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Reason::class);
    }
}
