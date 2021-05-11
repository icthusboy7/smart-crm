<?php
/**
 * Visit Repository
 */
namespace App\Repository;

use App\Entity\Visit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Visit Repository class
 * @method Visit|null find($id, $lockMode = null, $lockVersion = null)
 * @method Visit|null findOneBy(array $criteria, array $orderBy = null)
 * @method Visit[]    findAll()
 * @method Visit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VisitRepository extends ServiceEntityRepository
{
    /**
     * VisitRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Visit::class);
    }

    /**
     * Encontrar visitas no canceladas
     * @param $status
     * @return mixed
     */
    public function findVisitNoCancel($status)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('v')
            ->from($this->getEntityName(), 'v')
            ->where($qb->expr()->notLike('v.status', ':status'))
            ->setParameter('status', $status);

        return $qb->getQuery()->getResult();
    }

    /**
     * Encontrar visitas no canceladas
     * @param $status
     *
     * @return mixed
     */
    public function findbyStatus($status)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('v')
            ->from($this->getEntityName(), 'v')
            ->where($qb->expr()->in('v.status', ':status'))
            ->setParameter('status', $status);

        return $qb->getQuery()->getResult();
    }
}
