<?php

namespace App\Repository;

use App\Entity\MasterQuotation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method MasterQuotation|null find($id, $lockMode = null, $lockVersion = null)
 * @method MasterQuotation|null findOneBy(array $criteria, array $orderBy = null)
 * @method MasterQuotation[]    findAll()
 * @method MasterQuotation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MasterQuotationRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, MasterQuotation::class);
    }

    /**
     * Filter cotizacion
     * @param string $search
     *
     * @return mixed
     */
    public function findByNumCoti(string $search)
    {
        $queryBuild = $this->createQueryBuilder('a')
            ->select('a.codigoCoti id', 'a.codigoCoti text')
            ->where('a.codigoCoti LIKE :numcoti')
            ->setParameter('numcoti', '%'.$search.'%');

        return $queryBuild->getQuery()->getResult();
    }

    // /**
    //  * @return MasterQuotation[] Returns an array of MasterQuotation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MasterQuotation
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
