<?php
/**
 * StatusQuotation Repository
 */
namespace App\Repository;

use App\Entity\StatusQuotation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * StatusQuotation Repository class
 * @method StatusQuotation|null find($id, $lockMode = null, $lockVersion = null)
 * @method StatusQuotation|null findOneBy(array $criteria, array $orderBy = null)
 * @method StatusQuotation[]    findAll()
 * @method StatusQuotation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StatusQuotationRepository extends ServiceEntityRepository
{
    /**
     * StatusQuotationRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, StatusQuotation::class);
    }
}
