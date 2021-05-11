<?php
/**
 * Controlador de notificaciones y alertas (no Rabbit)
 */
namespace App\Controller;

use \DateTime;
use \Exception;

// ENTITIES
use App\Entity\Groups;
use App\Entity\Notifications;
use App\Entity\NotificationsTo;

// CONTAINERS
use App\Entity\User;

// COMPONENTS
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

// Doctrine
use Doctrine\Common\Persistence\ObjectRepository;

// BUNDLES
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class NotificationsController
 */
class NotificationsController extends AbstractController
{
    /**
     * Envio de notificación directo para base de datos
     * @param Request $request
     *
     * @return Response
     *
     * @throws Exception
     */
    public function sendNotification(Request $request): Response
    {

        $notificationType = $this->getNotificationsRepository()->findOneBy(['id' => $request->request->get('notification_type')]);

        if (null !== $request->request->get('user_to_notify') and 'User' !== $request->request->get('user_to_notify')) {
            $notificationUser = $this->getDoctrine()->getRepository(User::class)->findOneBy(['id' => $request->request->get('user_to_notify')]);
            $this->sendNotificationUser($notificationUser, $notificationType);
        }
        if (!is_null($request->request->get('group_to_notify')) and 'Group' !== $request->request->get('group_to_notify')) {
            $this->sendNotificationGroup(true, $notificationType);
        }

        return new Response();
    }

    /**
     * Envio de notificacion a usuario
     *
     * @param User          $user
     * @param Notifications $notificationType
     *
     * @throws Exception
     */
    public function sendNotificationUser(User $user, Notifications $notificationType): void
    {
        $entityManager = $this->getDoctrine()->getManager();
        $notification  = new NotificationsTo();
        $notification->setCreatedAt(new DateTime('now'));
        $notification->setUpdatedAt(new DateTime('now'));
        $notification->setNotification($notificationType);
        $notification->setUser($user);
        $notification->setGroupMessage(false);
        $notification->setSeen(false);
        $notification->setFlagSeen(false);

        $entityManager->persist($notification);
        $entityManager->flush();
    }

    /**
     * Envio de notificacion a grupo
     *
     * @param bool          $group
     * @param Notifications $notificationType
     *
     * @throws Exception
     */
    public function sendNotificationGroup(bool $group, Notifications $notificationType): void
    {
        $entityManager = $this->getDoctrine()->getManager();
        $groupUsers    = $this->getDoctrine()->getRepository(Groups::class)->find($group)->getUsers();
        foreach ($groupUsers as $user) {
            $notification = new NotificationsTo();
            $notification->setCreatedAt(new DateTime('now'));
            $notification->setUpdatedAt(new DateTime('now'));
            $notification->setNotification($notificationType);
            $notification->setGroupMessage($group);
            $notification->setUser($user);
            $notification->setSeen(false);
            $notification->setFlagSeen(false);
            $entityManager->persist($notification);
            $entityManager->flush();
        }
    }

    /**
     * Devuelve todas las notificaciones individuales para el usuario logeado
     * Renderiza la vista de gestion de notificaciones de individuales
     * @return Response
     */
    public function showUserNotifications(): Response
    {
        // TEMP ALL USERS AND GROUPS FOR NOTIFY TEST //
        $allUsers          = $this->getDoctrine()->getRepository(User::class)->findAll();
        $allGroups         = $this->getDoctrine()->getRepository(Groups::class)->findAll();
        $notificationsType = $this->getNotificationsRepository()->findAll();
        // END TEMP //

        // USER NOTIFICATIONS //
        if ('ROLE_ADMIN' === $this->getUser()->getRole()->getRoleName()) {
            $notificationsUser     = null;
            $notificationsNavUser  = null;
            $notificationsNavGroup = null;
        } else {
            // NAV BAR NOTIFICATIONS //
            $notificationsNavUser  = $this->getNotificationsToRepository()
                ->findBy(['groupMessage' => false, 'user' => $this->getUser()->getId(), 'seen' => false]);
            $notificationsNavGroup = $this->getNotificationsToRepository()
                ->findBy(['user' => $this->getUser()->getId(), 'groupMessage' => true, 'seen' => false]);

            // ALL NOTIFICATIONS //
            $notificationsUser = $this->getNotificationsToRepository()
                ->findBy(['user' => $this->getUser()->getId(), 'groupMessage' => false]);

            foreach ($notificationsUser as $notification) {
                preg_match("/<a\shref=\"(.*)\"/", $notification->getNotification()->getDescription(), $url);

                $urlNoti = isset($url[1]) ? $url[1] : '';

                $notification->getNotification()->setUrl($urlNoti);

            }
        }
        // END USER NOTIFICATIONS //

        return $this->render(
            'notificaciones/notificaciones_usuario.html.twig',
            ['allUsers' => $allUsers, 'allGroups' => $allGroups, 'allNotTypes' => $notificationsType, 'notifications_group' => $notificationsNavGroup, 'notifications_user' => $notificationsNavUser, 'allnotifyuser' => $notificationsUser]
        );
    }

    /**
     * Devuelve todas las notificaciones de grupo para el usuario logeado
     * Renderiza la vista de gestion de notificaciones de grupo
     * @return Response
     */
    public function showGroupNotifications(): Response
    {
        // TEMP ALL USERS AND GROUPS FOR NOTIFY TEST //
        $allUsers          = $this->getDoctrine()->getRepository(User::class)->findAll();
        $allGroups         = $this->getDoctrine()->getRepository(Groups::class)->findAll();
        $notificationsType = $this->getNotificationsRepository()->findAll();
        // END TEMP //

        // USER NOTIFICATIONS //
        if ('ROLE_ADMIN' === $this->getUser()->getRole()->getRoleName()) {
            $notificationsGroup    = null;
            $notificationsNavUser  = null;
            $notificationsNavGroup = null;
        } else {
            // NAV BAR NOTIFICATIONS //
            $notificationsNavUser  = $this->getNotificationsToRepository()
                ->findBy(['groupMessage' => false, 'user' => $this->getUser()->getId(), 'seen' => false]);
            $notificationsNavGroup = $this->getNotificationsToRepository()
                ->findBy(['user' => $this->getUser()->getId(), 'groupMessage' => true, 'seen' => false]);

            // ALL NOTIFICATIONS //
            $notificationsGroup = $this->getNotificationsToRepository()
                ->findBy(['user' => $this->getUser(), 'groupMessage' => true]);

            foreach ($notificationsGroup as $notification) {
                preg_match("/<a\shref=\"(.*)\"/", $notification->getNotification()->getDescription(), $url);

                $urlNoti = isset($url[1]) ? $url[1] : '';

                $notification->getNotification()->setUrl($urlNoti);
            }
        }
        // END USER NOTIFICATIONS //

        return $this->render(
            'notificaciones/notificaciones_grupo.html.twig',
            ['allUsers' => $allUsers, 'allGroups' => $allGroups, 'allNotTypes' => $notificationsType, 'notifications_group' => $notificationsNavGroup, 'notifications_user' => $notificationsNavUser, 'allnotifygroup' => $notificationsGroup]
        );
    }

    /**
     * Marca como vistas las notificaciones individuales del usuario loggeado,
     * excepto aquellas que tengan el flagSeen activo
     * @return Response
     */
    public function setUserNotificationsSeen(): Response
    {
        $notificationsUser = $this->getNotificationsToRepository()
            ->findBy(['user' => $this->getUser()->getId(), 'groupMessage' => false]);
        $entityManager     = $this->getDoctrine()->getManager();
        foreach ($notificationsUser as $notification) {
            (false === $notification->isFlagSeen()) ? $notification->setSeen(true) : null;
            $entityManager->persist($notification);
            $entityManager->flush();
        }

        return new Response();
    }

    /**
     * Marca como vistas las notificaciones de grupo del usuario loggeado,
     * excepto aquellas que tengan el flagSeen activo
     * @return Response
     */
    public function setGroupNotificationsSeen(): Response
    {
        $notificationsUserGroup = $this->getNotificationsToRepository()
            ->findBy(['user' => $this->getUser()->getId(), 'groupMessage' => true]);
        $entityManager          = $this->getDoctrine()->getManager();
        foreach ($notificationsUserGroup as $notification) {
            (false === $notification->isFlagSeen()) ? $notification->setSeen(true) : null;
            $entityManager->persist($notification);
            $entityManager->flush();
        }

        return new Response();
    }

    /**
     * Activa el flagSeen para una notificación
     *
     * @param Request $request
     *
     * @return Response
     *
     * @throws Exception
     */
    public function setUserNotificationsFlagSeen(Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $notification  = $this->getNotificationsToRepository()->find($request->request->get('notification_id'));
        if (true === $notification->isFlagSeen()) {
            $notification->setFlagSeen(false);
            $notification->setSeen(true);
        } else {
            $notification->setFlagSeen(true);
            $notification->setSeen(false);
        }
        $notification->setUpdatedAt(new DateTime('now'));

        $entityManager->persist($notification);
        $entityManager->flush();

        return new Response();
    }


    /**
     * @return ObjectRepository
     */
    private function getNotificationsToRepository(): ObjectRepository
    {
        return $this->getDoctrine()->getRepository(NotificationsTo::class);
    }

    /**
     * @return ObjectRepository
     */
    private function getNotificationsRepository(): ObjectRepository
    {
        return  $this->getDoctrine()->getRepository(Notifications::class);
    }
}
