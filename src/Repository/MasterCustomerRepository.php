<?php

namespace App\Repository;

use App\Entity\MasterCustomer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @method MasterCustomer|null find($id, $lockMode = null, $lockVersion = null)
 * @method MasterCustomer|null findOneBy(array $criteria, array $orderBy = null)
 * @method MasterCustomer[]    findAll()
 * @method MasterCustomer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MasterCustomerRepository extends ServiceEntityRepository
{

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * MasterCustomerRepository constructor.
     * @param RegistryInterface   $registry
     * @param TranslatorInterface $translator
     */
    public function __construct(RegistryInterface $registry, TranslatorInterface $translator)
    {
        parent::__construct($registry, MasterCustomer::class);
        $this->translator = $translator;
    }

    /**
     * Return Customers for select options finding %"Cliente"%
     * @param string $value
     *
     * @return mixed
     */
    public function findCustomersSelect(string $value)
    {
        $type = ' - '.$this->translator->trans('Cu');

        return $this->createQueryBuilder('c')
            ->select('c.nif AS id, CONCAT(\'[\',c.nif,\'] \', c.nombre, :type) AS text')
            ->andWhere('c.nif LIKE :val OR c.nombre LIKE :val')
            ->setParameter('val', '%'.$value.'%')
            ->setParameter('type', ''.$type.'')
            ->orderBy('c.nif', 'ASC')
            ->distinct()
            ->setMaxResults(50)
            ->getQuery()
            ->getResult();
    }
}
