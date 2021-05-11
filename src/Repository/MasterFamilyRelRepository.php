<?php

namespace App\Repository;

use App\Entity\MasterFamilyRel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method MasterFamilyRel|null find($id, $lockMode = null, $lockVersion = null)
 * @method MasterFamilyRel|null findOneBy(array $criteria, array $orderBy = null)
 * @method MasterFamilyRel[]    findAll()
 * @method MasterFamilyRel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MasterFamilyRelRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, MasterFamilyRel::class);
    }

    // /**
    //  * @return MasterFamilyRel[] Returns an array of MasterFamilyRel objects
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
    public function findOneBySomeField($value): ?MasterFamilyRel
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
