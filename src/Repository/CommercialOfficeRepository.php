<?php
/**
 * CommercialOffice Repository
 */
namespace App\Repository;

use App\Entity\CommercialOffice;
use App\Entity\GestorHorizontal;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * CommercialOffice repository class
 * @method CommercialOffice|null find($id, $lockMode = null, $lockVersion = null)
 * @method CommercialOffice|null findOneBy(array $criteria, array $orderBy = null)
 * @method CommercialOffice[]    findAll()
 * @method CommercialOffice[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommercialOfficeRepository extends ServiceEntityRepository
{
    /**
     * CommercialOfficeRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CommercialOffice::class);
    }

    /**
     * Get Responsible of Zone
     *
     * @param string $office
     *
     * @return mixed
     */
    public function findResponsibleZone(string $office)
    {
        $queryBuild = $this->createQueryBuilder('co')
            ->select('gh.Gestor')
            ->leftJoin(GestorHorizontal::class, 'gh', 'WITH', 'co.Horizontal = gh.Horizontal')
            ->where('co.Office LIKE :office')
            ->setParameter('office', $office);

        return $queryBuild->getQuery()->getResult();
    }
}
