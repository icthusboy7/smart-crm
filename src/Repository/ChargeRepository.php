<?php
/**
 * Charge Repository
 */
namespace App\Repository;

use App\Entity\Charge;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Charge repository class
 * @method Charge|null find($id, $lockMode = null, $lockVersion = null)
 * @method Charge|null findOneBy(array $criteria, array $orderBy = null)
 * @method Charge[]    findAll()
 * @method Charge[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ChargeRepository extends ServiceEntityRepository
{
    /**
     * ChargeRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Charge::class);
    }
}
