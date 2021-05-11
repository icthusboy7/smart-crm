<?php

namespace App\Repository;

use App\Entity\ComercialExpediente;
use App\Entity\ComercialExpedienteCotizaciones;
use App\Entity\ComercialMuro;
use App\Entity\ComercialTask;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ComercialMuro|null find($id, $lockMode = null, $lockVersion = null)
 * @method ComercialMuro|null findOneBy(array $criteria, array $orderBy = null)
 * @method ComercialMuro[]    findAll()
 * @method ComercialMuro[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ComercialMuroRepository extends ServiceEntityRepository
{
    /**
     * ComercialMuroRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ComercialMuro::class);
    }

    /**
     * @param int $taskId
     *
     * @return mixed
     */
    public function findByTask(int $taskId)
    {
        return $this->createQueryBuilder('a')
            ->leftJoin(ComercialTask::class, 'ct', 'WITH', 'a.id = ct.comercialMuro')
            ->where('ct.id = :taskId')
            ->setParameter('taskId', $taskId)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param int    $wallId
     * @param int    $author
     * @param string $where
     * @param array  $params
     *
     * @return mixed
     */
    public function findUsersNotification(int $wallId, int $author, string $where, array $params)
    {
        return $this->createQueryBuilder('m')
            ->where($where)
            ->andWhere('m.autor != :autor')
            ->setParameter($params['key'], $params['value'])
            ->setParameter(':autor', $author)
            ->addGroupBy('m.autor')
            ->getQuery()
            ->getResult();
    }

    /**
     * @param int   $pipeline
     * @param array $quotes
     *
     * @return mixed
     */
    public function findByJoinQuotes(int $pipeline, array $quotes)
    {

        return $this->createQueryBuilder('m')
            ->leftJoin(ComercialExpediente::class, 'ce', 'WITH', 'm.expediente = ce.id')
            ->where('m.expediente = :pipeline')
            ->orwhere('ce.expedientePadre = :pipeline')
            ->orWhere('m.cotizacion IN (:quotes)')
            ->setParameter(':pipeline', $pipeline)
            ->setParameter(':quotes', $quotes)
            ->orderBy('m.createdAt', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @param array $pipelines
     * @param array $orderBy
     *
     * @return mixed
     */
    public function findByPipeLinesAndProvision(array $pipelines, array $orderBy)
    {
        return (!isset($pipelines['cotizacion']))
            ? $this->createQueryBuilder('cm')
                ->leftJoin(ComercialExpediente::class, 'ce', 'WITH', 'cm.expediente = ce.id')
                ->where('ce.id = :pipeline')
                ->orwhere('ce.expedientePadre = :pipeline')
                ->setParameter(':pipeline', $pipelines['expediente'])
                ->orderBy('cm.createdAt', $orderBy['createdAt'])
                ->getQuery()
                ->getResult()
            : $this->createQueryBuilder('m')
                ->leftJoin(ComercialExpediente::class, 'ce', 'WITH', 'm.expediente = ce.id')
                ->Where('m.cotizacion = :quotes')
                ->setParameter(':quotes', $pipelines['cotizacion'])
                ->orderBy('m.createdAt', 'ASC')
                ->getQuery()
                ->getResult();
    }
}
