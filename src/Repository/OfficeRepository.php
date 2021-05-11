<?php
/**
 * Office repository
 */
namespace App\Repository;

use App\Entity\Office;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Office Repository class
 * @method Office|null find($id, $lockMode = null, $lockVersion = null)
 * @method Office|null findOneBy(array $criteria, array $orderBy = null)
 * @method Office[]    findAll()
 * @method Office[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OfficeRepository extends ServiceEntityRepository
{
    /**
     * OfficeRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Office::class);
    }

    /**
     * Return Offices for select options finding %"nombre"%
     * @param $value
     * @return mixed
     */
    public function findOfficesSelect($value)
    {
        return $this->createQueryBuilder('o')
            ->select('o.CodigoOficina id, o.Centro text')
            ->andWhere('o.Centro LIKE :val')
            ->setParameter('val', '%'.$value.'%')
            ->orderBy('o.Centro', 'ASC')
            ->setMaxResults(50)
            ->getQuery()
            ->getResult();
    }

    /**
     * Return Offices for select options finding %"Centro"%
     * @param $value
     *
     * @return mixed
     */
    public function findOfficesSelect2($value)
    {
        return $this->createQueryBuilder('o')
            ->select('o.Centro id, o.Centro text')
            ->andWhere('o.Centro LIKE :val')
            ->setParameter('val', '%'.$value.'%')
            ->orderBy('o.Centro', 'ASC')
            ->setMaxResults(50)
            ->getQuery()
            ->getResult();
    }

    /**
     * Find office using filters (If filters is not null)
     * @param $value
     * @return \Doctrine\ORM\Query
     */
    public function findByOfficeForm($value)
    {
        $query = $this->createQueryBuilder('o');
        if (isset($value['office_find'])) {
            $query->where('o.Centro LIKE :name')->setParameter('name', '%'.$value['office_find'].'%');
            $query->orWhere('o.DireccionGeneralCentro LIKE :dg')->setParameter('dg', '%'.$value['office_find'].'%');
            $query->orWhere('o.DireccionTerritorialCentro LIKE :dt')->setParameter('dt', '%'.$value['office_find'].'%');
            $query->orWhere('o.DireccionAreaNegocioCentro LIKE :dan')->setParameter('dan', '%'.$value['office_find'].'%');
        }
        return $query->getQuery();
    }
}
