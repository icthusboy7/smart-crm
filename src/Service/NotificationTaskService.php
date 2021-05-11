<?php


namespace App\Service;

use \Exception;
use \Throwable;

use App\Entity\ComercialTask;
use App\Entity\Notifications;
use App\Entity\NotificationsTo;

use Doctrine\ORM\EntityManagerInterface;

use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class NotificationTaskService
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
    public $logger;

    /**
     * @var object
     */
    public $router;


    /**
     * NotificationTaskService constructor.
     * @param EntityManagerInterface $em
     * @param TranslatorInterface    $translator
     * @param UrlGeneratorInterface  $router
     * @param LoggerInterface|null   $logger
     */
    public function __construct(EntityManagerInterface $em, TranslatorInterface $translator, UrlGeneratorInterface $router, ?LoggerInterface $logger = null)
    {
        $this->em         = $em;
        $this->translator = $translator;
        $this->logger     = $logger;
        $this->router     = $router;
    }
    /**
     * @param ContainerInterface $container
     * @param ComercialTask      $task
     *
     * @return bool
     */
    public function commercialTaskMessage(ContainerInterface $container, ComercialTask $task): bool
    {
        try {
            $container->get('old_sound_rabbit_mq.task_producer');
            $container->get('old_sound_rabbit_mq.task_producer')->setContentType('application/json');
            $container->get('old_sound_rabbit_mq.task_producer')->setDeliveryMode(2);
            $container->get('old_sound_rabbit_mq.task_producer')->publish(json_encode(['task' => $task->getId()]));

            return true;
        } catch (Exception $exception) {
            $this->logger->error('Error Send Message With in Wall Service', [$exception->getMessage()]);

            return false;
        } catch (\Error $error) {
            $this->logger->error('Error Send Message With in Wall Service', [$error->getMessage()]);

            return false;
        }
    }

    /**
     * @param int $idTask
     *
     * @return bool
     */
    public function saveNotificationTask(int $idTask): bool
    {
        if (is_null($idTask)) {
            return false;
        }

        $task = $this->em
            ->getRepository(ComercialTask::class)
            ->findOneBy(['id' => $idTask]);

        // if task not have responsible not create notification
        if (!is_null($task->getResponsible())) {
            $notification = $this->saveNotification($task);

            if (!is_null($notification)) {
                $this->saveNotificationTo($task, $notification);
            }
        }

        return true;
    }

    /**
     * generateLink
     * @param string $url
     * @param string $text
     * @param string $dataId
     *
     * @return string
     */
    public function generateLinkText(string $url, string $text, string $dataId): string
    {
        return '<a href="'.$url.'">'.$text.$dataId.'</a>';
    }

    /**
     * @param ComercialTask $task
     *
     * @return Notifications
     */
    private function saveNotification(ComercialTask $task): Notifications
    {
        $notification = new Notifications();
        $notification->setDescription($task->getCreatedBy()->getName().' '.$task->getCreatedBy()->getSurname().': '
            .$task->getDescription().' '.$this->generateLinkText($this->router->generate('comercial_ver_muro_tarea', ['idTarea' => $task->getId(), '_locale' => 'es' ]), $this->translator->trans(' wall Task #'), $task->getId()))
            ->setCreatedAt($task->getCreatedAt());
        $this->em->persist($notification);
        $this->em->flush();

        return $notification;
    }

    /**
     * @param ComercialTask $task
     * @param Notifications $notification
     *
     * @return bool
     */
    private function saveNotificationTo(ComercialTask $task, Notifications $notification): bool
    {
        try {
            $notificationTo = new NotificationsTo();
            $notificationTo->setNotification($notification);
            $notificationTo->setUser($task->getResponsible());
            $notificationTo->setCreatedAt($task->getCreatedAt());
            $notificationTo->setGroupMessage(false);
            $notificationTo->setSeen(false);
            $notificationTo->setFlagSeen(false);

            $this->em->persist($notificationTo);
            $this->em->flush();

            return true;
        } catch (Exception $exception) {
            return false;
        } catch (Throwable $throwable) {
            return false;
        }
    }
}
