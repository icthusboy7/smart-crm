<?php

namespace App\Repository;

use App\Entity\ComercialCotizacion;
use App\Entity\ComercialExpediente;
use App\Entity\ComercialExpedienteCotizaciones;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ComercialExpedienteCotizaciones|null find($id, $lockMode = null, $lockVersion = null)
 * @method ComercialExpedienteCotizaciones|null findOneBy(array $criteria, array $orderBy = null)
 * @method ComercialExpedienteCotizaciones[]    findAll()
 * @method ComercialExpedienteCotizaciones[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ComercialExpedienteCotizacionesRepository extends ServiceEntityRepository
{
    /**
     * ComercialExpedienteCotizacionesRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ComercialExpedienteCotizaciones::class);
    }

    /**
     * @param int|null $idExpediente
     *
     * @return mixed
     */
    public function getCotizacionesExpediente(?int $idExpediente)
    {
        return $this->createQueryBuilder('ce')
            ->select('ce.cotizacion', 'cc.numCoti')
            ->leftJoin(ComercialCotizacion::class, 'cc', 'WITH', 'ce.cotizacion = cc.id')
            ->where('ce.expedienteID = :idExpediente')
            ->andWhere('ce.cotizacion IS NOT NULL')
            ->setParameter('idExpediente', $idExpediente)
            ->orderBy('ce.cotizacion', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Borrar todas las cotizaciones de un expediente que no esten en el array
     *
     * @param int   $idExp
     * @param array $cotis
     *
     * @return mixed
     */
    public function findExpedienteClearCotis(int $idExp, array $cotis)
    {
        $qb = $this->createQueryBuilder('c');
        $qb->delete(ComercialExpedienteCotizaciones::class, 'c')
            ->where('c.expedienteID = :exp')
            ->andwhere($qb->expr()->notIn('c.cotizacion', $cotis))
            ->setParameter('exp', $idExp);

        return $qb->getQuery()->execute();
    }

    /**
     * @param int $idPadre
     * @param int $idHijo
     *
     * @return mixed
     */
    public function updateJoinExpedientes(int $idPadre, int $idHijo)
    {
        $qb = $this->createQueryBuilder('c');
        $qb->update()->set('c.expedienteID', $idPadre)
            ->where('c.expedienteID = :idHijo')
            ->setParameter('idHijo', $idHijo);

        return $qb->getQuery()->execute();
    }

    // /**
    //  * @return ComercialExpedienteCotizaciones[] Returns an array of ComercialExpedienteCotizaciones objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /**
     * Get All quote assigned with expedient
     *
     * @return mixed
     */
    public function findQuoteWithExpedient()
    {
        return $this->createQueryBuilder('c')
            ->select('e.id', 'c.cotizacion', 'o.fechaEstado', 'c.createdAt', 'o.autor', 'o.idTarea', 'o.fechaTarea')
            ->leftJoin(ComercialCotizacion::class, 'o', 'WITH', 'c.cotizacion = o.numCoti')
            ->leftJoin(ComercialExpediente::class, 'e', 'WITH', 'c.expedienteID = e.id')
            ->getQuery()
            ->getResult();
    }
}
