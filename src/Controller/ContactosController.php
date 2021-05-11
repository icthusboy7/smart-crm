<?php
/**
 * Controlador de contactos
 */
namespace App\Controller;

/* ENTITIES */
use App\Core\Entity\Contact;
use App\Core\Entity\Vocabs\ContactKind;
use App\Core\Entity\Vocabs\ContactStatus;
use App\Entity\ContactFilters;
use App\Entity\MasterCustomer;
use App\Entity\MasterOffice;
use App\Entity\MasterProvider;
use App\Entity\OficinaContactos;
use App\Utils\Widgets;

use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Matrix\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

// COMPONENTS
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class ContactosController
 */
class ContactosController extends AbstractController
{
    /**
     * Utils widgets
     * @var Widgets
     */
    private $widgets;

    /**
     * Paginador de tablas
     * @var PaginatorInterface
     */
    private $paginator;

    /**
     * Entity manager
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * Translator
     * @var TranslatorInterface
     */
    private $translator;


    /**
     * ContactosController constructor.
     * @param Widgets                $widgets
     * @param PaginatorInterface     $knpPaginator
     * @param EntityManagerInterface $em
     * @param TranslatorInterface    $translator
     */
    public function __construct(Widgets $widgets, PaginatorInterface $knpPaginator, EntityManagerInterface $em, TranslatorInterface $translator)
    {
        $this->widgets    = $widgets;
        $this->paginator  = $knpPaginator;
        $this->em         = $em;
        $this->translator = $translator;
    }

    /**
     * Muestra la lista de contactos filtrando por la query del request, que puede ser nula
     * y paginando los resultados de 10 en 10
     *
     * @param Request $request Based on listar_contactos query, can be null
     *
     * @return Response
     */
    public function index(Request $request): Response
    {
        //SET FILTERS
        $query           = [];
        $limit           = 10;
        $query['buscar'] = null;
        $query['office'] = null;
        if ($request->query->get('contact_find')) {
            $query['buscar'] = $request->query->get('contact_find');
        }
        if ($request->query->get('contact_offices') != null) {
            $query['office'] = $request->query->get('contact_offices');
        }
        // CONTACTS
        $page = is_null($request->query->get('page'))
            ? 1
            : $request->query->get('page');

        $contacts = $this->getDoctrine()->getRepository(Contact::class)->findByContactsProviderClient($query, $page, $limit);
        $filters  = $this->getDoctrine()->getRepository(ContactFilters::class)->findBy(['user' => [$this->getUser(), null]]);

        $paginator  = $this->paginator;
        $pagination = $paginator->paginate(
            $contacts,
            $request->query->getInt('page', 1),
            10
        );
        $pagination->setCustomParameters(['position' => 'centered']);

        return $this->render('area-comercial/listar_contactos.html.twig', [
            'contacts' => $pagination,
            'filters'  => $filters,
        ]);
    }

    /**
     * Crea o actualiza el formulario del modal de contactos
     * @param Request $request
     *
     * @return Response
     */
    public function submitContact(Request $request): Response
    {
        $contactNIF = $request->request->get('contact_nif');

        if (is_null($contactNIF) or '' === $contactNIF) {
            return new Response($this->translator->trans('error.form.contact'));
        }

        $contactRepo = $this->getDoctrine()->getManager()->getRepository(Contact::class);
        $contact     = $contactRepo->findOneBy(['nif' => $contactNIF]);

        try {
            if (is_null($contact)) {
                $contact = $contactRepo->newContact();
            } else {
                $contact->setStatus(ContactStatus::ACTIVE());
            }

            $contact->setName($request->request->get('contact_nombre'));
            $contact->setAddress($request->request->get('contact_direccion'));
            $contact->setNif($request->request->get('contact_nif'));
            $contact->setEmail($request->request->get('contact_email'));
            $contact->setPhone($request->request->get('contact_telefono'));
            $contact->setNotes($request->request->get('contact_observaciones'));

            $this->em->persist($contact);
            $this->em->flush();
        } catch (\Exception $e) {
            new Response($this->translator->trans('error.form.contact'));
        }

        return new Response();
    }

    /**
     * Delete a contact for contacts view
     *
     * @param Request $request
     *
     * @return Response
     *
     */
    public function deleteContacto(Request $request): Response
    {
        $contact = $this->getDoctrine()->getRepository(Contact::class)
            ->findOneBy(['id' => $request->query->get('id')]);
        try {
            if ($contact->getKind()->getName() === ContactKind::TEMPORARY()->getName()) {
                $this->em->remove($contact);
            } else {
                if ($contact->getKind()->getName() === ContactKind::SUPPLIER()->getName()) {
                    $supplier = $this->getDoctrine()->getRepository(MasterProvider::class)
                        ->findOneBy(['nif' => $contact->getNif()]);
                    $contact->setName($supplier->getNombre());
                } else {
                    $customer = $this->getDoctrine()->getRepository(MasterCustomer::class)
                        ->findOneBy(['nif' => $contact->getNif()]);
                    $contact->setName($customer->getNombre());
                }
                $contact->setStatus(ContactStatus::INACTIVE());
                $contact->removeInformation();
                $contact->setUpdatedAt(new \DateTime('now'));
                $this->em->persist($contact);
            }
            $this->em->flush();

            return new Response();
        } catch (\Exception $e) {
            return new Response();
        }
    }

    /**
     * Create a custom query for contacts view getting URL parameters
     * @param Request $request
     *
     * @return Response
     */
    public function createQuery(Request $request): Response
    {
        $user          = $this->getUser();
        $entityManager = $this->getDoctrine()->getManager();
        $filter        = new ContactFilters();
        if (get_class($user) === 'App\Entity\Admin') {
            $filter->setAdmin($this->getUser());
        } else {
            $filter->setUser($this->getUser());
        }

        $filter->setName($request->request->get('query_name'));

        $query = '?';
        if ($request->request->get('contact_find')) {
            $query .= 'contact_find='.$request->request->get('contact_find').'&';
        }

        if ($request->request->get('contact_offices') != null) {
            foreach ($request->request->get('contact_offices') as $office) {
                $query .= 'contact_offices%5B%5D='.$office.'&';
            }
        }
        $filter->setQuery($query);

        try {
            $entityManager->persist($filter);
            $entityManager->flush();

            return new Response();
        } catch (Exception $e) {
            return new Response($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Delete a custom query for contacts view
     *
     * @param Request $request
     *
     * @return Response
     *
     */
    public function deleteQueryContactos(Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $query         = $this->getDoctrine()->getRepository(ContactFilters::class)
            ->findOneBy(['id' => $request->query->get('id')]);
        try {
            $entityManager->remove($query);
            $entityManager->flush();

            return new Response();
        } catch (\Exception $e) {
            return new Response($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Encontrar las oficinas de un contacto para llenar select2
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function getContactosOficinas(Request $request): JsonResponse
    {
        $officeSelected = [];
        $relations      = $this->getDoctrine()->getRepository(OficinaContactos::class)
            ->findBy(['nifContacto' => $request->request->get('id')]);
        foreach ($relations as $rel) {
            $fulloffice = $this->getDoctrine()->getRepository(MasterOffice::class)
                ->findOneBy(['codigo' => $rel->getCodigoOficina()]);
            $office     = ['id' => $fulloffice->getCodigo(), 'text' => $fulloffice->getNombre()];
            array_push($officeSelected, $office);
        }

        return new JsonResponse($officeSelected);
    }

    /**
     * Obtener informacion de contacto
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function getContactoInfo(Request $request): JsonResponse
    {
        $contact     = $this->getDoctrine()->getRepository(Contact::class)
            ->findOneBy(['id' => $request->request->get('id')]);
        $contactInfo = [
            'contact_nif'           => $contact->getNif(),
            'contact_nombre'        => $contact->getName(),
            'contact_direccion'     => $contact->getAddress(),
            'contact_email'         => $contact->getEmail(),
            'contact_telefono'      => $contact->getPhone(),
            'type_contact'          => $contact->getKind()->getId(),
            'contact_observaciones' => $contact->getNotes(),
        ];

        return new JsonResponse($contactInfo);
    }

    /**
     * Find offices for select2
     *
     * @param Request $request
     *
     * @return JsonResponse|Response
     *
     */
    public function selectOffices(Request $request)
    {
        $query = $request->query->get('q');
        if (is_null($query)) {
            $this->getDoctrine()->getManager();
            $result = $this->getDoctrine()->getRepository(MasterOffice::class)
                ->findOfficesSelect($query);

            return new JsonResponse($result);
        }

        return new Response('Not Found', 404);
    }

    /**
     * Find contact by NIF for select2
     *
     * @param Request $request
     *
     * @return JsonResponse|Response
     *
     */
    public function findContact(Request $request)
    {
        $query = $request->query->get('q');

        if (!is_null($query)) {
            $contacts = $this->getDoctrine()->getRepository(Contact::class)
                ->findContactsSelect($query);

            return new JsonResponse($contacts);
        }

        return new Response('Not Found', 404);
    }

    /**
     * Verificar que contacto existe segun NIF
     *
     * @param Request $request
     *
     * @return JsonResponse
     *
     */
    public function existContact(Request $request): JsonResponse
    {
        $contactId = $request->request->get('contact_id');
        $nif       = $request->request->get('contact_nif');

        if (empty($contactId)) {
            if (!is_null($nif)) {
                $contact = $this->getDoctrine()->getRepository(Contact::class)
                    ->findOneBy(['nif' => $nif]);
                if (isset($contact)) {
                    return new JsonResponse($this->translator->trans('Number document already in use'));
                }
            }
        }

        return new JsonResponse('true');
    }
}
