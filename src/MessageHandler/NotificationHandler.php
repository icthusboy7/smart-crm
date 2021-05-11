<?php
/**
 * Handler de notificaciones
 */
namespace App\MessageHandler;

use App\Message\Notification;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

/**
 * Class NotificationHandler
 * @package App\MessageHandler
 */
class NotificationHandler implements MessageHandlerInterface
{
    /**
     * Función invoke para dump del contenido
     * @param Notification $message
     */
    public function __invoke(Notification $message)
    {
        var_dump($message);
    }
}
