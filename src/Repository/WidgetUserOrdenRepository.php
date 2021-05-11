<?php
/**
 * WidgetUserOrden Repository
 */
namespace App\Repository;

use App\Entity\WidgetUserOrden;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * WidgetUserOrden Repository class
 * @method WidgetUserOrden|null find($id, $lockMode = null, $lockVersion = null)
 * @method WidgetUserOrden|null findOneBy(array $criteria, array $orderBy = null)
 * @method WidgetUserOrden[]    findAll()
 * @method WidgetUserOrden[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WidgetUserOrdenRepository extends ServiceEntityRepository
{
    /**
     * WidgetUserOrdenRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, WidgetUserOrden::class);
    }
}
