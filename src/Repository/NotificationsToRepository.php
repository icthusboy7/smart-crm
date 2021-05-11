<?php
/**
 * NotificationsTo Repository
 */
namespace App\Repository;

use App\Entity\NotificationsTo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * NotificationsTo Repository class
 * @method NotificationsTo|null find($id, $lockMode = null, $lockVersion = null)
 * @method NotificationsTo|null findOneBy(array $criteria, array $orderBy = null)
 * @method NotificationsTo[]    findAll()
 * @method NotificationsTo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NotificationsToRepository extends ServiceEntityRepository
{
    /**
     * NotificationsToRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, NotificationsTo::class);
    }
}
