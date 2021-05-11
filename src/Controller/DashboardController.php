<?php
/**
 * Controlador para el dashboard de Area de comerciales
 */
namespace App\Controller;

// ENTITIES
use App\Entity\DashboardWidgets;
use App\Entity\Groups;
use App\Entity\NotificationsTo;
use App\Entity\User;
use App\Entity\WidgetUserOrden;

// BUNDLES
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

// COMPONENTS
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

// UTILS
use App\Utils\Widgets;

use Symfony\Component\Security\Core\Role\Role;
use Symfony\Component\Security\Core\Role\RoleHierarchy;

/**
 * Class DashboardController
 */
class DashboardController extends AbstractController
{
    /**
     * Utils widgets
     * @var Widgets
     */
    private $widgets;

    /**
     * DashboardController constructor.
     * @param Widgets $widgets
     */
    public function __construct(Widgets $widgets)
    {
        $this->widgets = $widgets;
    }

    /**
     * Devuelve ordenados los widgets para el menú principal de la aplicación
     * @return Response
     */
    public function index(): Response
    {
        // USER VARS //
        $userId        = $this->getUser()->getId();
        $user          = $this->getUser();
        $name          = $user->getName();
        $roleId        = $user->getRole()->getId();
        $roleName      = $user->getRole()->getRoleName();
        $originalRoles = $this->getRoles($roleName);
        $padre         = null;
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
            $notificationsNavUser  = $this->getDoctrine()->getRepository(NotificationsTo::class)->findBy(['groupMessage' => false, 'user' => $this->getUser()->getId(), 'seen' => false]);
            $notificationsNavGroup = $this->getDoctrine()->getRepository(NotificationsTo::class)->findBy(['user' => $this->getUser()->getId(), 'groupMessage' => true, 'seen' => false]);
        }
        // END USER NOTIFICATIONS //

        $widgets = $this->widgets->getAction($padre, $roleId, $originalRoles);
        foreach ($widgets as &$widget) {
            $widget->setOrden($this->checkOrden($widget->getId(), $userId));
        }

        usort($widgets, function ($a, $b) {
            return $a->getOrden() > $b->getOrden();
        });

        return $this->render(
            'dashboard/dashboard.html.twig',
            ['widgets' => $widgets, 'username' => $name, 'role' => $roleId, 'role_name' => $roleName, 'allUsers' => $allUsers, 'allGroups' => $allGroups, 'notifications_user' => $notificationsNavUser, 'notifications_group' => $notificationsNavGroup]
        );
    }

    /**
     * Comprueba el orden de los widgets para un usuario
     * @param int $widgetId
     * @param int $userId
     *
     * @return mixed
     */
    public function checkOrden(int $widgetId, int $userId)
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

    /**
     * Actualiza el orden de los widgets mediante el formulario de ordenar widgets
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
                    $widget     = $this->getDoctrine()->getRepository(DashboardWidgets::class)->findOneBy(['id' => $key]);
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
     * Vista listar_peticiones
     * @return Response
     */
    public function listarPeticiones(): Response
    {
        return $this->render('control-documental/listar_peticiones.html.twig');
    }

    /**
     * Obtienes los roles heredados de un rol para comprobar sus permisos
     * @param string $role
     *
     * @return array
     */
    private function getRoles(string $role): array
    {
        //$hierarchy   = $this->container->getParameter('security.role_hierarchy.roles');
        $hierarchy     = $this->getParameter('security.role_hierarchy.roles');
        $roleHierarchy = new RoleHierarchy($hierarchy);
        $roles         = $roleHierarchy->getReachableRoles([new Role($role)]);

        return array_map(function (Role $role) {
            return $role->getRole();
        }, $roles);
    }
}
