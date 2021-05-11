<?php
/**
 * Listener del evento: Activación de usuario
 */
namespace App\EventListener;

use FOS\UserBundle\Event\UserEvent;
use FOS\UserBundle\FOSUserEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;
use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Component\Templating\EngineInterface;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class SendEmailEnabledUser
 * @package App\EventListener
 */
class SendEmailEnabledUser implements EventSubscriberInterface
{
    /**
     * Inyección de RouterInterface
     * @var RouterInterface
     */
    private $router;
    /**
     * Inyección de UserManagerInterface
     * @var UserManagerInterface
     */
    private $em;
    /**
     * Inyección de SwiftMailer
     * @var \Swift_Mailer
     */
    private $mailer;
    /**
     * Inyección de EngineInterface
     * @var EngineInterface
     */
    private $templating;

    /**
     * SendEmailEnabledUser constructor.
     * @param RouterInterface $router
     * @param UserManagerInterface $em
     * @param \Swift_Mailer $mailer
     * @param EngineInterface $templating
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(RouterInterface $router, UserManagerInterface $em, \Swift_Mailer $mailer, EngineInterface $templating, EntityManagerInterface $entityManager)
    {
        $this->router = $router;
        $this->em = $em;
        $this->mailer = $mailer;
        $this->templating = $templating;
    }

    /**
     * Función a realizar al activar usuario
     * @param UserEvent $event
     */
    public function onEnabledUser(UserEvent $event)
    {

        $user = $event->getUser();

        //VERIFICA SI EL USUARIO TIENE EMAIL
        if($user->getEmail()) {
            $username = $user->getUsername();
            $mail = $user->getEmail();

            $this->sendEmailEnabledUser($username, $mail, $this->mailer);
        }
    }

    /**
     * Envio de mail de usuario activado
     * @param $username
     * @param $mail
     * @param $mailer
     */
    public function sendEmailEnabledUser($username, $mail, $mailer)
    {
        $message = (new \Swift_Message('Lets Test'))
            ->setFrom(getenv('MAIL_FROM'))
            ->setTo($mail)
            ->setBody(
                $this->templating->render(
                    'emails/enable_user.html.twig',
                    ['name' => $username]
                ),
                'text/html'
            )
        ;

        $mailer->send($message);
    }

    /**
     * Obtener evento de FOSUserBundle: Activación de usuario
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            FOSUserEvents::USER_ACTIVATED => 'onEnabledUser'
        ];
    }
}
