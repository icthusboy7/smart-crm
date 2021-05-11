<?php
/**
 * Controlador Home, funciones comunes de controladores
 */
namespace App\Controller;

// ENTITIES
use App\Entity\DashboardWidgets;
use App\Entity\Groups;
use App\Entity\NotificationsTo;
use App\Entity\User;
use App\Entity\WidgetUserOrden;

use App\Utils\Widgets;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;

// COMPONENTS
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Role\Role;
use Symfony\Component\Security\Core\Role\RoleHierarchy;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class HomeController
 * @package App\Controller
 */
class HomeController extends AbstractController
{
    /**
     * Utils widgets
     * @var Widgets
     */
    private $widgets;

    /**
     * @var UrlGeneratorInterface
     */
    private $router;

    /**
     * @var ParameterBagInterface
     */
    private $params;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * HomeController constructor.
     * @param Widgets               $widgets
     * @param UrlGeneratorInterface $router
     * @param ParameterBagInterface $params
     * @param TranslatorInterface   $translator
     */
    public function __construct(Widgets $widgets, UrlGeneratorInterface $router, ParameterBagInterface $params, TranslatorInterface $translator)
    {
        $this->widgets    = $widgets;
        $this->router     = $router;
        $this->params     = $params;
        $this->translator = $translator;
    }

    /**
     * Redireccion al login para usuarios no logeados
     * Redireccion al menú principal para usuarios logeados
     * @return RedirectResponse
     */
    public function index(): RedirectResponse
    {
        $url = (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY') && !$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED'))
            ? $this->generateUrl('fos_user_security_login')
            : $this->generateUrl('dashboards');

        return new RedirectResponse($url);
    }

    /**
     * Constructor del layout de la aplicación, recibe un padre para
     * saber los widgets a mostrar en la barra lateral, en caso de NULL, se intuye que
     * debe mostrar los widgets del menu principal
     * @param string|null $padre
     * @param string|null $route
     * @param array|null  $params
     *
     * @return Response
     */
    public function layoutContructor(?string $padre, ?string $route, ?array $params): Response
    {
        /**
         * Languages
         */
        $parameterValue    = $this->params->get('app.locales');
        $language          = explode('|', $parameterValue);
        $params['_locale'] = $language[0];
        $urlEn             = $this->router->generate($route, $params);
        $params['_locale'] = $language[1];
        $urlEs             = $this->router->generate($route, $params);
        $params['_locale'] = $language[2];
        $urlCa             = $this->router->generate($route, $params);
        $params['_locale'] = $language[3];
        $urlPt             = $this->router->generate($route, $params);
        $lang              = [
            0 => [$urlEn, $this->translator->trans('english')],
            1 => [$urlEs, $this->translator->trans('spanish')],
            2 => [$urlCa, $this->translator->trans('catalan')],
            3 => [$urlPt, $this->translator->trans('portuguese')],
        ];

        // USER VARS //
        $userId        = $this->getUser()->getId();
        $user          = $this->getUser();
        $name          = $user->getName();
        $roleId        = $user->getRole()->getId();
        $roleName      = $user->getRole()->getRoleName();
        $originalRoles = $this->getRoles($roleName);

        // END USER VARS //

        // TEMP ALL USERS AND GROUPS FOR NOTIFY TEST //
        $allUsers  = $this->getDoctrine()->getRepository(User::class)->findAll();
        $allGroups = $this->getDoctrine()->getRepository(Groups::class)->findAll();
        // END TEMP //

        // USER NOTIFICATIONS //
        if (get_class($user) === 'App\Entity\Admin') {
            $notificationsNavUser  = null;
            $notificationsNavGroup = null;
        } else {
            $userGroups = $this->getUser()->getMyGroups();
            $groups     = [];
            foreach ($userGroups as $group) {
                array_push($groups, $group->getId());
            }
            $notificationsNavUser  = $this->getDoctrine()->getRepository(NotificationsTo::class)->findBy(
                [
                    'groupMessage' => false,
                    'user'         => $this->getUser()->getId(),
                    'seen'         => false,
                ]
            );
            $notificationsNavGroup = $this->getDoctrine()->getRepository(NotificationsTo::class)->findBy(
                [
                    'user'         => $this->getUser()->getId(),
                    'groupMessage' => true,
                    'seen'         => false,
                ]
            );
        }
        // END USER NOTIFICATIONS //

        $widgets = $this->widgets->getAction($padre, $roleId, $originalRoles);


        foreach ($widgets as &$widget) {
            $widget->setOrden($this->checkOrden($widget->getId(), $userId));
        }

        usort($widgets, function ($a, $b) {
            return $a->getOrden() > $b->getOrden();
        });

        if (isset($padre)) {
            $dashboardWidgets = $this->getDoctrine()->getRepository(DashboardWidgets::class)->findOneBy(['nombre' => $padre]);
            $layoutRoute      = $dashboardWidgets->getRutaInterna();
        } else {
            $layoutRoute = 'homepage';
        }

        return $this->render(
            'default/navbar_top_sideleft.html.twig',
            [
                'widgets'             => $widgets,
                'username'            => $name,
                'role'                => $roleId,
                'role_name'           => $roleName,
                'allUsers'            => $allUsers,
                'allGroups'           => $allGroups,
                'notifications_user'  => $notificationsNavUser,
                'notifications_group' => $notificationsNavGroup,
                'lang'                => $lang,
                'layoutRoute'         => $layoutRoute,
            ]
        );
    }

    /**
     * @param string|null $route
     * @param array       $params
     *
     * @return Response
     */
    public function getBreadcrumb(?string $route, ?array $params): Response
    {
        $dataBreadcrumb = $this->getDataBreadCrumb($route, $params);

        $isWall = ($route === 'comercial_ver_muro_expediente' || $route === 'comercial_ver_muro_tarea' || $route === 'comercial_ver_muro_coti')
            ? true
            : false;

        return $this->render('default/breadcrumb.html.twig', ['breadcrumb' => $dataBreadcrumb, 'isWall' => $isWall ]);
    }

    /**
     * Obtienes los roles heredados de un rol para comprobar sus permisos
     * @param $role
     *
     * @return array
     */
    private function getRoles($role): array
    {
        $hierarchy = $this->getParameter('security.role_hierarchy.roles');
        //$hierarchy = $this->container->getParameter('security.role_hierarchy.roles');
        $roleHierarchy = new RoleHierarchy($hierarchy);
        $roles         = $roleHierarchy->getReachableRoles([new Role($role)]);

        return array_map(function (Role $role) {
            return $role->getRole();
        }, $roles);
    }

    /**
     * Check orden for a widgetId/user logged in
     * @param $widgetId
     * @param $userId
     *
     * @return mixed
     */
    public function checkOrden($widgetId, $userId)
    {

        $widget = $this->getDoctrine()->getRepository(WidgetUserOrden::class)->findOneBy(['user' => $userId, 'widget_dashboard' => $widgetId]);

        if (isset($widget)) {
            $order = $widget->getOrden();
        } else {
            $widget = $this->getDoctrine()->getRepository(DashboardWidgets::class)->findOneBy(['id' => $widgetId]);
            $order  = $widget->getOrden();
        }


        return $order;
    }

    /**
     * Actualiza el orden de los widgets para un usuario
     * @param Request $request
     *
     * @return Response
     */
    public function updateOrden(Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        // Loop start
        $itteration = 1;
        // Get user id and role
        $user     = $this->getUser();
        $userId   = $this->getUser()->getId();
        $userRole = $user->getRole()->getId();
        if ('ROLE_ADMIN' !== $userRole) {
            foreach ($request->request as $key => $value) {
                $widgetUser = $this->getDoctrine()->getRepository(WidgetUserOrden::class)->findOneBy(['user' => $userId, 'widget_dashboard' => $key]);
                if (isset($widgetUser)) {
                    $widgetUser->setOrden($itteration);
                } else {
                    $widget = $this->getDoctrine()->getRepository(DashboardWidgets::class)->findOneBy(['id' => $key]);

                    $widgetUser = new WidgetUserOrden();
                    $widgetUser->setOrden($itteration);
                    $widgetUser->setUser($this->getUser());
                    $widgetUser->setWidgetDashboard($widget);
                }
                $itteration++;
                $entityManager->persist($widgetUser);
                $entityManager->flush();
            }
        }

        return new Response();
    }

    /**
     * Render de acceso denegado
     * @return Response
     */
    public function access_denied(): Response
    {
        return $this->render('errors/access_denied_error.html.twig');
    }

    /**
     * create breadcrumb array
     * @param string     $route
     * @param array|null $params
     *
     * @return array
     */
    private function getDataBreadCrumb(string $route, ?array $params): array
    {
        $arrayBreadCrumb[] = ['url' => 'dashboard_comerciales', 'parameters' => [], 'text' => 'Área comerciales', ];
        switch ($route) {
            case 'comercial_ver_muro_tarea':
                $arrayBreadCrumb[] = ['url' => 'tasks', 'parameters' => [], 'text' => 'task', ];
                break;
            case 'tasks':
                $arrayBreadCrumb[] = ['url' => $route, 'parameters' => [], 'text' => 'task', ];
                break;
            case 'visitForm':
            case 'comercial_visitas':
                $arrayBreadCrumb[] = ['url' => $route, 'parameters' => [], 'text' => 'Visits', ];
                break;
            case 'peticion_cotizacion_form':
            case 'comercial_ver_muro_coti':
            case 'expedientes_form':
                $arrayBreadCrumb[] = ['url' => 'comercial_expedientes', 'parameters' => [], 'text' => 'Lista de expedientes', ];
                break;
            case 'comercial_expedientes':
            case 'comercial_expedientes_calendario':
                $arrayBreadCrumb[] = ['url' => $route, 'parameters' => [],  'text' => 'Lista de expedientes', ];
                break;
            case 'gestion_alertas':
                $arrayBreadCrumb[] = ['url' => $route, 'parameters' => [], 'text' => 'Alerts', ];
                break;
            case 'gestion_oficinas':
                $arrayBreadCrumb[] = ['url' => $route, 'parameters' => [], 'text' => 'Offices', ];
                break;
            case 'comercial_ver_muro_expediente':
                $arrayBreadCrumb[] = ['url' => 'comercial_expedientes', 'parameters' => [], 'text' => 'Lista de expedientes', ];
                $arrayBreadCrumb[] = [
                    'url'        => $route,
                    'parameters' => ['idExpediente' => $params['idExpediente']],
                    'text'       => 'wall',
                ];
                break;
            case 'task_types':
            case 'add_type':
            case 'edit_type':
                $arrayBreadCrumb[] = ['url' => 'task_types', 'parameters' => [], 'text' => 'task types', ];
                break;
        }

        return $arrayBreadCrumb;
    }
}
