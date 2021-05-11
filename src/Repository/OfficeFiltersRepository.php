<?php
/**
 * OfficeFilters Repository
 */
namespace App\Repository;

use App\Entity\OfficeFilters;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * OfficeFilters Repository class
 * @method OfficeFilters|null find($id, $lockMode = null, $lockVersion = null)
 * @method OfficeFilters|null findOneBy(array $criteria, array $orderBy = null)
 * @method OfficeFilters[]    findAll()
 * @method OfficeFilters[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OfficeFiltersRepository extends ServiceEntityRepository
{
    /**
     * OfficeFiltersRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, OfficeFilters::class);
    }
}
