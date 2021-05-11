<?php
/**
 * Alertas Repository
 */
namespace App\Repository;

use App\Entity\ComercialAlertas;
use App\Entity\ComercialExpediente;

use App\Entity\Horizontal;
use App\Entity\Vertical;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * AlertasRepository Class
 * @method ComercialAlertas|null find($id, $lockMode = null, $lockVersion = null)
 * @method ComercialAlertas|null findOneBy(array $criteria, array $orderBy = null)
 * @method ComercialAlertas[]    findAll()
 * @method ComercialAlertas[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ComercialAlertasRepository extends ServiceEntityRepository
{
    /**
     * AlertasRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ComercialAlertas::class);
    }

    /**
     * Return view table with relations
     * @param array $query
     * @param int   $active
     *
     * @return mixed
     */
    public function findTableView(array $query, int $active)
    {
        $queryBuild = $this->createQueryBuilder('a')
            ->leftJoin(ComercialExpediente::class, 'ce', 'WITH', 'a.expediente = ce.id')
            ->leftJoin(Horizontal::class, 'h', 'WITH', 'a.horizontal = h.id')
            ->leftJoin(Vertical::class, 'v', 'WITH', 'a.vertical = v.id')
            ->orderBy('a.updatedAt', 'DESC');

        if (isset($query['buscar']) && '' !== $query['buscar']) {
            $queryBuild->where('ce.titulo LIKE :titulo')->setParameter('titulo', '%'.$query['buscar'].'%');
            $queryBuild->orWhere('a.missatge LIKE :missatge')->setParameter('missatge', '%'.$query['buscar'].'%');
            $queryBuild->orwhere('a.personaNif LIKE :nif')->setParameter('nif', '%'.$query['buscar'].'%');
            $queryBuild->orwhere('h.name LIKE :horizontal')->setParameter('horizontal', '%'.$query['buscar'].'%');
            $queryBuild->orwhere('v.name LIKE :vertical')->setParameter('vertical', '%'.$query['buscar'].'%');
            $queryBuild->orwhere('a.oficina LIKE :oficina')->setParameter('oficina', '%'.$query['buscar'].'%');
        }

        $queryBuild->andWhere('a.deleted = :deleted')->setParameter('deleted', 0);

        return $queryBuild->getQuery()->getResult();
    }

    /**
     * Return data alerts filtered by Expediente and Cotizacion
     *
     * @param array  $query
     * @param string $typeAlert
     *
     * @return array
     */
    public function findAlertsBy(array $query, string $typeAlert = 'ALERT'): array
    {
        $queryBuild = $this->createQueryBuilder('a');

        if (0 !== $query['idExpediente']) {
            $queryBuild->where('a.expediente = :expediente')
                ->setParameter('expediente', $query['idExpediente']);
        }

        if (0 !== $query['idCotizacion']) {
             $queryBuild->andWhere('a.cotizacion = :cotizacion')
                 ->setParameter('cotizacion', $query['idCotizacion']);
        }

        if ('ALERT' === $typeAlert) {
            $queryBuild->andWhere('a.isAlert = true');
        } else {
            $queryBuild->andWhere('a.isAlert = false');
        }

        $queryBuild->andwhere('a.active = :active')->setParameter('active', 1);
        $queryBuild->andWhere('a.deleted = :deleted')->setParameter('deleted', 0);

        $queryBuild->orderBy('a.nivel', 'DESC');

        return $queryBuild->getQuery()->getResult();
    }

    /**
     * Retorna un array con las alertas relacionadas con la columna solicitada.
     *
     * @param string      $field
     * @param mixed       $data
     * @param string|null $type
     *
     * @return array
     */
    public function findAlerts(string $field, $data, ?string $type = null): array
    {
        $query = $this->createQueryBuilder('alertas');
        if (is_array($data)) {
            $query->where("alertas.{$field} IN (:data)");
            $query->setParameter(':data', $data);
        } elseif('oficina' === $field && !is_null($data)) {
            $dt = explode( ' - ',$data->getDt());
            $dg = explode( ' - ',$data->getDg());
            $dan = explode( ' - ',$data->getDan());
            $query->Where("alertas.{$field} = :office");
            $query->orWhere("alertas.{$field} = :dt");
            $query->orWhere("alertas.{$field} = :dg");
            $query->orWhere("alertas.{$field} = :dan");
            $query->setParameter(':office', $data->getCodigo());
            $query->setParameter(':dt', $dt[0]);
            $query->setParameter(':dg', $dg[0]);
            $query->setParameter(':dan', $dan[0]);
        } else {
            $query->where("alertas.{$field} = :data");
            $query->setParameter(':data', $data);
        }


        if (!is_null($type)) {
            $query->andWhere('alertas.isAlert = :alert')->setParameter(':alert', ('ALERT' === $type) ? true : false);
        }
        $query->andwhere('alertas.active = :active')->setParameter('active', 1);
        $query->andWhere('alertas.deleted = :deleted')->setParameter('deleted', 0);

        return $query->getQuery()->getResult();
    }
}
