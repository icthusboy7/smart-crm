<?php

namespace App\Service;

use \Exception;

use App\Entity\ComercialExpedienteFavoritos;
use App\Entity\ComercialMuro;
use App\Entity\ComercialTask;
use App\Entity\Notifications;
use App\Entity\NotificationsTo;
use App\Entity\User;

use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class NotificationWallService
{
    /**
     * @var object
     */
    private $em;

    /**
     * @var object
     */
    private $translator;

    /**
     * @var object
     */
    private $notification;

    /**
     * @var object
     */
    private $notificationTo;

    /**
     * @var object
     */
    private $dataWall;

    /**
     * @var object
     */
    private $router;

    /**
     * NotificationService constructor.
     * @param EntityManagerInterface $em
     * @param TranslatorInterface    $translator
     * @param UrlGeneratorInterface  $router
     * @param MuroService            $muroService
     */
    public function __construct(EntityManagerInterface $em, TranslatorInterface $translator, UrlGeneratorInterface $router, MuroService $muroService)
    {
        $this->em             = $em;
        $this->translator     = $translator;
        $this->notification   = new Notifications();
        $this->notificationTo = new NotificationsTo();
        $this->router         = $router;
        $this->dataWall       = $muroService;
    }

    /**
     * Save Notification
     * @param array $dataMessage
     */
    public function saveNotification(array $dataMessage): void
    {
        $this->notification->setDescription($dataMessage['message'])
            ->setCreatedAt($dataMessage['dateTime']);

        $this->em->persist($this->notification);
        $this->em->flush();
    }

    /**
     * Users to send save notificationTo
     * @param array $userToSend
     * @param bool  $groupMessage
     */
    public function saveNotificationToUsers(array $userToSend, bool $groupMessage = false): void
    {
        foreach ($userToSend as $userTo) {
            $this->saveNotificationTo($userTo, $groupMessage);
        }
    }

    /**
     * Save user Notification To
     * @param User $addUser
     * @param bool $groupMessage
     */
    public function saveNotificationTo(User $addUser, bool $groupMessage = false): void
    {
        $this->notificationTo = new NotificationsTo();
        $this->notificationTo->setNotification($this->notification);
        $this->notificationTo->setUser($addUser);
        $this->notificationTo->setGroupMessage($groupMessage);
        $this->notificationTo->setSeen(false);
        $this->notificationTo->setFlagSeen(false);

        $this->em->persist($this->notificationTo);
        $this->em->flush();
    }

    /**
     * @param int|null $wallId
     * @param array    $comercialResponsible
     *
     * @return bool
     *
     * @throws Exception
     */
    public function notificationMuro(?int $wallId, array $comercialResponsible): bool
    {
        if (is_null($wallId)) {
            return false;
        }

        // Get message created into wall
        $dataNotification = $this->dataWall->getNotificationData($wallId);

        $this->notification = new Notifications();
        // save Notification
        $this->saveNotification($dataNotification);

        // Notification all wall Writers
        $usersToSend = $this->getUsersWriters(
            $this->em->getRepository(ComercialMuro::class)->findUsersNotification(
                $wallId,
                $dataNotification['objectWall']->getAutor()->getId(),
                $dataNotification['whereMuro'],
                $dataNotification['paramMuro']
            )
        );

        // Is closed task
        if ($dataNotification['objectWall']->getTipo() === MuroService::STATUS) {
            $closeTask = $this->dataWall->isCloseTask($dataNotification['objectWall']->getId());
            if (!is_null($closeTask)) {
                (!is_null($closeTask->getResponsible()) && $this->isToReadyUser($closeTask->getResponsible()->getId(), $usersToSend))
                    ? array_push($usersToSend, $closeTask->getResponsible())
                    : null;
                (!is_null($closeTask->getCreatedBy()) && $this->isToReadyUser($closeTask->getCreatedBy()->getId(), $usersToSend))
                    ? array_push($usersToSend, $closeTask->getCreatedBy())
                    : null;
            }
        }

        // Wall message comes Pipeline
        if (!is_null($dataNotification['objectWall']->getExpediente())) {
            // Notification all users with favourite pipeline
            $this->getUsersFavouritePipeline($usersToSend, $dataNotification);
        }

        // if exist a Responsable pipeline
        if (!is_null($dataNotification['objectWall']->getExpediente()) && $this->isToReadyUser($dataNotification['objectWall']->getExpediente()->getResponsable()->getId(), $usersToSend)) {
            array_push($usersToSend, $dataNotification['objectWall']->getExpediente()->getResponsable());
        }

        // if is not task
        if (2 !== $dataNotification['objectWall']->getTipo() && !is_null($dataNotification['objectWall']->getExpediente())) {
            if ($this->dataWall->isTypeUser($dataNotification['objectWall']->getAutor(), 'Commercial')) {
                (!is_null($dataNotification['objectWall']->getExpediente()->getResponsableGestorInterno())
                && $this->isToReadyUser($dataNotification['objectWall']->getExpediente()->getResponsableGestorInterno(), $usersToSend))
                    ? array_push($usersToSend, $dataNotification['objectWall']->getExpediente()->getResponsableGestorInterno())
                    : (!empty($comercialResponsible)
                        ? $this->getDataManagerUsers($comercialResponsible)
                        : null);
            }

            if (!empty($usersToSend)) {
                $this->saveNotificationToUsers($usersToSend);
            }
        } else {
            $userToSend = $this->em->getRepository(ComercialTask::class)->findOneBy(['comercialMuro' => $wallId]);

            (!is_null($userToSend)) ? $this->saveNotificationTo($userToSend->getResponsible()) : null;

            (!is_null($dataNotification['objectWall']->getExpediente())
                ? $this->saveNotificationTo($dataNotification['objectWall']->getExpediente()->getResponsableGestorInterno())
                : $this->getDataManagerUsers($comercialResponsible));

            if (!empty($usersToSend)) {
                $this->saveNotificationToUsers($usersToSend);
            }
        }

        return true;
    }

    /**
     * @param array $dataManagerUsers
     *
     * @throws Exception
     */
    public function getDataManagerUsers(array $dataManagerUsers): void
    {
        foreach ($dataManagerUsers as $managerUser) {
            $this->saveNotificationTo($this->getUserManager($managerUser['Gestor']), true);
        }
    }

    /**
     * @param string $userName
     *
     * @return User
     */
    public function getUserManager(string $userName): User
    {
        return $this->em->getRepository(User::class)->findOneBy(['username' => $userName]);
    }

    /**
     * @param array $dataUsersNotice
     *
     * @return array
     */
    public function getUsersWriters(array $dataUsersNotice): array
    {
        $userToSend = [];
        foreach ($dataUsersNotice as $user) {
            array_push($userToSend, $this->getUserObject($user));
        }

        return $userToSend;
    }

    /**
     * @param array $arrayUsersAlready
     * @param array $dataNotification
     */
    public function getUsersFavouritePipeline(array &$arrayUsersAlready, array $dataNotification): void
    {
        $usersPipelineFavourite = $this->em->getRepository(ComercialExpedienteFavoritos::class)
            ->findUsersFavouritePipeline(
                $dataNotification['objectWall']->getAutor()->getId(),
                $dataNotification['objectWall']->getExpediente()->getId()
            );
        //var_dump($arrayUsersAllready[0]->getId());
        foreach ($usersPipelineFavourite as $userPipelineFav) {
            ($this->isToReadyUser($userPipelineFav->getUser()->getId(), $arrayUsersAlready))
            ? array_push($arrayUsersAlready, $userPipelineFav->getUser())
            : null;
        }
    }

    /**
     * @param int   $user
     * @param array $arrayUsersAlready
     *
     * @return bool
     */
    public function isToReadyUser(int $user, array $arrayUsersAlready): bool
    {
        foreach ($arrayUsersAlready as $userAlready) {
            if ($userAlready->getId() === $user) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param mixed $user
     *
     * @return User
     */
    public function getUserObject($user): ?User
    {
        if ('App\Entity\ComercialMuro' === get_class($user) || 'Proxies\__CG__\App\Entity\ComercialMuro' === get_class($user)) {
            return $user->getAutor();
        }

        if ('App\Entity\ComercialTask' === get_class($user)) {
            return $user->getResponsible();
        }

        return null;
    }
}
