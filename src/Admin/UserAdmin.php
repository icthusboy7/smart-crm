<?php
/**
 * Configuración para sonata admin de la entidad User
 */
namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;

use Sonata\AdminBundle\Route\RouteCollection;

use App\Entity\Role;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use Sonata\AdminBundle\Form\Type\ModelType;

/**
 * Class UserAdmin
 * @package App\Admin
 */
final class UserAdmin extends AbstractAdmin
{
    /**
     * UserAdmin constructor.
     * @param string $code
     * @param string $class
     * @param string $baseControllerName
     */
    public function __construct($code, $class, $baseControllerName)
    {
        parent::__construct($code, $class, $baseControllerName);
    }

    /**
     * Funcion toString
     * @param mixed $object
     * @return string
     */
    public function toString($object)
    {
        return $object instanceof BlogPost
            ? $object->getTitle()
            : 'User'; // shown in the breadcrumb on the create view
    }

    /**
     * Funcion configureRoutes
     * @param RouteCollection $collection
     */
    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->remove('create');
    }

    /**
     * Formulario de SonataAdmin para entidad User
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        //TODO: HACERLO CON ROLE_HEREDADO
        $role = $this->getRoleFilter();
        $roleName = "ROLE_ADMIN";

        $formMapper
                    ->add('regNumber')
                    ->add('regNumberCaixabank')
                    ->add('name')
                    ->add('surname')
                    ->add('enabled')
                    ->add('role', null, [
                        'class' => 'App\Entity\Role',
                        'required' => true,
                        'query_builder' =>
                            function ($er) use ($roleName, $role) {
                                $qb = $er->createQueryBuilder('r');
                                if ($role === 'ROLE_ADMIN_GUEST') {
                                    if ($roleName) {
                                        $qb->where('r.roleName <> :name')
                                            ->setParameter('name', $roleName);
                                    }
                                }
                                $qb->orderBy('r.roleName', 'ASC');
                                return $qb;
                            }
                            ])
                    ->add('company')
                    ->add('email', null, ['required' => false])
                    ->add('timesLogged')
                    ->add('createdAt')
                    ->add('updatedAt')
        ;
    }

    /**
     * Lista de campos a mostrar en la listView de SonataAdmin
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('regNumber')
            ->add('regNumberCaixabank')
            ->add('name')
            ->add('surname')
            ->add('enabled')
            ->add('company')
            ->add('role')
            ->add('email')
        ;
    }

    /**
     * Filtros para la tabla de listView de SonataAdmin
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('username')
        ;
    }

    /**
     * Interceptar a previo update para identificar si se debe informar(mail) al activar usuario
     * @param object $user
     */
    public function preUpdate($user)
    {
        $container = $this->getConfigurationPool()->getContainer();
        $em = $container->get('doctrine.orm.entity_manager');

        $original = $em->getUnitOfWork()->getOriginalEntityData($user);
        if ($user->isFlagNotify() != $original['flagNotify']) {
            $container = $this->getConfigurationPool()->getContainer();
            $message = (new \Swift_Message('S.M.A.R.T. User enabled'))
                ->setFrom(getenv('MAIL_FROM'))
                ->setTo($user->getEmail())
                ->setBody(
                    $container->get('templating')->render(
                        'emails/enable_user.html.twig',
                        ['name' => $user->getUsername()]
                    ),
                    'text/html'
                )
            ;
            $container->get('mailer')->send($message);
        }
    }

    /**
     * Interceptar evento postUpdate para añadir timeStamps
     * @param object $user
     */
    public function postUpdate($user)
    {
        $container = $this->getConfigurationPool()->getContainer();
        $em = $container->get('doctrine.orm.entity_manager');
        $user->setUpdatedAt(new \DateTime('now'));
        $em->persist($user);
        $em->flush();
    }

    /**
     * Devuelve el Rol del usuario
     * @return mixed
     */
    public function getRoleFilter()
    {
        $user = $this->getConfigurationPool()->getContainer()->get('security.token_storage')->getToken()->getUser();
        $role = $user->getRole()->getRoleName();
        return $role;
    }
}
