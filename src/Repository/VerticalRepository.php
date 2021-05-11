<?php
/**
 * Vertical Repository
 */
namespace App\Repository;

use App\Entity\Vertical;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Vertical Repository class
 * @method Vertical|null find($id, $lockMode = null, $lockVersion = null)
 * @method Vertical|null findOneBy(array $criteria, array $orderBy = null)
 * @method Vertical[]    findAll()
 * @method Vertical[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VerticalRepository extends ServiceEntityRepository
{
    /**
     * VerticalRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Vertical::class);
    }

    /**
     * @param string $search
     *
     * @return mixed
     */
    public function findByName(string $search)
    {
        $queryBuild = $this->createQueryBuilder('a')
            ->select('a.id id', 'a.name text')
            ->where('a.name LIKE :name')
            ->setParameter('name', '%'.$search.'%');

        return $queryBuild->getQuery()->getResult();
    }
}
