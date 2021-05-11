<?php
/**
 * Servicio de notificaciones gestionado por rabbitMQ
 */
namespace App\Rabbit;

use \Exception;

use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;

use Doctrine\ORM\EntityManagerInterface;

use App\Service\NotificationWallService;

/**
 * Class NotificationService
 */
class NotificationService implements ConsumerInterface
{
    /**
     * Inyección de entity manager
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var object
     */
    private $notificationWallService;

    /**
     * NotificationService constructor.
     * @param EntityManagerInterface  $em
     * @param NotificationWallService $notificationWallService
     */
    public function __construct(EntityManagerInterface $em, NotificationWallService $notificationWallService)
    {
        $this->em                      = $em;
        $this->notificationWallService = $notificationWallService;
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
        $this->notificationWallService->notificationMuro($response['Muro'], $response['comercialResponsible']);
    }
}
