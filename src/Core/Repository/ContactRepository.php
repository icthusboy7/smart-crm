<?php
/**
 * Contact Repository
 */
namespace App\Core\Repository;

use App\Core\Entity\Contact;
use App\Core\Entity\Vocabs\ContactKind;
use App\Core\Entity\Vocabs\ContactStatus;
use App\Entity\Concerns\IsEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\DBAL\ParameterType;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Contact repository class
 * @method Contact|null find($id, $lockMode = null, $lockVersion = null)
 * @method Contact|null findOneBy(array $criteria, array $orderBy = null)
 * @method Contact[]    findAll()
 * @method Contact[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContactRepository extends ServiceEntityRepository
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * ContactRepository constructor.
     * @param RegistryInterface   $registry
     * @param TranslatorInterface $translator
     */
    public function __construct(RegistryInterface $registry, TranslatorInterface $translator)
    {
        parent::__construct($registry, Contact::class);
        $this->translator = $translator;
    }


    /**
     * Instantiates a new contact. By default the contact is set as
     * active and its type as ContactKind::TEMPORARY().
     *
     * @return New contact instance
     */
    public function newContact(): Contact
    {
        $contact = new Contact();

        $em     = $this->getEntityManager();
        $kind   = ContactKind::TEMPORARY();
        $status = ContactStatus::ACTIVE();

        $contact->setStatus($status);
        $contact->setKind($kind->reference($em));

        return $contact;
    }


    /**
     * UNION AND PAGINATION TO CONTACT VIEW (CONTACT + CUSTOMERS + PROVIDERS)
     * @param array|null $value
     *
     * @return \Doctrine\ORM\Query
     */
    public function findByContactsProviderClient(?array $value): Query
    {
        $em     = $this->getEntityManager();
        $buscar = $value['buscar'];
        $office = $value['office'];

        $query = (isset($buscar) && isset($office) ? $this->findContactByAll($buscar, $office) : $em->createQuery('SELECT p from App\Core\Entity\Contact p'));

        if (isset($buscar) && !isset($office)) {
            $query = $this->findContactByData($buscar);
        }

        if (isset($office) && !isset($buscar)) {
            $query = $this->findContactByOffice($office);
        }

        return $query;
    }

    /**
     * @param array|null $office
     *
     * @return Query
     */
    public function findContactByOffice(?array $office): Query
    {
        $em    = $this->getEntityManager();
        $query = $em->createQuery('
                    SELECT p
                    FROM App\Core\Entity\Contact p');

        return $query;
    }

    /**
     * @param string|null $value
     *
     * @return \Doctrine\ORM\Query
     */
    public function findContactByData(?string $value): Query
    {
        $em    = $this->getEntityManager();
        $query = $em->createQuery('
                    SELECT p
                    FROM App\Core\Entity\Contact p
                    WHERE p.nif LIKE :find
                    or p.name LIKE :find
                    or p.phone LIKE :find
                    or p.address LIKE :find
                    or p.email LIKE :find');
        $query->setParameter('find', '%'.$value.'%');

        return $query;
    }

    /**
     * @param string|null $value
     * @param array|null  $office
     *
     * @return \Doctrine\ORM\Query
     */
    public function findContactByAll(?string $value, ?array $office): Query
    {
        $em    = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT p
            FROM App\Core\Entity\Contact p
            WHERE (p.nif LIKE :find
            or p.name LIKE :find
            or p.phone LIKE :find
            or p.address LIKE :find
            or p.email LIKE :find)'
        );
        $query->setParameter('find', '%'.$value.'%');

        return $query;
    }

    /**
     * Return Contacts for select options finding %"nombre"%
     *
     * @param string $value
     * @param int    $limit
     *
     * @return mixed
     */
    public function findContactsSelect(string $value, ?int $limit = 1)
    {
        $type = ' - '.$this->translator->trans('Co');

        $query = $this->createQueryBuilder('c')
            ->select('c.nif id, CONCAT(\'[\',c.nif,\'] \', c.name, :type) text');

        if (1 === $limit) {
            $query->andWhere('c.nif = :val')->setParameter('val', $value);
        } else {
            $query->andWhere('c.nif LIKE :val or c.name LIKE :val')
                ->setParameter('val', '%'.$value.'%');
        }

        $query->setParameter('type', ''.$type.'')
            ->orderBy('c.nif', 'ASC')
            ->setMaxResults($limit);

        return $query->getQuery()->getResult();
    }

    /**
     * Find only providers
     *
     * @param string   $value NIF to find
     * @param int|null $limit Number of returns
     *
     * @return mixed
     */
    public function findProvider(string $value, ?int $limit = 1)
    {
        $kind = [ContactKind::SUPPLIER(), ContactKind::CUSTOMER_SUPPLIER()];

        $type = ' - '.$this->translator->trans('P');

        $query = $this->createQueryBuilder('p')
            ->select('p.nif id, CONCAT(\'[\',p.nif,\'] \', p.name, :type) text');

        if (1 === $limit) {
            $query->andWhere('p.nif = :val')
                ->setParameter('val', $value);
        } else {
            $query->andWhere('p.nif LIKE :val or p.name LIKE :val')
                ->setParameter('val', '%'.$value.'%');
        }
        $query->andWhere('p.kind in (:kind)')
            ->setParameter('kind', $kind)
            ->setParameter('type', $type)
            ->orderBy('p.nif', 'ASC')
            ->setMaxResults($limit);



        return $query->getQuery()->getResult();
    }

    /**
     * @param string   $value NIF to find
     * @param int|null $limit Number of return
     *
     * @return mixed
     */
    public function findCustomer(string $value, ?int $limit = 1)
    {
        $kind = [ContactKind::CUSTOMER(), ContactKind::CUSTOMER_SUPPLIER()];

        $query = $this->createQueryBuilder('p')
            ->select('p.nif id, CONCAT(\'[\',p.nif,\'] \', p.name) text');

        if (1 === $limit) {
            $query->andWhere('p.nif = :val')
                ->setParameter('val', $value);
        } else {
            $query->andWhere('p.nif LIKE :val or p.name LIKE :val')
                ->setParameter('val', '%'.$value.'%');
        }
        $query->andWhere('p.kind in (:kind)')
            ->setParameter('kind', $kind)
            ->orderBy('p.nif', 'ASC')
            ->setMaxResults($limit);

        return $query->getQuery()->getResult();
    }

    /**
     * @param string   $value
     * @param int|null $limit
     *
     * @return mixed
     */
    public function allContactsSelect2(string $value, ?int $limit = 1)
    {
        $query = $this->createQueryBuilder('p')
            ->select('p.nif id, CONCAT(\'[\',p.nif,\'] \', p.name) text');
        if (1 === $limit) {
            $query->andWhere('p.nif = :val')->setParameter('val', $value);
        } else {
            $query->andWhere('p.nif LIKE :val or p.name LIKE :val')
                ->setParameter('val', '%'.$value.'%');
        }
        $query->orderBy('p.nif', 'ASC')
            ->setMaxResults($limit);

        return $query->getQuery()->getResult();
    }
}
