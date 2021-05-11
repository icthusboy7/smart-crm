<?php
/**
 * Created by PhpStorm.
 * User: sbernadas
 * Date: 26/06/2019
 * Time: 11:24
 */

namespace App\Controller;

/* ENTITIES */

use App\Core\Entity\Contact;
use App\Core\Entity\Vocabs\ContactKind;
use App\Entity\ComercialCotizacion;
use App\Entity\ComercialCotizacionFavoritos;
use App\Entity\ComercialExpediente;
use App\Entity\ComercialExpedienteCotizaciones;
use App\Entity\ComercialExpedienteFavoritos;
use App\Entity\ComercialExpedienteStatus;
use App\Entity\ComercialProducto;
use App\Entity\CommercialResponsable;
use App\Entity\MasterOffice;
use App\Entity\TipoCanal;
use App\Entity\User;
use App\Entity\Vertical;
use App\Service\ExcelExportService;
use App\Service\MuroService;
use App\Service\SAPConnectorService;
use App\Utils\Widgets;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class ExpedientesController
 */
class ExpedientesController extends AbstractController
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
     * Logger interface
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * SapConector Service
     * @var SAPConnectorService
     */
    protected $SAPConnectorService;

    /**
     * Muro Service
     * @var MuroService
     */
    protected $muroService;

    /**
     * Translator Service
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * Class for "default" calendar pipelines
     * @var string
     */
    private $badgeInfoClass = 'info';

    /**
     * Class for "green" calendar pipelines
     * @var string
     */
    private $badgeSuccessClass = 'success';

    /**
     * Class for "yellow" calendar pipelines
     * @var string
     */
    private $badgeWarningClass = 'warning';

    /**
     * Class for "red" calendar pipelines
     * @var string
     */
    private $badgeDangerClass = 'danger';

    /**
     * ExpedientesController constructor
     *
     * @param Widgets                $widgets
     * @param PaginatorInterface     $knpPaginator
     * @param EntityManagerInterface $em
     * @param LoggerInterface        $logger
     * @param SAPConnectorService    $SAPConnectorService
     * @param MuroService            $muroService
     * @param TranslatorInterface    $translator
     */
    public function __construct(
        Widgets $widgets,
        PaginatorInterface $knpPaginator,
        EntityManagerInterface $em,
        LoggerInterface $logger,
        SAPConnectorService $SAPConnectorService,
        MuroService $muroService,
        TranslatorInterface $translator
    ) {
        $this->widgets             = $widgets;
        $this->paginator           = $knpPaginator;
        $this->em                  = $em;
        $this->logger              = $logger;
        $this->SAPConnectorService = $SAPConnectorService;
        $this->muroService         = $muroService;
        $this->translator          = $translator;
    }

    /**
     * Render de la vista ListarExpedientes
     *
     * @param Request $request
     *
     * @return Response render
     */
    public function index(Request $request): Response
    {
        $responsables       = $this->getDoctrine()
            ->getRepository(CommercialResponsable::class)->getResponsables();
        $estados            = $this->getDoctrine()
            ->getRepository(ComercialExpedienteStatus::class)->findAll();
        $statusID           = [];
        $zonasFilter        = [];
        $responsablesFilter = [];
        $nameFilter         = null;
        $filtroGlobal       = $request->query->get('filtroGlobal') !== 'none' ? 'curso' : 'none';

        /* Aplicamos filtros globales */
        if ($request->query->get('filtroGlobal') === 'todos') {
            $filtroGlobal = 'todos';
            foreach ($estados as $estado) {
                array_push($statusID, $estado->getId());
            }
        }
        if ($request->query->get('filtroGlobal') === 'perdidos') {
            $filtroGlobal = 'perdidos';
            array_push($statusID, 2);
        }
        if ($request->query->get('filtroGlobal') === 'activados') {
            $filtroGlobal = 'activados';
            array_push($statusID, 3);
        }
        if ($request->query->get('filtroGlobal') === 'proveedores') {
            $filtroGlobal = 'proveedores';
            array_push($statusID, 1);
        }
        if ($request->query->get('filtroGlobal') === 'oficinas') {
            $filtroGlobal = 'oficinas';
            array_push($statusID, 1);
        }
        if ('curso' === $filtroGlobal) {
            array_push($statusID, 1);
        }

        /* Aplicamos filtros especificos */
        if (!empty($request->query->get('f_estados')) and !is_null($request->query->get('f_estados'))) {
            $filtroGlobal = 'none';
            foreach ($request->query->get('f_estados') as $estado) {
                array_push($statusID, $estado);
            }
        }
        if (!empty($request->query->get('f_zona')) and !is_null($request->query->get('f_zona'))) {
            $filtroGlobal = 'none';
            foreach ($request->query->get('f_zona') as $zona) {
                array_push($zonasFilter, $zona);
            }
        }
        if (!empty($request->query->get('f_responsables')) and !is_null($request->query->get('f_responsables'))) {
            foreach ($request->query->get('f_responsables') as $responsable) {
                array_push($responsablesFilter, $responsable);
            }
        } elseif (is_null($request->query->get('filtroGlobal'))) {
            array_push($responsablesFilter, $this->getUser()->getId());
        }
        if (!empty($request->query->get('f_name')) and !is_null($request->query->get('f_name'))) {
            $nameFilter = $request->query->get('f_name');
        }

        /* Enviamos los filtros a la busqueda */
        $expedientes         = $this->getDoctrine()
            ->getRepository(ComercialExpediente::class)->findByExpedienteForm($statusID, $responsablesFilter, $nameFilter, $zonasFilter, false);
        $expedientesNoReport = $this->getDoctrine()
            ->getRepository(ComercialExpediente::class)->findByExpedienteForm($statusID, $responsablesFilter, $nameFilter, $zonasFilter, true);

        /* Paginamos los resultados */
        $paginator   = $this->paginator;
        $expedientes = $paginator->paginate(
            $expedientes,
            $request->query->getInt('page', 1),
            10
        );
        $expedientes->setCustomParameters(['position' => 'centered']);

        $expedientesNoReport = $paginator->paginate(
            $expedientesNoReport,
            $request->query->getInt('page', 1),
            10
        );
        $expedientesNoReport->setCustomParameters(['position' => 'centered']);

        foreach ($expedientes as $expediente) {
            $expediente->badgeColor = $this->getBadgeCalendarColor(
                $expediente->getfechaPosibleActivacion(),
                $expediente->getPorcentajeProbabilidad()
            );
        }

        foreach ($expedientesNoReport as $expediente) {
            $expediente->badgeColor = $this->getBadgeCalendarColor(
                $expediente->getfechaPosibleActivacion(),
                $expediente->getPorcentajeProbabilidad()
            );
        }


        $responsablesSelect = $this->getDoctrine()->getRepository(User::class)->findBy(['id' => $responsablesFilter]);

        /* Añadimos los counts para la vista */
        $counts              = [];
        $counts['perdidos']  = $this->getDoctrine()->getRepository(ComercialExpediente::class)->countPerdidos();
        $counts['enCurso']   = $this->getDoctrine()->getRepository(ComercialExpediente::class)->countEnCurso();
        $counts['activados'] = $this->getDoctrine()->getRepository(ComercialExpediente::class)->countActivados();
        $counts['total']     = $counts['enCurso'] + $counts['activados'] + $counts['perdidos'];

        /* Renderizamos la vista */
        return $this->render(
            'area-comercial/listar_expedientes.html.twig',
            [
                'responsables'        => $responsables,
                'responsablesFilter'  => $responsablesSelect,
                'expedientes'         => $expedientes,
                'expedientesNoReport' => $expedientesNoReport,
                'counts'              => $counts,
                'filtroGlobal'        => $filtroGlobal,
                'estados'             => $estados,
                'status_id'           => $statusID,
                'zonas'               => $zonasFilter,
            ]
        );
    }

    /**
     * Render de la vista ListarExpedientes modo calendario
     *
     * @param Request $request
     *
     * @return Response render
     */
    public function indexCalendario(Request $request): Response
    {
        $responsables       = $this->getDoctrine()
            ->getRepository(CommercialResponsable::class)->getResponsables();
        $estados            = $this->getDoctrine()
            ->getRepository(ComercialExpedienteStatus::class)->findAll();
        $statusID           = [];
        $responsablesFilter = [];
        $zonasFilter        = [];
        $nameFilter         = null;
        $filtroGlobal       = $request->query->get('filtroGlobal') !== 'none' ? 'curso' : 'none';

        /* Aplicamos filtros globales */
        if ($request->query->get('filtroGlobal') === 'todos') {
            $filtroGlobal = 'todos';
            foreach ($estados as $estado) {
                array_push($statusID, $estado->getId());
            }
        }
        if ($request->query->get('filtroGlobal') === 'perdidos') {
            $filtroGlobal = 'perdidos';
            array_push($statusID, 2);
        }
        if ($request->query->get('filtroGlobal') === 'activados') {
            $filtroGlobal = 'activados';
            array_push($statusID, 3);
        }
        if ($request->query->get('filtroGlobal') === 'proveedores') {
            $filtroGlobal = 'proveedores';
            array_push($statusID, 1);
        }
        if ($request->query->get('filtroGlobal') === 'oficinas') {
            $filtroGlobal = 'oficinas';
            array_push($statusID, 1);
        }
        if ('curso' === $filtroGlobal) {
            array_push($statusID, 1);
        }

        /* Aplicamos filtros especificos */
        if (!empty($request->query->get('f_estados')) and !is_null($request->query->get('f_estados'))) {
            $filtroGlobal = 'none';
            foreach ($request->query->get('f_estados') as $estado) {
                array_push($statusID, $estado);
            }
        }
        if (!empty($request->query->get('f_zona')) and !is_null($request->query->get('f_zona'))) {
            $filtroGlobal = 'none';
            foreach ($request->query->get('f_zona') as $zona) {
                array_push($zonasFilter, $zona);
            }
        }
        if (!empty($request->query->get('f_responsables')) and !is_null($request->query->get('f_responsables'))) {
            foreach ($request->query->get('f_responsables') as $responsable) {
                array_push($responsablesFilter, $responsable);
            }
        }
        if (!empty($request->query->get('f_name')) and !is_null($request->query->get('f_name'))) {
            $nameFilter = $request->query->get('f_name');
        }

        /* Obtenemos el año del filtro o el año actual */
        $year = !is_null($request->query->get('f_year'))
            ? $request->query->get('f_year')
            : date('Y');
        /* Sacamos lista de expedientes año agrupados por fecha */
        for ($month = 1; $month <= 12; $month++) {
            $dateObj                             = \DateTime::createFromFormat('!m', $month);
            $monthName                           = $dateObj->format('F');
            $expedientes[$month]['title']        = $monthName.' '.$year;
            $expedientes[$month]['dangerCount']  = 0;
            $expedientes[$month]['warningCount'] = 0;
            $expedientes[$month]['successCount'] = 0;
            $expedientes[$month]['expedientes']  = $this->getDoctrine()
                ->getRepository(ComercialExpediente::class)->findByExpedienteForm($statusID, $responsablesFilter, $nameFilter, $zonasFilter, false, true, $month.'/'.$year);
            foreach ($expedientes[$month]['expedientes'] as &$expediente) {
                $expediente['badge'] = $this->getBadgeCalendarColor($expediente['fechaPosibleActivacion'], $expediente['porcentajeProbabilidad']);
                switch ($expediente['badge']) {
                    case $this->badgeSuccessClass:
                            $expedientes[$month]['successCount']++;
                        break;
                    case $this->badgeWarningClass:
                            $expedientes[$month]['warningCount']++;
                        break;
                    case $this->badgeDangerClass:
                            $expedientes[$month]['dangerCount']++;
                        break;
                }
            }
        }

        $responsablesSelect = $this->getDoctrine()->getRepository(User::class)->findBy(['id' => $responsablesFilter]);

        /* Añadimos los counts para la vista */
        $counts              = [];
        $counts['perdidos']  = $this->getDoctrine()->getRepository(ComercialExpediente::class)->countPerdidos();
        $counts['enCurso']   = $this->getDoctrine()->getRepository(ComercialExpediente::class)->countEnCurso();
        $counts['activados'] = $this->getDoctrine()->getRepository(ComercialExpediente::class)->countActivados();
        $counts['total']     = $counts['enCurso'] + $counts['activados'] + $counts['perdidos'];

        /* Renderizamos la vista */
        return $this->render(
            'area-comercial/listar_expedientes_calendario.html.twig',
            [
                'responsables'       => $responsables,
                'responsablesFilter' => $responsablesSelect,
                'expedientes'        => $expedientes,
                'counts'             => $counts,
                'filtroGlobal'       => $filtroGlobal,
                'estados'            => $estados,
                'status_id'          => $statusID,
                'pagination'         => $year,
                'zonas'              => $zonasFilter,
            ]
        );
    }

    /**
     * Constructor de row de la tabla de expedientes
     *
     * @param ComercialExpediente $expediente
     *
     * @return Response render
     */
    public function expedienteBoxConstructor(ComercialExpediente $expediente): Response
    {
        $cotizacionesIds = [];
        foreach ($expediente->getCotizaciones() as $coti) {
            array_push($cotizacionesIds, $coti->getCotizacion());
        }
        $cotizaciones  = $this->getDoctrine()->getRepository(ComercialCotizacion::class)
            ->findBy(
                [
                    'numCoti' => $cotizacionesIds,
                ]
            );
        $favoritos     = $this->getDoctrine()->getRepository(ComercialExpedienteFavoritos::class)->findBy(['user' => $this->getUser()]);
        $favoritosList = [];
        foreach ($favoritos as $favorito) {
            array_push($favoritosList, $favorito->getExpediente()->getId());
        }

        return $this->render(
            'area-comercial/expedientes/expediente_box.html.twig',
            [
                'expediente'   => $expediente,
                'cotizaciones' => $cotizaciones,
                'favoritos'    => $favoritosList,
            ]
        );
    }

    /**
     * Constructor de row para linea de la tabla de expedientes
     *
     * @param ComercialExpediente $expediente
     *
     * @return Response render
     */
    public function lineaBoxConstructor(ComercialExpediente $expediente): Response
    {
        $cotizacionesIds = [];
        foreach ($expediente->getCotizaciones() as $coti) {
            array_push($cotizacionesIds, $coti->getCotizacion());
        }
        $cotizaciones = $this->getDoctrine()->getRepository(ComercialCotizacion::class)
            ->findBy(
                [
                    'numCoti' => $cotizacionesIds,
                ]
            );

        $disposiciones = $this->getDoctrine()
            ->getRepository(ComercialExpediente::class)
            ->findBy(
                [
                    'esDisposicion'   => 1,
                    'expedientePadre' => $expediente,
                ]
            );
        $favoritos     = $this->getDoctrine()->getRepository(ComercialExpedienteFavoritos::class)->findBy(['user' => $this->getUser()]);
        $favoritosList = [];
        foreach ($favoritos as $favorito) {
            array_push($favoritosList, $favorito->getExpediente()->getId());
        }
        foreach ($disposiciones as &$dispo) {
            $dispoCotisID = [];
            foreach ($dispo->getCotizaciones() as $coti) {
                array_push($dispoCotisID, $coti->getCotizacion());
            }
            $dispo->cotizacionesInfo = $this->getDoctrine()->getRepository(ComercialCotizacion::class)
                ->findBy(
                    [
                        'numCoti' => $dispoCotisID,
                    ]
                );
        }

        return $this->render(
            'area-comercial/expedientes/linea_box.html.twig',
            [
                'expediente'    => $expediente,
                'cotizaciones'  => $cotizaciones,
                'disposiciones' => $disposiciones,
                'favoritos'     => $favoritosList,
            ]
        );
    }

    /**
     * Insert and edit form expediente
     *
     * @param Request $request
     *
     * @return Response
     */
    public function expedienteForm(?Request $request): Response
    {

        $expedienteId = $request->query->get('exp_id');
        if (null !== $expedienteId) {
            $expediente = $this->getDoctrine()
                ->getRepository(ComercialExpediente::class)
                ->find($expedienteId);
        }

        $estados = $this->getDoctrine()
            ->getRepository(ComercialExpedienteStatus::class)->findAll();

        $verticales = $this->getDoctrine()
            ->getRepository(Vertical::class)->findAll();

        $tiposProducto = $this->getDoctrine()
            ->getRepository(ComercialProducto::class)->findAll();

        $tiposCanal = $this->getDoctrine()
            ->getRepository(TipoCanal::class)->findAll();

        $allUsers = $this->getDoctrine()
            ->getRepository(User::class)->findAll();

        $tiposContacto = ContactKind::entities();

        $viewVars = [
            'estados'        => $estados,
            'verticales'     => $verticales,
            'tipos_producto' => $tiposProducto,
            'tipos_canal'    => $tiposCanal,
            'all_users'      => $allUsers,
            'tipos_contacto' => $tiposContacto,
        ];
        if (isset($expediente)) {
            if (null !== $expediente->getOficina()) {
                $oficina             = $this->getDoctrine()
                    ->getRepository(MasterOffice::class)
                    ->findBy(['codigo' => $expediente->getOficina()]);
                $viewVars['oficina'] = $oficina;
            }
            if (null !== $expediente->getClienteNif()) {
                $cliente             = $this->getDoctrine()->getRepository(Contact::class)->findOneBy(['nif' => $expediente->getClienteNif()]);
                $viewVars['cliente'] = $cliente;
            }
            if (null !== $expediente->getPrescriptorCif()) {
                $prescriptor             = $this->getDoctrine()->getRepository(Contact::class)->findOneBy(['nif' => $expediente->getPrescriptorCif()]);
                $viewVars['prescriptor'] = $prescriptor;
            }
            if ($expediente->getEslinea()) {
                $disposiciones             = $this->getDoctrine()
                    ->getRepository(ComercialExpediente::class)
                    ->findBy(
                        [
                            'esDisposicion'   => 1,
                            'expedientePadre' => $expediente,
                        ]
                    );
                $viewVars['disposiciones'] = $disposiciones;
            }
            $viewVars['expediente'] = $expediente;
        }

        return $this->render('area-comercial/crear_expedientes.html.twig', $viewVars);
    }

    /**
     * Crear peticion de cotizacion
     *
     * @return Response
     */
    public function peticionCotizacionForm(): Response
    {
        $estados = $this->getDoctrine()
            ->getRepository(ComercialExpedienteStatus::class)->findAll();

        $verticales = $this->getDoctrine()
            ->getRepository(Vertical::class)->findAll();

        $tiposProducto = $this->getDoctrine()
            ->getRepository(ComercialProducto::class)->findAll();

        $tiposCanal = $this->getDoctrine()
            ->getRepository(TipoCanal::class)->findAll();

        $allUsers = $this->getDoctrine()
            ->getRepository(User::class)->findAll();

        $tiposContacto = ContactKind::entities();

        $viewVars = [
            'estados'        => $estados,
            'verticales'     => $verticales,
            'tipos_producto' => $tiposProducto,
            'tipos_canal'    => $tiposCanal,
            'all_users'      => $allUsers,
            'isPeticion'     => true,
            'tipos_contacto' => $tiposContacto,
        ];

        return $this->render('area-comercial/crear_expedientes.html.twig', $viewVars);
    }

    /**
     * Create expediente using form view
     *
     * @param Request            $request
     * @param ContainerInterface $container
     *
     * @return Response
     *
     * @throws \Exception
     */
    public function createExpediente(Request $request, ContainerInterface $container): Response
    {
        $expedienteId = $request->request->get('expediente_id');
        $reporte      = $request->request->get('sin_reporte') === 'true' ? true : false;
        // Revisar si es creación o edición de expediente
        if (null !== $expedienteId) {
            $expediente      = $this->getDoctrine()
                ->getRepository(ComercialExpediente::class)
                ->find($expedienteId);
            $expedienteClone = clone $expediente;
        } else {
            $expediente = new ComercialExpediente();
            $expediente->setCreatedAt(new \DateTime('now'));
            $expediente->setCreatedBy($this->getUser());
        }

        // Actualizar datos de expediente
        $expediente->setTitulo($request->request->get('Title'));
        $expediente->setClienteNif($request->request->get('Customer'));
        $expediente->setPrescriptorCif($request->request->get('Provider'));
        $expediente->setCanal($request->request->get('Track_type'));
        $expediente->setStatus(
            $this->getDoctrine()->getRepository(ComercialExpedienteStatus::class)
                ->find($request->request->get('State'))
        );
        $expediente->setCanal(
            $this->getDoctrine()->getRepository(TipoCanal::class)
                ->find($request->request->get('Track_type'))
        );
        $expediente->setProductoID(
            $this->getDoctrine()->getRepository(ComercialProducto::class)
                ->find($request->request->get('Type_product'))
        );
        $expediente->setVertical(
            $this->getDoctrine()->getRepository(Vertical::class)
                ->find($request->request->get('Vertical'))
        );
        if ($request->request->get('Amount') !== '') {
            $expediente->setImporte(str_replace(',', '', $request->request->get('Amount')));
        }
        if ($request->request->get('Pipeline_responsable') !== '') {
            $expediente->setResponsable(
                $this->getDoctrine()->getRepository(User::class)
                    ->find($request->request->get('Pipeline_responsable'))
            );
        } else {
            $expediente->setResponsable(null);
        }
        if ($request->request->get('Internal_manager') !== '') {
            $expediente->setResponsableGestorInterno(
                $this->getDoctrine()->getRepository(User::class)
                    ->find($request->request->get('Internal_manager'))
            );
        } else {
            $expediente->setResponsableGestorInterno(null);
        }
        if ($request->request->get('External_manager') !== '') {
            $expediente->setResponsableGestorExterno(
                $this->getDoctrine()->getRepository(User::class)
                    ->find($request->request->get('External_manager'))
            );
        } else {
            $expediente->setResponsableGestorExterno(null);
        }
        if ($request->request->get('CBK_responsable') !== '') {
            $expediente->setResponsableCaixa($request->request->get('CBK_responsable'));
        } else {
            $expediente->setResponsableCaixa(null);
        }
        $expediente->setOficina($request->request->get('Office'));
        $expediente->setPorcentajeProbabilidad($request->request->get('Chance'));
        $request->request->get('type_result') === ''
            ? $expediente->setTin(null)
            : $expediente->setTin($request->request->get('type_result'));
        $expediente->setFechaPosibleActivacion($request->request->get('Activation_month'));
        $expediente->setEslinea($request->request->get('esLinia'));
        $expediente->setNumLinea($request->request->get('line_number'));
        $expediente->setObservaciones($request->request->get('Observations'));
        $expediente->setNoReport($reporte);

        if ($request->request->get('esLinia') || $request->request->get('esLinia') === 1) {
            $request->request->get('line_state') !== ''
                ? $expediente->setEstado($request->request->get('line_state'))
                : $expediente->setEstado(null);
            $request->request->get('line_limit_amount') !== ''
                ? $expediente->setImporteLimite(str_replace(',', '', $request->request->get('line_limit_amount')))
                : $expediente->setImporteLimite(null);
            $request->request->get('line_available_amount') !== ''
                ? $expediente->setImporteDisponible(str_replace(',', '', $request->request->get('line_available_amount')))
                : $expediente->setImporteDisponible(null);
            $request->request->get('line_end_date') !== ''
                ? $expediente->setFechaVencimiento(\DateTime::createFromFormat('d/m/Y', $request->request->get('line_end_date')))
                : $expediente->setFechaVencimiento(null);
        }

        try {
            $this->em->persist($expediente);
            $this->em->flush();
        } catch (\Exception $e) {
            return new Response($e->getMessage(), $e->getCode());
        }
        $cotizaciones = $request->request->get('numCoti');
        if (null !== $expedienteId) {
            $this->insertExpedienteMuro($expediente, $expedienteClone);
            $this->cleanCotis($expedienteId, $cotizaciones);
        }
        foreach ($cotizaciones as $coti) {
            $this->createCotizacion($coti, $expediente->getId());
        }
        // Crear disposiciones si el expediente es una linia
        if ($request->request->get('esLinia') || $request->request->get('esLinia') === 1) {
            $endloop    = false;
            $itteration = 1;
            $allDispoId = [];
            // Mirar disposiciones del formulario y añadirlas a un array
            while ($request->request->get('dispo_id_'.$itteration)) {
                array_push($allDispoId, $request->request->get('dispo_id_'.$itteration));
                $itteration++;
            }
            $itteration = 1;
            // Revisar si las relaciones expediente-disposicion deben eliminarse
            if (null !== $expedienteId and null !== $allDispoId) {
                $this->cleanDispo($expedienteId, $allDispoId);
            }
            // Creacion de disposiciones del expediente
            while ($endloop === false) {
                $importe = $request->request->get('importe_'.$itteration);
                if (isset($importe)) {
                    $dispoExist      = $request->request->get('dispo_id_'.$itteration);
                    $expedienteDispo = isset($dispoExist) ?
                        $this->getDoctrine()->getRepository(ComercialExpediente::class)->find($dispoExist)
                        : new ComercialExpediente();
                    $status          = $this->getDoctrine()
                        ->getRepository(ComercialExpedienteStatus::class)
                        ->find($request->request->get('estado_'.$itteration));
                    $importe !== ''
                        ? $expedienteDispo->setImporte(str_replace(',', '', $importe))
                        : $expedienteDispo->setImporte(null);
                    $expedienteDispo->setPorcentajeProbabilidad($request->request->get('porcentajeProbabilidad_'.$itteration));
                    $request->request->get('tin_'.$itteration) === ''
                        ? $expedienteDispo->setTin(null)
                        : $expedienteDispo->setTin($request->request->get('tin_'.$itteration));
                    $expedienteDispo->setFechaPosibleActivacion($request->request->get('Activation_month'));
                    $expedienteDispo->setCreatedAt(new \DateTime('now'));
                    $expedienteDispo->setStatus($status);
                    $expedienteDispo->setExpedientePadre($expediente);
                    $expedienteDispo->setEsDisposicion(1);
                    $expedienteDispo->setEslinea(0);
                    $expedienteDispo->setNoReport($reporte);

                    $this->em->persist($expedienteDispo);
                    $this->em->flush();

                    // Creación de cotizaciones para disposición
                    $dispoCotis = [];
                    foreach ($request->request->get('numCoti_'.$itteration) as $coti) {
                        if (trim($coti) === '') {
                            continue;
                        }
                        array_push($dispoCotis, $coti);
                        $this->createCotizacion($coti, $expedienteDispo->getId());
                    }
                    $this->cleanCotis($expedienteDispo->getId(), $dispoCotis);
                    $itteration++;
                } else {
                    $endloop = true;
                }
            }
        }

        return new Response();
    }

    /**
     * Crear cotizacion y unir al expediente
     *
     * @param string   $numCoti       Numero de cotizacion
     * @param int|null $numExpediente Numero expediente que se asigna a la cotizacion
     *
     * @throws \Exception
     */
    public function createCotizacion(string $numCoti, ?int $numExpediente = null): void
    {
        //@TODO: Comprobar en el servicio de SAP que la cotización exista y obtener la info
        $validCoti = $this->SAPConnectorService->WSCheckNumCoti($numCoti, $numExpediente);
        if (false === $validCoti or '' === $numCoti) {
            //@TODO: Make this as error
            return;
        }

        if (is_null($numExpediente)) {
            //@TODO: Make this as error
            return;
        }

        $existJoin = $this->getDoctrine()->getRepository(ComercialExpedienteCotizaciones::class)->findOneBy(['expedienteID' => $numExpediente, 'cotizacion' => $numCoti]);
        if (null !== $existJoin) {
            return;
        }
        $joinCotiExp = new ComercialExpedienteCotizaciones();
        $joinCotiExp->setCotizacion($numCoti);
        $joinCotiExp->setExpedienteID(
            $this->getDoctrine()
                ->getRepository(ComercialExpediente::class)
                ->find($numExpediente)
        );

        $joinCotiExp->setCreatedAt(new \DateTime('now'));

        try {
            $this->em->persist($joinCotiExp);
            $this->em->flush();
        } catch (\Exception $e) {
            $this->logger->error('Cannnot join cotización '.$numCoti.' with expediente '.$numExpediente.' // Reason: '.$e->getMessage());
        }
    }

    /**
     * Limpiar todas las cotizaciones de un expediente que no estén en el array
     *
     * @param int   $idExpediente
     * @param array $allCotis
     */
    public function cleanCotis(int $idExpediente, array $allCotis): void
    {
        try {
            $this->getDoctrine()->getRepository(ComercialExpedienteCotizaciones::class)->findExpedienteClearCotis($idExpediente, $allCotis);
        } catch (\Exception $e) {
             return;
        }
    }

    /**
     * Limpiar todas las disposiciones de un expediente que no estén en el array
     *
     * @param int   $idExpediente
     * @param array $allDispo
     */
    public function cleanDispo(int $idExpediente, array $allDispo): void
    {
        try {
            $this->getDoctrine()->getRepository(ComercialExpediente::class)->findExpedienteClearDispo($idExpediente, $allDispo);
        } catch (\Exception $e) {
            return;
        }
    }

    /**
     * @return JsonResponse
     */
    public function getAllExpedientesList(): JsonResponse
    {
        $em          = $this->getDoctrine()->getManager();
        $expedientes = $em->getRepository(ComercialExpediente::class)->createQueryBuilder('p')
            ->where('p.deletedAt IS NULL')
            ->andWhere('p.esDisposicion != 1');

        $expedientes = $expedientes->getQuery()->getArrayResult();

        $response = ['expedientes' => $expedientes];

        return new JsonResponse($response);
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function getExpedienteForm(Request $request): JsonResponse
    {
        $em         = $this->getDoctrine()->getManager();
        $expediente = $em->getRepository(ComercialExpediente::class)->createQueryBuilder('p')
            ->where('p.id = :exp')
            ->setParameter('exp', $request->request->get('id_exp'));

        $expediente = $expediente->getQuery()->getArrayResult();

        $response = ['expediente' => $expediente];

        return new JsonResponse($response);
    }

    /**
     * Compara los valores antes del insert y crea los mensajes en el muro
     *
     * @param ComercialExpediente $expedienteUpdated
     * @param ComercialExpediente $expedienteClone
     *
     * @throws \Exception
     */
    public function insertExpedienteMuro(ComercialExpediente $expedienteUpdated, ComercialExpediente $expedienteClone): void
    {
        $user = $this->getUser();
        // change Title
        ($expedienteClone->getTitulo() !== $expedienteUpdated->getTitulo()) ?
            $this->muroService->changeStatusWall(
                $expedienteUpdated,
                'titulo',
                $user,
                $expedienteClone->getTitulo(),
                $expedienteUpdated->getTitulo()
            )
            : null;

        // change Import
        ($expedienteClone->getImporte() !== $expedienteUpdated->getImporte()) ?
            $this->muroService->changeStatusWall(
                $expedienteUpdated,
                'importe',
                $user,
                $expedienteClone->getImporte(),
                $expedienteUpdated->getImporte()
            )
            : null;

        // change % Probabilidad
        ($expedienteClone->getPorcentajeProbabilidad() !== $expedienteUpdated->getPorcentajeProbabilidad()) ?
            $this->muroService->changeStatusWall(
                $expedienteUpdated,
                'porcentaje de probabilidad',
                $user,
                $expedienteClone->getPorcentajeProbabilidad(),
                $expedienteUpdated->getPorcentajeProbabilidad()
            )
            : null;

        // change Date Possible Activation
        ($expedienteClone->getFechaPosibleActivacion() !== $expedienteUpdated->getFechaPosibleActivacion()) ?
            $this->muroService->changeStatusWall(
                $expedienteUpdated,
                'fecha posible activación',
                $user,
                $expedienteClone->getFechaPosibleActivacion(),
                $expedienteUpdated->getFechaPosibleActivacion()
            )
            : null;

        // change Customer
        ($expedienteClone->getClienteNIF() !== $expedienteUpdated->getClienteNIF()) ?
            $this->muroService->changeStatusWall(
                $expedienteUpdated,
                'cliente',
                $user,
                $expedienteClone->getClienteNIF(),
                $expedienteUpdated->getClienteNIF()
            )
            : null;

        // change Provider
        ($expedienteClone->getPrescriptorCIF() !== $expedienteUpdated->getPrescriptorCIF()) ?
            $this->muroService->changeStatusWall(
                $expedienteUpdated,
                'prescriptor',
                $user,
                $expedienteClone->getClienteNIF(),
                $expedienteUpdated->getClienteNIF()
            )
            : null;


        // change Office
        ($expedienteClone->getOficina() !== $expedienteUpdated->getOficina()) ?
            $this->muroService->changeStatusWall(
                $expedienteUpdated,
                'oficina',
                $user,
                $expedienteClone->getOficina(),
                $expedienteUpdated->getOficina()
            )
            : null;

        // change Vertical
        ($expedienteClone->getVertical() !== $expedienteUpdated->getVertical()) ?
            $this->muroService->changeStatusWall(
                $expedienteUpdated,
                'vertical',
                $user,
                $expedienteClone->getVertical(),
                $expedienteUpdated->getVertical()
            )
            : null;

        // change type Product
        ($expedienteClone->getProductoID() !== $expedienteUpdated->getProductoID()) ?
            $this->muroService->changeStatusWall(
                $expedienteUpdated,
                'producto',
                $user,
                (!is_null($expedienteClone->getProductoID()) ? $expedienteClone->getProductoID()->getNombre() : null),
                (!is_null($expedienteUpdated->getProductoID()) ? $expedienteUpdated->getProductoID()->getNombre() : null)
            )
            : null;

        // change Responsible Pipeline
        ($expedienteClone->getResponsable() !== $expedienteUpdated->getResponsable()) ?
            $this->muroService->changeStatusWall(
                $expedienteUpdated,
                'responsable',
                $user,
                (!is_null($expedienteClone->getResponsable()) ? $expedienteClone->getResponsable()->getRegNumber() : null),
                (!is_null($expedienteUpdated->getResponsable()) ? $expedienteUpdated->getResponsable()->getRegNumber() : null)
            )
            : null;

        // change Internal Manager Pipeline
        ($expedienteClone->getResponsableGestorInterno() !== $expedienteUpdated->getResponsableGestorInterno()) ?
            $this->muroService->changeStatusWall(
                $expedienteUpdated,
                'responsable interno',
                $user,
                (!is_null($expedienteClone->getResponsableGestorInterno()) ? $expedienteClone->getResponsableGestorInterno()->getRegNumber() : null),
                (!is_null($expedienteUpdated->getResponsableGestorInterno()) ? $expedienteUpdated->getResponsableGestorInterno()->getRegNumber() : null)
            )
            : null;

        // change External Manager Pipeline
        ($expedienteClone->getResponsableGestorExterno() !== $expedienteUpdated->getResponsableGestorExterno()) ?
            $this->muroService->changeStatusWall(
                $expedienteUpdated,
                'responsable externo',
                $user,
                (!is_null($expedienteClone->getResponsableGestorExterno()) ? $expedienteClone->getResponsableGestorExterno()->getRegNumber() : null),
                (!is_null($expedienteUpdated->getResponsableGestorExterno()) ? $expedienteUpdated->getResponsableGestorExterno()->getRegNumber() : null)
            )
            : null;
        // change state
        ($expedienteClone->getStatus() !== $expedienteUpdated->getStatus()) ?
            $this->muroService->changeStatusWall(
                $expedienteUpdated,
                'estado',
                $user,
                (!is_null($expedienteClone->getStatus()) ? $expedienteClone->getStatus()->getStatus() : null),
                (!is_null($expedienteUpdated->getStatus()) ? $expedienteUpdated->getStatus()->getStatus() : null)
            )
            : null;

        // change canal
        ($expedienteClone->getCanal() !== $expedienteUpdated->getCanal()) ?
            $this->muroService->changeStatusWall(
                $expedienteUpdated,
                'canal',
                $user,
                (!is_null($expedienteClone->getCanal()) ? $expedienteClone->getCanal()->getCanalDesc() : null),
                (!is_null($expedienteUpdated->getCanal()) ? $expedienteUpdated->getCanal()->getCanalDesc() : null)
            )
            : null;

        // change is a line
        ($expedienteClone->getEslinea() !== $expedienteUpdated->getEslinea()) ?
            $this->muroService->changeStatusWall(
                $expedienteUpdated,
                'es linea',
                $user,
                $expedienteClone->getEslinea(),
                $expedienteUpdated->getEslinea()
            )
            : null;

        // change is a line
        ($expedienteClone->getObservaciones() !== $expedienteUpdated->getObservaciones() ) ?
            $this->muroService->changeStatusWall(
                $expedienteUpdated,
                'observaciones',
                $user,
                $expedienteClone->getObservaciones(),
                $expedienteUpdated->getObservaciones()
            )
            : null;
    }

    /**
     * Validate num Coti SAP
     *
     * @param Request $request
     *
     * @return Response
     *
     * @throws \Exception
     */
    public function validateCoti(Request $request): Response
    {
        $result = $request->request->get('idExp')
                    ? $this->SAPConnectorService->WSCheckNumCoti($request->request->get('quote_id'), $request->request->get('idExp'))
                    : $this->SAPConnectorService->WSCheckNumCoti($request->request->get('quote_id'));

        if ('KO' === $result) {
            return new Response('KO');
        }

        if ($request->request->get('idExp')) {
            $coti = $this->getDoctrine()->getRepository(ComercialCotizacion::class)
                ->findOneBy(['numCoti' => $request->request->get('quote_id')]);
            if (!is_null($coti) && is_null($coti->getFechaTarea())) {
                try {
                    $fechaTarea = $request->request->get('fecha_tarea_peti_coti');
                    $coti->setFechaTarea($fechaTarea);
                    $coti->setUpdatedAt(new \DateTime('now'));
                    $this->em->persist($coti);
                    $this->em->flush();
                } catch (\Exception $e) {
                    $this->logger->error($e->getMessage());
                }
            }
        }

        // @TODO: Const vars with error list
        return new Response('OK');
    }

    /**
     * Validate num Coti SAP
     *
     * @param Request $request
     *
     * @return Response
     *
     * @throws \Exception
     */
    public function validateLine(Request $request): JsonResponse
    {
        if ('dev' === getenv('APP_ENV')) {
            $response = [
                'linea'  => $request->request->get('lineNumber'),
                'estado' => 'estado',
                'limite' => '1000000',
                'disp'   => '1200000',
                'fvenc'  => null,
            ];
        } else {
            $result = $this->SAPConnectorService->WSCheckNumLinea($request->request->get('lineNumber'));

            if (empty($result)) {
                return new JsonResponse('KO');
            }

            $fechaVenc = $result['sDocVta']['fVctoLin'];
            if (!is_null($fechaVenc) && '0000-00-00' !== $fechaVenc) {
                $fechaVenc = \DateTime::createFromFormat('Y-m-d', $result['sDocVta']['fVctoLin']);
                $fechaVenc->setTime(0, 0, 0);
            } else {
                $fechaVenc = null;
            }
            $response = [
                'linea'  => $request->request->get('lineNumber'),
                'estado' => $result['sDocVta']['estadoTxt'],
                'limite' => $result['sCondEco']['inversion']['limite'],
                'disp'   => $result['sCondEco']['inversion']['disponible'],
                'fvenc'  => (is_null($fechaVenc) ? null : $fechaVenc->format('d/m/Y')),
            ];
        }

        return new JsonResponse($response);
    }

    /**
     * Importar expediente
     *
     * @param Request $request
     *
     * @return Response
     */
    public function importExpediente(Request $request): Response
    {
        $idPadre = $request->request->get('idPadre');
        $idHijo  = $request->request->get('idHijo');

        try {
            $this->getDoctrine()->getRepository(ComercialExpedienteCotizaciones::class)->updateJoinExpedientes($idPadre, $idHijo);

            return new Response();
        } catch (\Exception $exception) {
            return new Response($exception->getMessage(), $exception->getCode());
        }
    }


    /**
     * Añadir expediente a favoritos
     *
     * @param Request $request
     *
     * @return Response
     *
     * @throws \Exception
     */
    public function addExpedienteFavoritos(Request $request): Response
    {
        $user               = $this->getUser();
        $expediente         = $this->getDoctrine()->getRepository(ComercialExpediente::class)->find($request->request->get('idExp'));
        $expedienteFavorito = $this->getDoctrine()->getRepository(ComercialExpedienteFavoritos::class)->findBy(['expediente' => $expediente, 'user' => $user]);
        if (!empty($expedienteFavorito)) {
            $user->removeExpedientesFavorito($expedienteFavorito[0]);
        } else {
            $expedienteFavorito = new ComercialExpedienteFavoritos();
            $expedienteFavorito->setExpediente($expediente);
            $expedienteFavorito->setCreatedAt(new \DateTime('now'));
            $user->addExpedientesFavorito($expedienteFavorito);
        }

        try {
            $this->em->persist($user);
            $this->em->flush();

            return new Response();
        } catch (\Exception $e) {
            return new Response($e->getMessage());
        }
    }

    /**
     * Borrar expediente safe delete
     *
     * @param Request $request
     *
     * @return Response
     *
     * @throws \Exception
     */
    public function deleteExpediente(Request $request): Response
    {
        $user       = $this->getUser();
        $expediente = $this->getDoctrine()->getRepository(ComercialExpediente::class)->find($request->request->get('idExp'));
        $expediente->setDeletedAt(new \DateTime('now'));
        $expediente->setDeletedBy($user);

        try {
            $this->em->persist($expediente);
            $this->em->flush();

            return new Response();
        } catch (\Exception $e) {
            return new Response($e->getMessage());
        }
    }

    /**
     * Buscar comerciales de un responsable
     *
     * @param Request $resquest
     *
     * @return Response
     */
    public function findResponsableComercial(Request $resquest): JsonResponse
    {
        $comerciales = $this->getDoctrine()->getRepository(CommercialResponsable::class)
            ->findBy(['Responsable' => $resquest->request->get('responsable')]);
        $users       = [];
        $response    = [];
        foreach ($comerciales as $comercial) {
            array_push($users, $comercial->getCommercial());
        }
        $objResponse = $this->getDoctrine()->getRepository(User::class)
            ->findBy(['regNumber' => $users]);

        foreach ($objResponse as $user) {
            $singleUser['name'] = '('.$user->getRegNumber().') '.$user->getName().' '.$user->getSurname();
            $singleUser['id']   = $user->getId();
            array_push($response, $singleUser);
        }

        return new JsonResponse(json_encode($response));
    }

    /**
     * Añadir cotización a favoritos
     *
     * @param Request $request
     *
     * @return Response
     *
     * @throws \Exception
     */
    public function addCotiFavoritos(Request $request): Response
    {
        $user               = $this->getUser();
        $cotizacion         = $request->request->get('numCoti');
        $cotizacionFavorito = $this->getDoctrine()->getRepository(ComercialCotizacionFavoritos::class)->findOneBy(['cotizacion' => $cotizacion, 'user' => $user]);
        if (!is_null($cotizacionFavorito)) {
            $user->removeExpedientesFavorito($cotizacionFavorito);
        } else {
            $cotizacionFavorito = new ComercialCotizacionFavoritos();
            $cotizacionFavorito->setCotizacion($cotizacion);
            $cotizacionFavorito->setCreatedAt(new \DateTime('now'));
            $user->addCotizacionesFavorita($cotizacionFavorito);
        }

        try {
            $this->em->persist($user);
            $this->em->flush();

            return new Response();
        } catch (\Exception $e) {
            return new Response($e->getMessage());
        }
    }

    /**
     * Desvincular cotizacion de expediente
     *
     * @param Request $request
     *
     * @return Response
     */
    public function desvincularCoti(Request $request): Response
    {
        $join = $this->getDoctrine()->getRepository(ComercialExpedienteCotizaciones::class)
            ->findOneBy(
                [
                    'expedienteID' => $request->request->get('idExp'),
                    'cotizacion'   => $request->request->get('numCoti'),
                ]
            );
        if (!is_null($join)) {
            try {
                $this->em->remove($join);
                $this->em->flush();
            } catch (\Exception $e) {
                $error['code']    = $e->getCode();
                $error['message'] = $this->translator->trans('Something went wrong');

                return new JsonResponse($error);
            }
        }

        return new Response();
    }

    /**
     * Get the badge color for calendar view
     *
     * @param string|null $date
     * @param int|null    $chance
     *
     * @return string
     */
    public function getBadgeCalendarColor(?string $date = null, ?int $chance = null): string
    {
        // Ignore nulls and return Info class
        if ('' === $date or null === $date or null === $chance) {
            return $this->badgeInfoClass;
        }

        // If something went wrong setting time, return Info class
        try {
            $today = new \DateTime('now');
            $today->setTime(12, 0, 0)->setDate(intval($today->format('Y')), intval($today->format('m')), 1);
            $dateExp = \DateTime::createFromFormat('m/Y', $date);
            $dateExp->setTime(12, 0, 0)->setDate(intval($dateExp->format('Y')), intval($dateExp->format('m')), 1);
            $todayDay = new \DateTime('now');
            $todayDay = $todayDay->format('d');
        } catch (\Exception $e) {
            return $this->badgeInfoClass;
        }

        // Aplies the logic
        if ($today > $dateExp) {
            return $this->badgeDangerClass;
        }
        if ($today == $dateExp and  100 !== $chance) {
            if (25 < $todayDay and 50 > $chance) {
                return $this->badgeDangerClass;
            }
            if (25 < $todayDay and 75 === $chance) {
                return  $this->badgeWarningClass;
            }
            if (20 < $todayDay and 50 === $chance) {
                return $this->badgeWarningClass;
            }
            if (15 < $todayDay and 25 === $chance) {
                return $this->badgeDangerClass;
            }
            if (10 < $todayDay and 25 === $chance) {
                return $this->badgeWarningClass;
            }
        }

        return $this->badgeSuccessClass;
    }

    /**
     * @param Request $request
     *
     * @return Response
     *
     * @throws \Exception
     */
    public function updateExpedienteCotisInfo(Request $request): Response
    {
        $expediente = $this->getDoctrine()->getRepository(ComercialExpediente::class)->find($request->request->get('idExp'));
        $cotis      = $expediente->getCotizaciones();
        foreach ($cotis as $coti) {
            try {
                $this->SAPConnectorService->WSCheckNumCoti($coti->getCotizacion());
            } catch (\Exception $e) {
                // TODO: Errors revision
            }
        }
        if ($expediente->getEsLinea()) {
            $disposiciones = $this->getDoctrine()->getRepository(ComercialExpediente::class)->findBy(['expedientePadre' => $expediente]);
            foreach ($disposiciones as $dispo) {
                $cotisDispo = $dispo->getCotizaciones();
                foreach ($cotisDispo as $coti) {
                    try {
                        $this->SAPConnectorService->WSCheckNumCoti($coti->getCotizacion());
                    } catch (\Exception $e) {
                        // TODO: Errors revision
                    }
                }
            }
        }

        return new Response();
    }

    /**
     * Update cotización info
     *
     * @param Request $request
     *
     * @return Response
     */
    public function updateCotiInfo(Request $request): Response
    {
        $coti = $request->request->get('numCoti');

        // TODO: Update!
        return new Response($coti);
    }

    /**
     * @param Request            $request
     * @param ExcelExportService $excelService
     *
     * @return Response
     *
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function exportExcel(Request $request, ExcelExportService $excelService): Response
    {
        $filter            = [];
        $statusID          = [];
        $zonesFilter       = [];
        $responsibleFilter = [];
        $nameFilter        = null;

        $filter['f_responsables'] = [];
        $filter['f_estados']      = [];
        foreach ($request->request->get('form') as $formRequest) {
            switch ($formRequest['name']) {
                case 'f_responsables[]':
                    array_push($filter['f_responsables'], $formRequest['value']);
                    break;
                case 'f_estados[]':
                    array_push($filter['f_estados'], $formRequest['value']);
                    break;
                default:
                    $filter[$formRequest['name']] = $formRequest['value'];
            }
        }

        /* Apply filters  */
        if (!empty($filter['f_estados']) and !is_null($filter['f_estados'])) {
            foreach ($filter['f_estados'] as $estado) {
                array_push($statusID, $estado);
            }
        }
        if (!empty($filter['f_zona']) and !is_null($filter['f_zona'])) {
            foreach ($filter['f_zona'] as $zona) {
                array_push($zonesFilter, $zona);
            }
        }
        if (!empty($filter['f_responsables']) and !is_null($filter['f_responsables'])) {
            foreach ($filter['f_responsables'] as $responsable) {
                array_push($responsibleFilter, $responsable);
            }
        } elseif (is_null($filter['filtroGlobal'])) {
            array_push($responsibleFilter, $this->getUser()->getId());
        }
        if (!empty($request->request->get('f_name')) and !is_null($request->request->get('f_name'))) {
            $nameFilter = $request->request->get('f_name');
        }

        /* Send filter Data Pipelines */
        $pipelines = $this->getDoctrine()
                ->getRepository(ComercialExpediente::class)
                ->findByExpedienteForm($statusID, $responsibleFilter, $nameFilter, $zonesFilter, !$request->request->get('isReport'));

        /* create excel file with Service*/
        $file = $excelService->createNewDocumentPipelines($pipelines->getResult(), $this->getUser());

        return new JsonResponse(['file' => $file]);
    }

    /**
     * @param Request            $request
     * @param ExcelExportService $excelService
     *
     * @return Response
     */
    public function getDocument(Request $request, ExcelExportService $excelService): Response
    {
        $logged = (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY') && !$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED')) ? false : true;
        // Get the file
        if ($logged) {
            $file        = $this->getUser().'.xlsx';
            $objDocument = '../'.getenv('FILES_UPLOAD').$excelService::TMPFOLDER.$file;
            if ($file) {
                try {
                    return new BinaryFileResponse($objDocument, 200, ['Content-Type', $excelService::MIMETYPES['xlsx'], 'Content-Disposition' => 'attachment; filename="'.$file.'"']);
                } catch (\Exception $e) {
                    return new Response('ko');
                }
            }
        }

        return new Response('ko');
    }
}
