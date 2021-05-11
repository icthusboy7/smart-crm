<?php

/**
 * Controlador de alertas
 */
namespace App\Controller;

use \DateTime;
use App\Core\Entity\Contact;
use App\Entity\MasterOffice;
use App\Entity\MasterQuotation;

// ENTITYS
use App\Entity\ComercialAlertas;
use App\Entity\ComercialAlertasFilters;
use App\Entity\ComercialCotizacion;
use App\Entity\ComercialExpediente;
use App\Entity\Horizontal;
use App\Entity\Vertical;

use App\Form\Type\AlertasFormType;

// COMPONENTS
use App\Utils\Widgets;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;

// SERVICES
use App\Service\AlertasService;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Security\Core\Security;

/**
 * Class AlertasController
 */
class ComercialAlertasController extends AbstractController
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
     * Entity manager
     * @var EntityManagerInterface
     */
    private $security;


    /**
     * AlertasController constructor.
     * @param Widgets                $widgets
     * @param PaginatorInterface     $knpPaginator
     * @param EntityManagerInterface $em
     * @param Security               $security
     */
    public function __construct(Widgets $widgets, PaginatorInterface $knpPaginator, EntityManagerInterface $em, Security $security)
    {
        $this->widgets   = $widgets;
        $this->paginator = $knpPaginator;
        $this->em        = $em;
        $this->security  = $security;
    }

    /**
     * List alertas Comerciales
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request): Response
    {
        //SET FILTERS
        $query = [];

        if ($request->query->get('buscar')) {
            $query['buscar'] = $request->query->get('buscar');
        }

        //APPLY FILTERS

        $alertas = $this->getDoctrine()->getRepository(ComercialAlertas::class)->findTableView($query, 1);

        $filters = $this->getDoctrine()->getRepository(ComercialAlertasFilters::class)->findBy(['user' => [$this->getUser(), null]]);

        $paginator  = $this->paginator;
        $pagination = $paginator->paginate(
            $alertas,
            $request->query->getInt('page', 1),
            10
        );

        $pagination->setCustomParameters(['position' => 'centered']);

        return $this->render('area-comercial/listar_alertas.html.twig', ['alertas' => $pagination, 'filters' => $filters]);
    }

    /**
     * Gestionar Alerta
     *
     * @param Request $request
     *
     * @return Response
     */
    public function gestionarAlertas(Request $request): Response
    {

        $em = $this->getDoctrine()->getManager();

        $id = (!$request->get('id') ? 0 : $request->get('id'));

        $alerta = $em->getRepository(ComercialAlertas::class)->findOneBy(['id' => $id]);

        $form = $this->createForm(AlertasFormType::class, $alerta, ['form_type' => $request->get('action')]);
        $form->handleRequest($request);

        return $this->render('area-comercial/modals/alertas_gestiona.html.twig', [
            'origen' => $request->get('action'),
            'form'   => $form->createView(),
            'alerta' => $alerta,
        ]);
    }

    /**
     * Find data with parameter Requested
     *
     * @param Request $request
     *
     * @return JsonResponse|Response
     */
    public function findDataAlert(Request $request)
    {
        $type   = $request->query->get('type');
        $query  = $request->query->get('q');
        $action = $request->get('action');
        $id     = $request->get('id');

        if (!is_null($query)) {
            if ('expedients' === $type) {
                $result = $this->getDoctrine()->getRepository(ComercialExpediente::class)->findByTitulo($query);

                return new JsonResponse($result);
            }
            if ('cotizacion' === $type) {
                $result = $this->getDoctrine()->getRepository(MasterQuotation::class)->findByNumCoti($query);

                return new JsonResponse($result);
            }
            if ('horizontal' === $type) {
                $result = $this->getDoctrine()->getRepository(Horizontal::class)->findByName($query);

                return new JsonResponse($result);
            }
            if ('vertical' === $type) {
                $result = $this->getDoctrine()->getRepository(Vertical::class)->findByName($query);

                return new JsonResponse($result);
            }

            return new Response('Not Found', 404);
        }

        if ('edit_alert' === $action) {
            $result = $this->getDoctrine()->getRepository(ComercialAlertas::class)->find($id);

            return new JsonResponse([
                'h' => (is_null($result->getHorizontal()) ? '' : $result->getHorizontal()->getId()),
                'v' => (is_null($result->getVertical()) ? '' : $result->getVertical()->getId()),
            ]);
        }

        return new Response('Not Found', 404);
    }

    /**
     * Create Alert
     * @param Request $request
     *
     * @return Response
     *
     * @throws \Exception
     */
    public function createAlert(Request $request): Response
    {
        $alertForm = $request->request->get('alertas_form');

        $entityManager = $this->getDoctrine()->getManager();

        if (strlen($alertForm['missatge']) === 0) {
            return new Response('empty message', 500);
        }

        if (empty($alertForm['id'])) {
            $alert = new ComercialAlertas();
        } else {
            $alert = $this->getDoctrine()->getRepository(ComercialAlertas::class)
                ->findOneBy(['id' => $alertForm['id']]);

            $alert->setUpdatedAt(new DateTime());
        }

        $vertical   = null;
        $horizontal = null;
        $pipeline   = null;
        if (isset($alertForm['vertical'])) {
            $vertical = $this->getDoctrine()->getRepository(Vertical::class)->find($alertForm['vertical']);
        }
        if (isset($alertForm['horizontal'])) {
            $horizontal = $this->getDoctrine()->getRepository(Horizontal::class)->find($alertForm['horizontal']);
        }

        $alert->setNivel($alertForm['nivel'])
            ->setMissatge($alertForm['missatge'])
            ->setDeleted(0)
            ->setCotizacion((!isset($alertForm['cotizacion']) ? '' : $alertForm['cotizacion']))
            ->setPersonaNif((!isset($alertForm['personanif']) ? '' : $alertForm['personanif']))
            ->setOficina((!isset($alertForm['oficina']) ? '' : $alertForm['oficina']))
            ->setHorizontal($horizontal)
            ->setVertical($vertical)
            ->setActive(
                (isset($alertForm['active']) ? (('1' === $alertForm['active'] || '' === $alertForm['active'] ) ? 1 : 0) : 0)
            )
            ->setIsAlert(
                (isset($alertForm['isAlert']) ? (('1' === $alertForm['isAlert']) ? true : false) : false)
            )
            ->setUpdatedAt(new DateTime());

        if (isset($alertForm['expediente'])) {
            $pipeline = $this->getDoctrine()->getRepository(ComercialExpediente::class)->findOneBy(['id' => $alertForm['expediente']]);
        }

        $alert->setExpediente((is_null($pipeline) ? null : $pipeline));


        try {
            $entityManager->persist($alert);
            $entityManager->flush();

            return new Response((empty($alertForm['id']) ? 'add' : 'updated'));
        } catch (\Exception $e) {
            return new Response($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Borrado no fisico de las alertas
     *
     * @param Request $request
     *
     * @return Response
     *
     * @throws \Exception
     */
    public function deleteAlertas(Request $request): Response
    {

        $entityManager = $this->getDoctrine()->getManager();

        $id = (!$request->get('id') ? 0 : $request->get('id'));

        $alert = $this->getDoctrine()->getRepository(ComercialAlertas::class)->findOneBy(['id' => $id]);

        $alert->setDeleted(1)
            ->setDeletedBy($this->security->getUser()->getUsername())
            ->setDeletedAt(new DateTime());

        try {
            $entityManager->persist($alert);
            $entityManager->flush();

            return new Response();
        } catch (\Exception $e) {
            return new Response($e->getMessage(), $e->getCode());
        }
    }

    /**
     * getFullInfoAlerta
     *
     * @param Request $request
     *
     * @return Response
     */
    public function getFullInfoAlerta(Request $request): Response
    {
        $alert = $this->getDoctrine()->getRepository(ComercialAlertas::class)->findOneBy(['id' => $request->request->get('id')]);

        $alerta['expediente'] = $alerta['cotizacion'] = $alerta['personanif'] = $alerta['oficina'] = $alerta['horizontal'] = $alerta['vertical'] = '';

        if (!is_null($alert->getExpediente())) {
            $data = $this->em->getRepository(ComercialExpediente::class)->findBy(['id' => $alert->getExpediente()]);

            if (!empty($data)) {
                $alerta['expediente'] = $data[0]->getTitulo();
            }
        }

        if (!is_null($alert->getExpediente()) && !is_null($alert->getCotizacion())) {
            $data = $this->em->getRepository(ComercialCotizacion::class)->findBy(['numCoti' => $alert->getCotizacion()]);

            if (!empty($data)) {
                $alerta['cotizacion'] = $data[0]->getNumCoti();
            }
        }

        $data = $this->em->getRepository(Contact::class)->findOneBy(['nif' => $alert->getPersonaNif()]);

        if (!empty($data)) {
            $alerta['personanif'] = $data->getNif().' - '.$data->getName();
        }

        $data = $this->em->getRepository(MasterOffice::class)->findBy(['codigo' => $alert->getOficina()]);

        if (!empty($data)) {
            $alerta['oficina'] = $data[0]->getNombre();
        }

        $data = $this->em->getRepository(Horizontal::class)->findBy(['id' => $alert->getHorizontal()]);

        if (!empty($data)) {
            $alerta['horizontal'] = $data[0]->getName();
        }

        $data = $this->em->getRepository(Vertical::class)->findBy(['id' => $alert->getVertical()]);

        if (!empty($data)) {
            $alerta['vertical'] = $data[0]->getName();
        }

        $alerta['nivel']    = $alert->getNivel();
        $alerta['missatge'] = $alert->getMissatge();

        return $this->render('area-comercial/modals/info_alerta_modal.html.twig', ['alerta' => $alerta ]);
    }

    /**
     * Create a custom query for office view getting URL parameters
     *
     * @param Request $request
     *
     * @return Response
     */
    public function createQueryAlerta(Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $filter        = new ComercialAlertasFilters();
        $filter->setUser($this->security->getUser());
        $filter->setName($request->request->get('query_name'));

        $query = '?';
        if ($request->request->get('buscar')) {
            $query .= 'buscar='.$request->request->get('buscar');
        }
        $filter->setQuery($query);

        try {
            $entityManager->persist($filter);
            $entityManager->flush();

            return new Response();
        } catch (\Exception $e) {
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
    public function deleteQueryAlerta(Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $queryDeleted  = $this->getDoctrine()->getRepository(ComercialAlertasFilters::class)
            ->findOneBy([
                'id' => $request->query->get('id'),
            ]);
        try {
            $entityManager->remove($queryDeleted);
            $entityManager->flush();

            return new Response();
        } catch (\Exception $e) {
            return new Response($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Obtener nif por alerta
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function getNifByAlertId(Request $request): JsonResponse
    {
        $nif    = '';
        $em     = $this->getDoctrine()->getManager();
        $id     = $request->get('id');
        $alerta = $em->getRepository(ComercialAlertas::class)->findOneBy(['id' => $id]);
        $nif    = $alerta->getPersonaNif();

        return new JsonResponse($nif);
    }

    /**
     * Obtener oficina por alerta
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function getOfficeByAlertId(Request $request): JsonResponse
    {
        $office = '';
        $em     = $this->getDoctrine()->getManager();
        $id     = $request->get('id');
        $alerta = $em->getRepository(ComercialAlertas::class)->findOneBy(['id' => $id]);

        if (isset($alerta) && !is_null($alerta)) {
            $office = $alerta->getOficina();
        }

        return new JsonResponse($office);
    }

    /**
     * @param Request        $request
     * @param AlertasService $alertasService
     *
     * @return Response
     */
    public function findAlertNif(Request $request, AlertasService $alertasService): Response
    {
        $result = $alertasService->getAlertsNIF($request->request->get('nif'));
        $type   = $request->request->get('type');

        return (count($result) > 0) ? $this->render('area-comercial/includes/alert_info.html.twig', ['alert' => count($result), 'type' => $type]) : new Response();
    }

    /**
     * @param Request        $request
     * @param AlertasService $alertasService
     *
     * @return JsonResponse
     */
    public function getAlertInfoNif(Request $request, AlertasService $alertasService): JsonResponse
    {
        $result   = $alertasService->getAlertsNIF($request->request->get('nif'));
        $i        = 0;
        $response = [];

        foreach ($result as $alerta) {
            $response[$i] = $alerta->getMissatge();
            $i++;
        }

        return new JsonResponse($response);
    }

    /**
     * @param Request        $request
     * @param AlertasService $alertasService
     *
     * @return Response
     */
    public function findAlertOffice(Request $request, AlertasService $alertasService): Response
    {
        $result   = $alertasService->getAlertsOffice($request->request->get('office'));
        $type   = $request->request->get('type');

        return (count($result) > 0) ? $this->render('area-comercial/includes/alert_info.html.twig', ['alert' => count($result), 'type' => $type]) : new Response();
    }

    /**
     * @param Request        $request
     * @param AlertasService $alertasService
     *
     * @return JsonResponse
     */
    public function getAlertInfoOffice(Request $request, AlertasService $alertasService): JsonResponse
    {
        $result   = $alertasService->getAlertsOffice($request->request->get('office'));
        $i        = 0;
        $response = [];
        foreach ($result as $alerta) {
            $response[$i] = $alerta->getMissatge();
            $i++;
        }

        return new JsonResponse($response);
    }
}
