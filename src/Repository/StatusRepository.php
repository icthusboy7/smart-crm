<?php
/**
 * StatusQuotation Repository
 */
namespace App\Repository;

use App\Entity\Status;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Status Repository class
 * @method Status|null find($id, $lockMode = null, $lockVersion = null)
 * @method Status|null findOneBy(array $criteria, array $orderBy = null)
 * @method Status[]    findAll()
 * @method Status[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StatusRepository extends ServiceEntityRepository
{
    /**
     * StatusRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Status::class);
    }
}
