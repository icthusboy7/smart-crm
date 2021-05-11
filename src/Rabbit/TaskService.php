<?php


namespace App\Rabbit;

use \Exception;

use App\Service\NotificationTaskService;

use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;

class TaskService implements ConsumerInterface
{

    /**
     * @var object
     */
    private $notificationTaskService;

    /**
     * TaskService constructor.
     * @param NotificationTaskService $notificationTaskService
     */
    public function __construct(NotificationTaskService $notificationTaskService)
    {
        $this->notificationTaskService = $notificationTaskService;
    }

    /**
     * Recibe notificaciones de Rabbit y las gestiona por tipo
     *
     * @param AMQPMessage $msg
     *
     * @throws Exception
     */
    public function execute(AMQPMessage $msg): void
    {
        $response = json_decode($msg->body, true);

        $this->notificationTaskService->saveNotificationTask($response['task']);
    }
}
