<?php
/**
 * DocumentType Repository
 */
namespace App\Repository;

use App\Entity\DocumentType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * DocumentType Repository class
 * @method DocumentType|null find($id, $lockMode = null, $lockVersion = null)
 * @method DocumentType|null findOneBy(array $criteria, array $orderBy = null)
 * @method DocumentType[]    findAll()
 * @method DocumentType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DocumentTypeRepository extends ServiceEntityRepository
{
    /**
     * DocumentTypeRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, DocumentType::class);
    }
}
