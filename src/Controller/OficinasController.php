<?php
/**
 * Controlador de oficinas
 */
namespace App\Controller;

/* ENTITIES */

use App\Entity\CommercialOffice;
use App\Entity\ContactFilters;
use App\Entity\DashboardWidgets;
use App\Entity\MasterOffice;
use App\Entity\OfficeFilters;
use App\Entity\WidgetUserOrden;
use App\Entity\Groups;
use App\Entity\NotificationsTo;
use App\Entity\User;
use App\Entity\Notifications;
use App\Core\Entity\Contact;
use Doctrine\ORM\EntityManagerInterface;

use Knp\Component\Pager\PaginatorInterface;
use Matrix\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Utils\Widgets;

use Symfony\Component\Security\Core\Role\Role;
use Symfony\Component\Security\Core\Role\RoleHierarchy;

// COMPONENTS
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class OficinasController
 * @package App\Controller
 */
class OficinasController extends AbstractController
{
    /**
     * Utils widget
     * @var Widgets
     */
    private $widgets;

    /**
     * Paginador para tablas
     * @var PaginatorInterface
     */
    private $paginator;

    /**
     * Entity manager
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * OficinasController constructor.
     * @param Widgets $widgets
     * @param PaginatorInterface $knpPaginator
     * @param EntityManagerInterface $em
     */
    public function __construct(Widgets $widgets, PaginatorInterface $knpPaginator, EntityManagerInterface $em)
    {
        $this->widgets = $widgets;
        $this->paginator = $knpPaginator;
        $this->em = $em;
    }

    /**
     * Muestra la lista de oficinas filtrando por la query del request, que puede ser nula,
     * y paginando los resultados de 10 en 10
     * @param Request $request Based on listar_oficinas query, can be null
     * @return Response
     */
    public function index(Request $request)
    {
        $user = $this->getUser();
        //SET FILTERS
        $query = array();
        if ($request->query->get('office_find')) {
            $query['office_find'] = $request->query->get('office_find');
        }
        //APPLY FILTERS
        $offices = $this->getDoctrine()->getRepository(MasterOffice::class)->findByOfficeForm($query);

        if (get_class($user) === 'App\Entity\Admin') {
            $filters = $this->getDoctrine()->getRepository(OfficeFilters::class)->findBy(array('admin' => array($this->getUser())));
        } else {
            $filters = $this->getDoctrine()->getRepository(OfficeFilters::class)->findBy(array('user' => array($this->getUser())));
        }
        $paginator  = $this->paginator;
        $pagination = $paginator->paginate(
            $offices,
            $request->query->getInt('page', 1),
            10
        );
        $pagination->setCustomParameters(['position' => 'centered']);
        // END CONTACTS

        return $this->render('area-comercial/listar_oficinas.html.twig', ['offices' => $pagination, 'filters' => $filters]) ;
    }

    /**
     * Create a custom query for office view getting URL parameters
     * @param Request $request
     * @return Response
     */
    public function createQuery(Request $request)
    {
        $user = $this->getUser();
        $entityManager = $this->getDoctrine()->getManager();
        $filter = new OfficeFilters();

        if (get_class($user) === 'App\Entity\Admin') {
            $filter->setAdmin($this->getUser());
        } else {
            $filter->setUser($this->getUser());
        }
        $filter->setName($request->request->get('query_name'));

        $query = '?';
        if ($request->request->get('office_find')) {
            $query .= 'office_find='.$request->request->get('office_find').'&';
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
     * @param Request $request
     * @return Response
     *
     */
    public function deleteQueryOficina(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $query_deleted = $this->getDoctrine()->getRepository(OfficeFilters::class)->findOneBy(array('id' => $request->query->get('id')));
        try {
            $entityManager->remove($query_deleted);
            $entityManager->flush();
            return new Response();
        } catch (Exception $e) {
            return new Response($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Recibe el ID de una oficina mediante Request y devuelve la información de una oficina formateada para
     * el modal de frontend
     * @param Request $request
     * @param TranslatorInterface $translator
     * @return JsonResponse|Response
     */
    public function getFullOffice(Request $request, TranslatorInterface $translator)
    {

        try {
            $office = $this->getDoctrine()->getRepository(MasterOffice::class)->findOneBy(array('codigo' => $request->request->get('id')));

            /*
             * Construccion del modal de información de oficina
             */

            /* INFORMACIÓN GENERAL */
            $output_zona_info_general = '<div class="col-lg-6 col-md-12""><div class="card">';
            $output_zona_info_general .= '<div class="card-header text-black fs-14">'.$translator->trans('Office_general_info').'</div><div class="card-body"><ul class="list-group fs-12">';
            $output_zona_info_general .= '<li class="list-group-item d-flex justify-content-between align-items-center"><strong>'.$translator->trans('Office_code').':</strong> '.$office->getCodigo().'</li>';
            $output_zona_info_general .= '<li class="list-group-item d-flex justify-content-between align-items-center"><strong>'.$translator->trans('Center_category').':</strong> '.$office->getCategoria().'</li>';
            $output_zona_info_general .= '<li class="list-group-item d-flex justify-content-between align-items-center"><strong>'.$translator->trans('Center_type').':</strong> '.$office->getTipo().'</li>';
            $output_zona_info_general .= '</ul></div></div></div>';

            /* INFORMACIÓN DE CONTACTO */
            $output_zona_info_contacto = '<div class="col-lg-6 col-md-12""><div class="card">';
            $output_zona_info_contacto .= '<div class="card-header text-black fs-14">'.$translator->trans('Office_contact_info').'</div><div class="card-body"><ul class="list-group fs-12">';
            $output_zona_info_contacto .= '<li class="list-group-item d-flex justify-content-between align-items-center"><strong>'.$translator->trans('Office_email').':</strong> '.$office->getEmail().'</li>';
            $output_zona_info_contacto .= '<li class="list-group-item d-flex justify-content-between align-items-center"><strong>'.$translator->trans('Office_phone').'1:</strong> '.$office->getTelefonoPrincipal().'</li>';
            $output_zona_info_contacto .= '<li class="list-group-item d-flex justify-content-between align-items-center"><strong>'.$translator->trans('Office_phone').'2:</strong> '.$office->getTelefonoSecundario().'</li>';
            $output_zona_info_contacto .= '</ul></div></div></div>';

            /* INFORMACIÓN DE USUARIOS */
            $output_zona_info_users = '<div class="col-lg-6 col-md-12""><div class="card">';
            $output_zona_info_users .= '<div class="card-header text-black fs-14">'.$translator->trans('Office_users_list').'</div><div class="card-body"><ul class="list-group fs-12">';
            $output_zona_info_users .= '<li class="list-group-item d-flex justify-content-between align-items-center"><strong>'.$translator->trans('Office_director').':</strong> '.$office->getDirectorActual().'</li>';
            $output_zona_info_users .= '<li class="list-group-item d-flex justify-content-between align-items-center"><strong>'.$translator->trans('Office_manager').':</strong> '.$office->getGestorActual().'</li>';
            $output_zona_info_users .= '<li class="list-group-item d-flex justify-content-between align-items-center"><strong>'.$translator->trans('Office_comercial').'1:</strong></li>';
            $output_zona_info_users .= '</ul></div></div></div>';

            /* INFORMACIÓN DE DIRECCION */
            $output_zona_info_direccion = '<div class="col-lg-6 col-md-12""><div class="card">';
            $output_zona_info_direccion .= '<div class="card-header text-black fs-14">'.$translator->trans('Office_address_info').'</div><div class="card-body"><ul class="list-group fs-12">';
            $output_zona_info_direccion .= '<li class="list-group-item d-flex justify-content-between align-items-center"><strong>'.$translator->trans('Office_country').':</strong> '.$office->getPais().'</li>';
            $output_zona_info_direccion .= '<li class="list-group-item d-flex justify-content-between align-items-center"><strong>'.$translator->trans('Office_province').':</strong> '.$office->getProvincia().'</li>';
            $output_zona_info_direccion .= '<li class="list-group-item d-flex justify-content-between align-items-center"><strong>'.$translator->trans('Office_town').'2:</strong> '.$office->getPoblacion().'</li>';
            $output_zona_info_direccion .= '<li class="list-group-item d-flex justify-content-between align-items-center"><strong>'.$translator->trans('Office_address_general').'1:</strong> '.$office->getDg().'</li>';
            $output_zona_info_direccion .= '<li class="list-group-item d-flex justify-content-between align-items-center"><strong>'.$translator->trans('Office_address_territory').'1:</strong> '.$office->getDt().'</li>';
            $output_zona_info_direccion .= '<li class="list-group-item d-flex justify-content-between align-items-center"><strong>'.$translator->trans('Office_address_zone').'1:</strong> '.$office->getDan().'</li>';
            $output_zona_info_direccion .= '<li class="list-group-item d-flex justify-content-between align-items-center"><strong>'.$translator->trans('Office_address').'1:</strong> '.$office->getDomicilio().'</li>';
            $output_zona_info_direccion .= '</ul></div></div></div>';



            $output = '<div class="col-lg-12 fs-12"><div class="row">'.$output_zona_info_general.$output_zona_info_contacto.'</div><div class="row mt-10">'.$output_zona_info_users.$output_zona_info_direccion.'</div></div>';
            return new JsonResponse($output, 200);
        } catch (Exception $e) {
            return new Response($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Find offices for select2
     *
     * @param Request $request
     * @return JsonResponse|Response
     *
     */
    public function selectOffices(Request $request)
    {
        $query = $request->query->get('q');
        if ($query != null) {
            $this->getDoctrine()->getManager();
            $result = $this->getDoctrine()->getRepository(MasterOffice::class)->findOfficesSelect($query);
            return new JsonResponse($result);
        }
        return new Response('Not Found', 404);
    }

    /**
     * Find offices for select2
     *
     * @param Request $request
     * @return JsonResponse|Response
     *
     */
    public function selectOffices2(Request $request)
    {
        $query = $request->query->get('q');
        if ($query != null) {
            $this->getDoctrine()->getManager();
            $result = $this->getDoctrine()->getRepository(Office::class)->findOfficesSelect2($query);
            return new JsonResponse($result);
        }
        return new Response('Not Found', 404);
    }

    /**
     * Find responsable by office
     *
     * @param Request $request
     * @return Response
     */
    public function findOffice(Request $request)
    {
        $query = $request->request->get('id_office');

        $results = $this->getDoctrine()->getRepository(MasterOffice::class)->findBy(array('codigo' => $query));

        $text = "";
        if ($results) {
            foreach ($results as $result) {
                $text = $result->getNombre();
            }
        }

        return new Response($text);
    }

    /**
     * Find Office responsable
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function findOfficeResponsable(Request $request): JsonResponse
    {
        $office           = $request->request->get('office');
        $commercialOffice = $this->getDoctrine()->getRepository(CommercialOffice::class)->findOneBy(['Office' => $office]);

        if ($commercialOffice) {
            $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['regNumber' => $commercialOffice->getCommercial()]);
            if ($user) {
                return new JsonResponse([
                    'name'      => $user->getName().' '.$user->getSurname(),
                    'id'        => $user->getId(),
                    'regNumber' => $user->getRegNumber(),
                ]);
            }
        }

        return new JsonResponse(['id' => 0]);
    }
}
