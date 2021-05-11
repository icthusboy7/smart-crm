<?php
/**
 * GestorHorizontal Repository
 */
namespace App\Repository;

use App\Entity\GestorHorizontal;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * GestorHorizontal Repository class
 * @method GestorHorizontal|null find($id, $lockMode = null, $lockVersion = null)
 * @method GestorHorizontal|null findOneBy(array $criteria, array $orderBy = null)
 * @method GestorHorizontal[]    findAll()
 * @method GestorHorizontal[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GestorHorizontalRepository extends ServiceEntityRepository
{
    /**
     * GestorHorizontalRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, GestorHorizontal::class);
    }
}
