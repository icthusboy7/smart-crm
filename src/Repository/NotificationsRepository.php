<?php
/**
 * Notifications Repository
 */
namespace App\Repository;

use App\Entity\Notifications;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Notifications Repository class
 * @method Notifications|null find($id, $lockMode = null, $lockVersion = null)
 * @method Notifications|null findOneBy(array $criteria, array $orderBy = null)
 * @method Notifications[]    findAll()
 * @method Notifications[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NotificationsRepository extends ServiceEntityRepository
{
    /**
     * NotificationsRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Notifications::class);
    }
}
