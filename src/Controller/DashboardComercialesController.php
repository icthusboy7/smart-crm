<?php
/**
 * Controlador para el dashboard de Area de comerciales
 */
namespace App\Controller;

/* ENTITIES */
use App\Core\Entity\Contact;
use App\Entity\ComercialAlertas;
use App\Entity\ComercialExpediente;
use App\Entity\ComercialTask;
use App\Entity\ComercialTaskType;
use App\Entity\DashboardWidgets;
use App\Entity\MasterOffice;
use App\Entity\NotificationsTo;
use App\Entity\Reason;
use App\Entity\Status;
use App\Entity\Views\Manager;
use App\Entity\Visit;
use App\Entity\WidgetUserOrden;


use App\Utils\Widgets;

use FOS\UserBundle\Model\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Role\Role;
use Symfony\Component\Security\Core\Role\RoleHierarchy;

/**
 * Class DashboardComercialesController
 */
class DashboardComercialesController extends AbstractController
{
    /**
     * Utils widgets
     * @var Widgets
     */
    private $widgets;

    /**
     * DashboardComercialesController constructor.
     * @param Widgets $widgets
     */
    public function __construct(Widgets $widgets)
    {
        $this->widgets = $widgets;
    }

    /**
     * Acceso del Dashboard de comerciales con widgets ordenados
     *
     * @return Response render with extra params
     */
    public function dashboardcomercial(): Response
    {
        $user          = $this->getUser();
        $userId        = $user->getId();
        $roleId        = $user->getRole()->getId();
        $padre         = 'AreaComercial';
        $roleName      = $user->getRole()->getRoleName();
        $originalRoles = $this->getRoles($roleName);

        // NAVBAR
        $name     = $user->getName();
        $roleName = $user->getRole()->getRoleName();

        $widgets = $this->widgets->getAction($padre, $roleId, $originalRoles);

        // USER NOTIFICATIONS //
        if (get_class($user) === 'App\Entity\Admin') {
            $notificationsNavUser  = null;
            $notificationsNavGroup = null;
        } else {
            $userGroups = $this->getUser()->getMyGroups();

            $groups = [];
            foreach ($userGroups as $group) {
                array_push($groups, $group->getId());
            }
            $notificationsNavUser  = $this->getDoctrine()->getRepository(NotificationsTo::class)->findBy(['groupMessage' => false, 'user' => $this->getUser()->getId(), 'seen' => false]);
            $notificationsNavGroup = $this->getDoctrine()->getRepository(NotificationsTo::class)->findBy(['user' => $this->getUser()->getId(), 'groupMessage' => true, 'seen' => false]);
        }
        // END USER NOTIFICATIONS //

        foreach ($widgets as &$widget) {
            $widget->setOrden($this->checkOrden($widget->getId(), $userId));
        }

        usort($widgets, function ($a, $b) {
            return $a->getOrden() > $b->getOrden();
        });
        $visitas     = $this->twigVisitas();
        $contactos   = $this->twigContactos();
        $oficinas    = $this->twigOficinas();
        $tasks       = $this->twigTasks($user);
        $taskTypes   = $this->twigTaskTypes();
        $expedientes = $this->twigPipelines();
        $alertas     = $this->twigAlertas();

        return $this->render('area-comercial/dashboard_comercial.html.twig', [
            'widgets'             => $widgets,
            'role'                => $roleId,
            'username'            => $name,
            'role_name'           => $roleName,
            'notifications_user'  => $notificationsNavUser,
            'notifications_group' => $notificationsNavGroup,
            'visitas'             => $visitas,
            'contactos'           => $contactos,
            'oficinas'            => $oficinas,
            'tasks'               => $tasks,
            'task_types'          => $taskTypes,
            'alertas'             => $alertas,
            'expedientes'         => $expedientes,
        ]);
    }

    /**
     * Vista visitas
     * @param string $filtro
     *
     * @return Response
     */
    public function indexVisitas(string $filtro = ''): Response
    {
        return $this->render(
            'area-comercial/listar_visitas.html.twig',
            [
                'managers' => $this->getManagers(),
                'states'   => $this->getStates(),
            ]
        );
    }

    /**
     * Vista Expedientes
     * @param string $filtro
     *
     * @return Response
     */
    public function indexExpedientes(string $filtro = 'todas'): Response
    {
        return $this->render('area-comercial/listar_expedientes.html.twig');
    }

    /**
     * Vista de Operaciones
     *
     * @return Response
     */
    public function indexOperaciones(): Response
    {
        return $this->render('area-comercial/listar_operaciones.html.twig');
    }

    /**
     * Revisa el orden de un widget para un usuario y devuelve el orden que tiene establecido
     * el usuario en concreto, si no tiene ningún orden personal, devuelve el orden por defecto
     * de la tabla widgets
     * @param int $widgetId
     * @param int $userId
     *
     * @return mixed
     */
    public function checkOrden(int $widgetId, int $userId): string
    {

        $widget = $this->getDoctrine()->getRepository(WidgetUserOrden::class)->findOneBy(['user' => $userId, 'widget_dashboard' => $widgetId]);

        if (isset($widget)) {
            $orden = $widget->getOrden();
        } else {
            $widget = $this->getDoctrine()->getRepository(DashboardWidgets::class)->findOneBy(['id' => $widgetId]);
            $orden  = $widget->getOrden();
        }

        return $orden;
    }

    /* CONTROLADORES PARA WIDGETS CON TWIGS AÑADIDOS */

    /**
     * Función especial para llenar el twig adjuntado a oficinas para el dashboard de AreaComercial
     * @return object[]
     */
    public function twigOficinas(): array
    {
        $oficinas = $this->getDoctrine()->getRepository(MasterOffice::class)->findBy([], null, 3);

        return $oficinas;
    }

    /**
     * Función especial para llenar el twig adjuntado a oficinas para el dashboard de AreaComercial
     * @return object[]
     */
    public function twigContactos(): array
    {
        $contactos = $this->getDoctrine()->getRepository(Contact::class)->findBy([], null, 4);

        return $contactos;
    }

    /**
     * Función especial para llenar el twig adjuntado a Alertas para el dashboard de AreaComercial
     *
     * @return object[]
     */
    public function twigAlertas(): array
    {
        $alertas = $this->getDoctrine()->getRepository(ComercialAlertas::class)->findBy([], ['nivel' => 'DESC'], 4);

        return $alertas;
    }

    /**
     * Función vacía para usar en el twig de gestiones
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function twigGestiones(): Response
    {
        // TODO: Funcion para el twigGestiones, actualmente no tenemos información de este widget
        return new Response();
    }

    /**
     * Función vacía para usar en el twig de pipelines
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function twigPipelines(): array
    {
        // TODO: Funcion para el twigPipelines, actualmente no tenemos información de este widget
        return $this->getDoctrine()->getRepository(ComercialExpediente::class)
            ->findBy(['expedientePadre' => null, 'esDisposicion' => 0, 'deletedAt' => null], ['updatedAt' => 'DESC'], 3);
    }

    /**
     * Devuelve un array formateado para el twig de visitas de la vista de widgets
     * @return array
     */
    public function twigVisitas(): array
    {
        $visitas         = $this->getDoctrine()->getRepository(Visit::class)->findBy([], ['status' => 'ASC'], 4);
        $visitasFormated = [];
        foreach ($visitas as $visita) {
            if ($visita->getCustomerID()) {
                $customers = $this->getCustomer($visita->getCustomerID());
                if ($customers) {
                    $oficina = $customers[0]['text'];
                }
            }
            if ($visita->getProviderID()) {
                $providers = $this->getProvider($visita->getProviderID());
                if ($providers) {
                    $oficina = $providers[0]['text'];
                }
            }

            if ($visita->getOffice()) {
                $oficina = $this->getDoctrine()->getRepository(MasterOffice::class)->findOneBy(['codigo' => $visita->getOffice()]);
                if (isset($oficina)) {
                    $oficina = $oficina->getNombre();
                }
            }
            $asunto = $this->getDoctrine()->getRepository(Reason::class)->findOneBy(['id' => $visita->getReason()])->getName();
            $status = $visita->getStatus();
            array_push($visitasFormated, ['oficina' => $oficina, 'asunto' => $asunto, 'status' => $status]);
        }

        return $visitasFormated;
    }

    /**
     * Devuelve un array formateado para el twig de tareas de la vista de widgets
     *
     * @param UserInterface $user
     *
     * @return array
     */
    public function twigTasks(UserInterface $user): array
    {
        $tasks = $this->getDoctrine()->getRepository(ComercialTask::class)->findAllWithExpedientes([
            'idUsuario' => $user->getId(),
            'limit'     => 5,
        ]);

        return $tasks;
    }

    /**
     * @return array
     */
    public function twigTaskTypes(): array
    {
        return $this->getDoctrine()->getRepository(ComercialTaskType::class)
            ->findBy(
                ['deletedAt' => null],
                ['id' => 'asc'],
                5
            );
    }

    /**
     * Busca los clientes para llenar el select2, en caso
     * de no encontrar ninguno, buscara tambien en contactos
     * a no ser que reciba un false como segunda variable
     *
     * @param string $query
     * @param bool   $withContact
     *
     * @return mixed
     */
    public function getCustomer(string $query, bool $withContact = true)
    {
        $customers = $this->getDoctrine()->getRepository(Contact::class)->findCustomer($query, 1);
        if (true === !$customers && $withContact) {
            $customers = $this->getDoctrine()->getRepository(Contact::class)->findContactsSelect($query, 1);
        }

        return $customers;
    }

    /**
     * Busca los proveedores para llenar el select2,
     * en caso de no encontrar ninguno, buscara tambien en contactos
     * a no ser que reciba un false como segunda variable
     *
     * @param string $query
     * @param bool   $withContact
     *
     * @return mixed
     */
    public function getProvider(string $query, bool $withContact = true)
    {
        $providers = $this->getDoctrine()->getRepository(Contact::class)->findProvider($query, 1);
        if (true === !$providers && $withContact) {
            $providers = $this->getDoctrine()->getRepository(Contact::class)->findContactsSelect($query, 1);
        }

        return $providers;
    }


    /**
     * Returns all the users that are managers ordered by name.
     *
     * @return array        Users array
     */
    private function getManagers(): array
    {
        $em         = $this->getDoctrine();
        $repository = $em->getRepository(Manager::class);

        return $repository->findBy([], ['name' => 'DESC']);
    }


    /**
     * Returns all the possible visit statuses ordered by ID.
     *
     * @return array        Status array
     */
    private function getStates(): array
    {
        $em         = $this->getDoctrine();
        $repository = $em->getRepository(Status::class);

        return $repository->findBy([], ['id' => 'ASC']);
    }


    /**
     * Obtiene los roles heredados de un rol
     * @param string $role
     *
     * @return array
     */
    private function getRoles(string $role): array
    {
        $hierarchy = $this->getParameter('security.role_hierarchy.roles');
        //$hierarchy = $this->container->getParameter('security.role_hierarchy.roles');
        $roleHierarchy = new RoleHierarchy($hierarchy);
        $roles         = $roleHierarchy->getReachableRoles([new Role($role)]);

        return array_map(function (Role $role) {
            return $role->getRole();
        }, $roles);
    }
}
