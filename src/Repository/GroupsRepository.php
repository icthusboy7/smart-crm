<?php
/**
 * Groups Repository
 */
namespace App\Repository;

use App\Entity\Groups;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Groups Repository class
 * @method Groups|null find($id, $lockMode = null, $lockVersion = null)
 * @method Groups|null findOneBy(array $criteria, array $orderBy = null)
 * @method Groups[]    findAll()
 * @method Groups[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GroupsRepository extends ServiceEntityRepository
{
    /**
     * GroupsRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Groups::class);
    }
}
