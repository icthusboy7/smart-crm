<?php
/**
 * Controlador defecto para notificaciones de Rabbit
 */
namespace App\Controller;

use App\Entity\Notifications;

use App\Message\Notification;
use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Messenger\MessageBusInterface;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

// COMPONENTS
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class DefaultController
 * @package App\Controller
 */
class DefaultController extends AbstractController
{

    /**
     * Container interface
     * @var ContainerInterface
     */
    protected $container;

    /**
     * DefaultController constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Envia notificaciones a la cola del servidor rabbitMQ
     * @param Request $request
     * @param ContainerInterface $container
     * @return Response
     */
    public function sendToRabbit(Request $request, ContainerInterface $container)
    {
        $message = ["Type"=>"Muro","User"=>$request->request->get('user_to_notify'),"Group"=>$request->request->get('group_to_notify'), "Message"=>$request->request->get('notify_message')];
        $rabbitMessage = json_encode($message);
        $container->get('old_sound_rabbit_mq.notification_producer')->setContentType('application/json');
        $container->get('old_sound_rabbit_mq.notification_producer')->publish($rabbitMessage);

        return new Response(
            '<html><body><p>Channel: '.$rabbitMessage.'</p></body></html>'
        );
    }
}
