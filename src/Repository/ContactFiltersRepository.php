<?php
/**
 * ContactsFilters Repository
 */
namespace App\Repository;

use App\Entity\ContactFilters;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * ContactFilters repository class
 * @method ContactFilters|null find($id, $lockMode = null, $lockVersion = null)
 * @method ContactFilters|null findOneBy(array $criteria, array $orderBy = null)
 * @method ContactFilters[]    findAll()
 * @method ContactFilters[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContactFiltersRepository extends ServiceEntityRepository
{
    /**
     * ContactsFiltersRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ContactFilters::class);
    }
}
