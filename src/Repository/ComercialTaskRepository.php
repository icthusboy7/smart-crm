<?php

namespace App\Repository;

use App\Entity\ComercialTask;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ComercialTask|null find($id, $lockMode = null, $lockVersion = null)
 * @method ComercialTask|null findOneBy(array $criteria, array $orderBy = null)
 * @method ComercialTask[]    findAll()
 * @method ComercialTask[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ComercialTaskRepository extends ServiceEntityRepository
{
    /**
     * ComercialTaskRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ComercialTask::class);
    }

    /**
     * @param array|null $params
     *
     * @return array|QueryBuilder
     */
    public function findAllWithExpedientes(?array $params = null)
    {
        $query = $this->createQueryBuilder('task')
            ->addSelect('muro')
            ->addSelect('expediente')
            ->leftjoin('task.comercialMuro', 'muro')
            ->leftjoin('muro.expediente', 'expediente')
            ->addGroupBy('task')
        ;

        if (isset($params['idUsuario'])) {
            if ($params['idUsuario'] === 'NULL') {
                $query->andWhere(
                    $query->expr()->isNull('task.responsible')
                );
            } else {
                $query->add('where', $query->expr()->in('task.responsible', $params['idUsuario']));
            }
        }

        if (isset($params['zonas'])) {
            $query->add('where', $query->expr()->in('expediente.oficinaZona', $params['zonas']));
        }

        if (isset($params['status'])) {
            $query->add('where', $query->expr()->in('task.status', $params['status']));
        } else {
            $query->andWhere('task.status != 5')
                ->andWhere('task.status != 3');
        }

        if (isset($params['type'])) {
            $query->add('where', $query->expr()->in('task.type', $params['type']));
        }

        if (isset($params['desc'])) {
            $query->andWhere('task.description LIKE :desc')
                ->setParameter('desc', '%'.$params['desc'].'%');
        }

        if (isset($params['limit'])) {
            $query->setMaxResults($params['limit']);
        }

        return $query->getQuery()->getResult();
    }

    /**
     * @param string    $zona
     * @param bool|null $sinResponsable
     *
     * @return array|QueryBuilder
     */
    public function getTareas(string $zona, ?bool $sinResponsable = false)
    {
        $params = ['zonas' => [$zona]];
        if ($sinResponsable) {
            $params['idUsuario'] = 'NULL';
        }

        return $this->findAllWithExpedientes($params);
    }

    /**
     * @param string|null $zona
     * @param bool        $sinResponsable
     *
     * @return int
     *
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function countTareas(?string $zona = null, ?bool $sinResponsable = false): int
    {
        $query = $this->createQueryBuilder('task')
            ->select('count(task.id)')
            ->leftjoin('task.comercialMuro', 'muro')
            ->leftjoin('muro.expediente', 'expediente')
            ->andWhere('task.status != 5')
            ->andWhere('task.status != 3')
        ;
        if (!is_null($zona)) {
            $query->andWhere('expediente.oficinaZona = :zona')
                ->setParameter('zona', $zona);
        }

        if ($sinResponsable) {
            $query->andWhere($query->expr()->isNull('task.responsible'));
        }

        return (int) $query->getQuery()->getSingleScalarResult();
    }

    /**
     * @param int $userId
     *
     * @return int
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function countUserTareas(int $userId): int
    {
        $query = $this->createQueryBuilder('task')
            ->select('count(task.id)')
            ->leftjoin('task.comercialMuro', 'muro')
            ->leftjoin('muro.expediente', 'expediente')
            ->andWhere('task.status != 5')
            ->andWhere('task.status != 3')
        ;

        $query->andWhere('task.responsible = :user')
            ->setParameter('user', $userId);


        return (int) $query->getQuery()->getSingleScalarResult();
    }

    // /**
    //  * @return ComercialTask[] Returns an array of ComercialTask objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ComercialTask
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
