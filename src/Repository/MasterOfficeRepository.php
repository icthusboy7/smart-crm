<?php

namespace App\Repository;

use App\Entity\MasterOffice;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method MasterOffice|null find($id, $lockMode = null, $lockVersion = null)
 * @method MasterOffice|null findOneBy(array $criteria, array $orderBy = null)
 * @method MasterOffice[]    findAll()
 * @method MasterOffice[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MasterOfficeRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, MasterOffice::class);
    }

    /**
     * Return Offices for select options finding %"nombre"%
     * @param $value
     * @return mixed
     */
    public function findOfficesSelect($value)
    {
        return $this->createQueryBuilder('o')
            ->select('o.codigo id, o.nombre text')
            ->andWhere('o.nombre LIKE :val')
            ->setParameter('val', '%'.$value.'%')
            ->orderBy('o.nombre', 'ASC')
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
            $query->where('o.nombre LIKE :name')->setParameter('name', '%'.$value['office_find'].'%');
            $query->orWhere('o.dg LIKE :dg')->setParameter('dg', '%'.$value['office_find'].'%');
            $query->orWhere('o.dt LIKE :dt')->setParameter('dt', '%'.$value['office_find'].'%');
            $query->orWhere('o.dan LIKE :dan')->setParameter('dan', '%'.$value['office_find'].'%');
        }
        return $query->getQuery();
    }

    /**
     * Find offices child
     * @param $value
     *
     * @return mixed
     */
    public function findOfficeHeritage($value)
    {
        $query = $this->createQueryBuilder('o');
        $query->orWhere('o.dan LIKE :dan')->setParameter('dan', $value.'%');

        return $query->getQuery()->getResult();
    }
}
