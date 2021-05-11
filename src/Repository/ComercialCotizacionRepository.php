<?php

/**
 * ComercialCotizacion Repository
 */
namespace App\Repository;

use App\Entity\ComercialCotizacion;
use App\Entity\ComercialExpedienteCotizaciones;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * ComercialCotizacion repository class
 * @method ComercialCotizacion|null find($id, $lockMode = null, $lockVersion = null)
 * @method ComercialCotizacion|null findOneBy(array $criteria, array $orderBy = null)
 * @method ComercialCotizacion[]    findAll()
 * @method ComercialCotizacion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ComercialCotizacionRepository extends ServiceEntityRepository
{
    /**
     * ComercialCotizacionRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ComercialCotizacion::class);
    }

    /**
     * @param string $search
     *
     * @return mixed
     */
    public function filterQuotes(string $search)
    {
        $qb = $this->createQueryBuilder('e');

        return $qb->select('e.numCoti id, CONCAT(\'Número de Cotización [\',e.numCoti,\'] - \', e.solicitanteNombre)  text')
            ->Where('e.numCoti LIKE :search')
            ->orWhere('e.solicitanteNombre LIKE :search')
            ->orWhere('e.solicitanteNif LIKE :search')
            ->setParameter('search', $search.'%')
            ->getQuery()
            ->getResult();
    }
}
