<?php
/**
 * CommercialResponsable Repository
 */
namespace App\Repository;

use App\Entity\CommercialResponsable;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * CommercialResponsable Repository class
 * @method CommercialResponsable|null find($id, $lockMode = null, $lockVersion = null)
 * @method CommercialResponsable|null findOneBy(array $criteria, array $orderBy = null)
 * @method CommercialResponsable[]    findAll()
 * @method CommercialResponsable[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommercialResponsableRepository extends ServiceEntityRepository
{
    /**
     * CommercialResponsableRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CommercialResponsable::class);
    }

    /**
     * Return all responsables as users
     *
     * @return mixed
     */
    public function getResponsables()
    {
        $qb = $this->createQueryBuilder('r')
            ->select('u')
            ->join(User::class, 'u', 'WITH', 'r.Responsable = u.regNumber');

        return $qb->getQuery()->getResult();
    }
}
