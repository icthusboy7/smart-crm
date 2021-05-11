<?php
/**
 * Document Repository
 */
namespace App\Repository;

use App\Entity\Document;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Document Repository class
 * @method Document|null find($id, $lockMode = null, $lockVersion = null)
 * @method Document|null findOneBy(array $criteria, array $orderBy = null)
 * @method Document[]    findAll()
 * @method Document[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DocumentRepository extends ServiceEntityRepository
{
    /**
     * DocumentRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Document::class);
    }

    /**
     * Encontrar documentos con path
     *
     * @return mixed
     */
    public function findPathNoNull()
    {
        return $this->createQueryBuilder('d')
            ->where('d.path IS NOT NULL')
            ->getQuery()
            ->getResult();
    }
}
