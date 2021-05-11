<?php

namespace App\Repository;

use App\Entity\Views\Event;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

/**
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRepository extends ServiceEntityRepository
{
    /**
     * {@inheritDoc}
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }


    /**
     * Obtains an array of calendar events filtered by the given search
     * criteria. Only author_id, status_id, start and end are supported.
     *
     * @param array $filters An array of search filters
     *
     * @return Event[]       An array of calendar events
     */
    public function search(array $filters): array
    {
        $builder = $this->createQueryBuilder('e');

        if (empty($filters['status_id'])) {
            return [];
        }

        if (!empty($filters['start'])) {
            $date = $filters['start'];
            $this->andWhereStartDate($builder, $date);
        }

        if (!empty($filters['end'])) {
            $date = $filters['end'];
            $this->andWhereEndDate($builder, $date);
        }

        if (!empty($filters['author_id'])) {
            $ids = $filters['author_id'];
            $this->andWhereAuthorIn($builder, $ids);
        }

        if (!empty($filters['status_id'])) {
            $ids = $filters['status_id'];
            $this->andWhereStatusIn($builder, $ids);
        }

        return $builder->getQuery()->getResult();
    }


    /**
     * Adds a where clausule to a query builder that filters the
     * results by the provided event end date (lower than).
     *
     * @param QueryBuilder $builder Query builder
     * @param array        $date    A date object
     *
     * @return                      Query builder
     */
    protected function andWhereEndDate($builder, $date): QueryBuilder
    {
        $expression = $builder->expr()->lt('e.end', ':end');
        $builder->setParameter('end', $date);
        $builder->andWhere($expression);

        return $builder;
    }


    /**
     * Adds a where clausule to a query builder that filters the
     * results by the provided event start date (greater or equal).
     *
     * @param QueryBuilder $builder Query builder
     * @param array        $date    A date object
     *
     * @return                      Query builder
     */
    protected function andWhereStartDate($builder, $date): QueryBuilder
    {
        $expression = $builder->expr()->gte('e.start', ':start');
        $builder->setParameter('start', $date);
        $builder->andWhere($expression);

        return $builder;
    }


    /**
     * Adds a where clausule to a query builder that filters the
     * results by the provided author identifiers.
     *
     * @param QueryBuilder $builder Query builder
     * @param array        $ids     List of author identifiers
     *
     * @return                      Query builder
     */
    protected function andWhereAuthorIn($builder, $ids): QueryBuilder
    {
        $expression = $builder->expr()->in('e.author', ':authors');
        $builder->setParameter('authors', $ids);
        $builder->andWhere($expression);

        return $builder;
    }


    /**
     * Adds a where clausule to a query builder that filters the
     * results by the provided status identifiers.
     *
     * @param QueryBuilder $builder Query builder
     * @param array        $ids     List of status identifiers
     *
     * @return                      Query builder
     */
    protected function andWhereStatusIn($builder, $ids): QueryBuilder
    {
        $expression = $builder->expr()->in('e.status', ':statuses');
        $builder->setParameter('statuses', $ids);
        $builder->andWhere($expression);

        return $builder;
    }
}
