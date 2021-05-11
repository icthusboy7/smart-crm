<?php
/**
 * Listener del evento LOGIN
 */
namespace App\EventListener;

use Symfony\Component\Ldap;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\FOSUserEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;
use FOS\UserBundle\Model\UserManagerInterface;

/**
 * Class RedirectAfterLogin
 * @package App\EventListener
 */
class RedirectAfterLogin implements EventSubscriberInterface
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
     * RedirectAfterLogin constructor.
     * @param RouterInterface $router
     * @param UserManagerInterface $em
     */
    public function __construct(RouterInterface $router, UserManagerInterface $em)
    {
        $this->router = $router;
        $this->em = $em;
    }

    /**
     * Acción para login correcto
     * @param FormEvent $event
     */
    public function onLoginSuccess(FormEvent $event)
    {
        $url = $this->router->generate('sonata_admin_dashboard');
        $response = new RedirectResponse($url);
        $event->setResponse($response);
    }

    /**
     * Obtener evento de seguridad: Login
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            FOSUserEvents::SECURITY_IMPLICIT_LOGIN => 'onLoginSuccess'
        ];
    }
}
