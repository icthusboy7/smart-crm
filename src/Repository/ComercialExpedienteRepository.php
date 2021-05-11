<?php
/**
 * ComercialExpediente Repository
 */
namespace App\Repository;

use App\Entity\ComercialExpediente;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * ComercialExpedienteRepository class
 * @method ComercialExpediente|null find($id, $lockMode = null, $lockVersion = null)
 * @method ComercialExpediente|null findOneBy(array $criteria, array $orderBy = null)
 * @method ComercialExpediente[]    findAll()
 * @method ComercialExpediente[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ComercialExpedienteRepository extends ServiceEntityRepository
{
    /**
     * ComercialExpedienteRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ComercialExpediente::class);
    }

    /**
     * @param string $search
     *
     * @return mixed
     */
    public function findByTitulo(string $search)
    {
        $queryBuild = $this->createQueryBuilder('a')
                           ->select('a.id id', 'a.titulo text')
                           ->where('a.titulo LIKE :titulo')
                           ->setParameter('titulo', '%'.$search.'%');

        return $queryBuild->getQuery()->getResult();
    }

    /**
     * Find pipeline using filters (If filters is not null)
     *
     * @param array  $status
     * @param array  $responsables
     * @param string $name
     * @param array  $zonas
     * @param bool   $reporte
     * @param bool   $calendarMode
     * @param string $fecha
     *
     * @return mixed
     */
    public function findByExpedienteForm(?array $status = [], ?array $responsables = [], ?string $name = null, ?array $zonas = [], ?bool $reporte = false, ?bool $calendarMode = false, ?string $fecha = null)
    {
        $qb      = $this->getEntityManager()->createQueryBuilder();
        $query   = $this->createQueryBuilder('e');
        $reporte = $reporte ? 1 : 0;
        if ($calendarMode) {
            if (strlen($fecha) === 6) {
                $fecha = '0'.$fecha;
            }
            $query->select('e.id', 'e.titulo', 'e.fechaPosibleActivacion', 'e.porcentajeProbabilidad');
            $query->andWhere('e.fechaPosibleActivacion = :fecha')->setParameter('fecha', $fecha);
        }
        $query->andWhere('e.expedientePadre is NULL')
            ->andWhere('e.esDisposicion = 0')
            ->andwhere('e.deletedBy is NULL')
            ->andWhere('e.noReport = :reporte')->setParameter('reporte', $reporte);
        if (!empty($status)) {
            $query->andWhere($qb->expr()->in('e.status', $status));
        }
        if (!empty($zonas)) {
            $query->andWhere($qb->expr()->in('e.oficinaZona', $zonas));
        }
        if (!empty($responsables)) {
            $query->andWhere($qb->expr()->in('e.responsable', $responsables));
        }
        if (!is_null($name)) {
            $query->andWhere('e.titulo LIKE :name')->orWhere('e.observaciones LIKE :name')->orWhere('e.importe LIKE :name')
                ->setParameter('name', '%'.$name.'%');
        }

        if ($calendarMode) {
            return $query->getQuery()->getResult();
        }

        return $query->getQuery();
    }

    /**
     * Count pipeline using filters (If filters is not null)
     *
     * @param array  $status
     * @param array  $responsables
     * @param string $name
     * @param array  $zonas
     *
     * @return int
     *
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function countByExpedienteForm(?array $status = [], ?array $responsables = [], ?string $name = null, ?array $zonas = []): int
    {
        $qb    = $this->getEntityManager()->createQueryBuilder();
        $query = $this->createQueryBuilder('e');
        $query->select('count(e.id)')
            ->andWhere('e.expedientePadre is NULL')
            ->andWhere('e.esDisposicion = 0')
            ->andwhere('e.deletedBy is NULL');
        if (!empty($status)) {
            $query->andWhere($qb->expr()->in('e.status', $status));
        }
        if (!empty($zonas)) {
            $query->andWhere($qb->expr()->in('e.oficinaZona', $zonas));
        }
        if (!empty($responsables)) {
            $query->andWhere($qb->expr()->in('e.responsable', $responsables));
        }
        if (!is_null($name)) {
            $query->andWhere('e.titulo LIKE :name')
                ->setParameter('name', '%'.$name.'%');
        }

        return (int) $query->getQuery()->execute();
    }

    /**
     * @param int $idExpediente
     *
     * @return mixed
     */
    public function findExpedientesPadre(int $idExpediente)
    {
        return $this->createQueryBuilder('e')
            ->where('e.deletedAt IS NULL')
            ->andWhere('e.id = :expID OR e.expedientePadre = :expID')
            ->setParameter(':expID', $idExpediente)
            ->getQuery()
            ->getResult();
    }

    /**
     * Borrar todas las disposiciones de un expediente que no esten en el array
     *
     * @param int   $idExp
     * @param array $dispos
     *
     * @return mixed
     */
    public function findExpedienteClearDispo(int $idExp, array $dispos)
    {
        $qb = $this->createQueryBuilder('d');
        $qb->update()->set('d.expedientePadre', ':nullable')
           ->where('d.expedientePadre = :exp')
           ->andwhere($qb->expr()->notIn('d.id', $dispos))
           ->setParameter('exp', $idExp)
           ->setParameter('nullable', null);

        return $qb->getQuery()->execute();
    }

    /**
     * Contar expedientes perdidos
     *
     * @return mixed
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function countPerdidos()
    {
        $qb = $this->createQueryBuilder('e');
        $qb->select('count(e.id)')
            ->where('e.status = 2')
            ->andWhere('e.expedientePadre is NULL')
            ->andWhere('e.deletedBy is NULL');

        $count = $qb->getQuery()->getSingleScalarResult();

        return (int) $count;
    }

    /**
     * Contar expedientes en curso
     *
     * @return mixed
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function countEnCurso()
    {
        $qb = $this->createQueryBuilder('e');
        $qb->select('count(e.id)')
            ->where('e.status = 1')
            ->andWhere('e.expedientePadre is NULL')
            ->andWhere('e.deletedBy is NULL');

        $count = $qb->getQuery()->getSingleScalarResult();

        return (int) $count;
    }

    /**
     * Contar expedientes activados
     *
     * @return mixed
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function countActivados()
    {
        $qb = $this->createQueryBuilder('e');
        $qb->select('count(e.id)')
            ->where('e.status = 3')
            ->andWhere('e.expedientePadre is NULL')
            ->andWhere('e.deletedBy is NULL');

        $count = $qb->getQuery()->getSingleScalarResult();

        return (int) $count;
    }

    /**
     * @return array
     */
    public function filterForCalendar()
    {
        $qb = $this->createQueryBuilder('e');
        $qb->select('e.titulo');

        return $qb->getQuery()->getArrayResult();
    }

    /**
     * @param string $search
     *
     * @return mixed
     *
     * @throws \Doctrine\ORM\ORMException
     */
    public function filterPipelines(string $search)
    {
        $qb = $this->createQueryBuilder('e');

        return $qb->select('e.id id, e.titulo text')
            ->where('e.status = 1')
            ->andWhere('e.titulo LIKE :search')
            ->setParameter('search', $search.'%')
            ->getQuery()
            ->getResult();
    }
}
