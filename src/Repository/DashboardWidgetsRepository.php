<?php
/**
 * DashboardWidgets Repository
 */
namespace App\Repository;

use App\Entity\DashboardWidgets;
use App\Entity\WidgetUserOrden;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * DashboardWidgets Repository class
 * @method DashboardWidgets|null find($id, $lockMode = null, $lockVersion = null)
 * @method DashboardWidgets|null findOneBy(array $criteria, array $orderBy = null)
 * @method DashboardWidgets[]    findAll()
 * @method DashboardWidgets[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DashboardWidgetsRepository extends ServiceEntityRepository
{
    /**
     * DashboardWidgetsRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, DashboardWidgets::class);
    }

    /**
     * Find widgets for an user
     * @param $user
     * @return mixed
     */
    public function findUserWidgetsOrder($user)
    {
        $query = $this->createQueryBuilder('u')
            ->from(WidgetUserOrden::class, 'o')
            ->where('o.user = :user')
            ->orderBy('o.orden', 'ASC')
            ->setParameter('user', $user)
            ->getQuery();

        return $query->getResult();
    }
}
