<?php
/**
 * Listener evento de registro
 */
namespace App\EventListener;

use App\Entity\Role;
use App\Entity\Company;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\FOSUserEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;
use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Component\Templating\EngineInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Dotenv\Dotenv;

/**
 * Class RedirectAfterRegistrationSubscriber
 * @package App\EventListener
 */
class RedirectAfterRegistrationSubscriber implements EventSubscriberInterface
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
     * RedirectAfterRegistrationSubscriber constructor.
     * @param RouterInterface $router
     * @param UserManagerInterface $em
     * @param \Swift_Mailer $mailer
     * @param EngineInterface $templating
     * @param EntityManagerInterface $entityManager
     * @param FlashBagInterface $flashBag
     */
    public function __construct(RouterInterface $router, UserManagerInterface $em, \Swift_Mailer $mailer, EngineInterface $templating, EntityManagerInterface $entityManager, FlashBagInterface $flashBag)
    {
        $this->router = $router;
        $this->em = $em;
        $this->mailer = $mailer;
        $this->templating = $templating;
        $this->em2 = $entityManager;
        $this->flashBag = $flashBag;
    }

    /**
     * Función despues de registro correcto
     * @param FormEvent $event
     */
    public function onRegistrationSuccess(FormEvent $event)
    {
        $user = $event->getForm()->getData();
        if ($user->getEmail()) {
            $this->flashBag->add(
                'notice',
                'Bienvenido! En breve recibirás un email de tu usuario registrado.'
            );
        }

        $user->setEnabled(false);
        $user->setFlagNotify(false);

        //ROL POR DEFECTO
        $role = $this->em2->getRepository(Role::class)->findOneBy(array(
            'roleName' => 'ROLE_USER'));
        $user->setRole($role);

        //COMPANY POR DEFECTO
        if (strtolower(substr($user->getRegNumber(), 0, 3)) == 'u21') {
            $company = $this->em2->getRepository(Company::class)->findOneBy(array('companyShort'=>'CEF'));
        } elseif (strtolower(substr($user->getRegNumber(), 0, 3)) == 'u06') {
            $company = $this->em2->getRepository(Company::class)->findOneBy(array('companyShort'=>'CCF'));
        } elseif (strtolower(substr($user->getRegNumberCaixabank(), 0, 3)) == 'u01') {
            $company = $this->em2->getRepository(Company::class)->findOneBy(array('companyShort'=>'CBK'));
        } else {
            $company = null;
        }

        $user->setCompany($company);

        //Send Email verification
        $this->sendEmailRegistration($user, $this->mailer);
        $this->em->updateUser($user);

        $url = $this->router->generate('fos_user_security_login');
        $response = new RedirectResponse($url);
        $event->setResponse($response);
    }

    /**
     * Enviar email al registrar un usuario nuevo
     * @param $user
     * @param $mailer
     */
    public function sendEmailRegistration($user, $mailer)
    {
        $message = (new \Swift_Message('Se ha registrado un nuevo usuario'))
            ->setFrom(getenv('MAIL_FROM'))
            ->setTo(getenv('MAIL_ADMIN'))
            ->setBody(
                $this->templating->render(
                    'emails/registration.html.twig',
                    ['name' => $user->getUsername()]
                ),
                'text/html'
            )
        ;

        $mailer->send($message);
    }

    /**
     * Obtener evento de seguridad: Registro
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            FOSUserEvents::REGISTRATION_SUCCESS => 'onRegistrationSuccess'
        ];
    }
}
