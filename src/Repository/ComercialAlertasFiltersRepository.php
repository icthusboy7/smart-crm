<?php
/**
 * AlertasFilters Repository
 */
namespace App\Repository;

use App\Entity\ComercialAlertasFilters;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * OfficeFilters Repository class
 * @method ComercialAlertasFilters|null find($id, $lockMode = null, $lockVersion = null)
 * @method ComercialAlertasFilters|null findOneBy(array $criteria, array $orderBy = null)
 * @method ComercialAlertasFilters[]    findAll()
 * @method ComercialAlertasFilters[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ComercialAlertasFiltersRepository extends ServiceEntityRepository
{
    /**
     * AlertasFiltersRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ComercialAlertasFilters::class);
    }
}
