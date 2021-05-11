<?php

namespace App\Repository;

use App\Entity\MasterProvider;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @method MasterProvider|null find($id, $lockMode = null, $lockVersion = null)
 * @method MasterProvider|null findOneBy(array $criteria, array $orderBy = null)
 * @method MasterProvider[]    findAll()
 * @method MasterProvider[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MasterProviderRepository extends ServiceEntityRepository
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * MasterProviderRepository constructor.
     * @param RegistryInterface   $registry
     * @param TranslatorInterface $translator
     */
    public function __construct(RegistryInterface $registry, TranslatorInterface $translator)
    {
        parent::__construct($registry, MasterProvider::class);
        $this->translator = $translator;
    }

    /**
     * Return Providers for select options finding %"Provider"%
     * @param string $value
     *
     * @return mixed
     */
    public function findProvidersSelect(string $value)
    {
        $type = ' - '.$this->translator->trans('Pr');

        return $this->createQueryBuilder('p')
            ->select('p.nif AS id, CONCAT(\'[\',p.nif,\'] \', p.nombre, :type) AS text')
            ->andWhere('p.nif LIKE :val OR p.nombre LIKE :val')
            ->setParameter('val', '%'.$value.'%')
            ->setParameter('type', ''.$type.'')
            ->orderBy('p.nif', 'ASC')
            ->setMaxResults(50)
            ->getQuery()
            ->getResult();
    }
}
