<?php
/**
 * Horizontal Repository
 */
namespace App\Repository;

use App\Entity\Horizontal;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Horizontal Repository class
 * @method Horizontal|null find($id, $lockMode = null, $lockVersion = null)
 * @method Horizontal|null findOneBy(array $criteria, array $orderBy = null)
 * @method Horizontal[]    findAll()
 * @method Horizontal[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HorizontalRepository extends ServiceEntityRepository
{
    /**
     * HorizontalRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Horizontal::class);
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
