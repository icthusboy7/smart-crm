<?php
/**
 * Configuración para sonata admin de la entidad Admin
 */
namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

use Sonata\AdminBundle\Datagrid\DatagridMapper;


use Sonata\AdminBundle\Route\RouteCollection;

/**
 * Class AdminAdmin
 * @package App\Admin
 */
final class AdminAdmin extends AbstractAdmin
{
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
        //$collection->remove('create');
    }

    /**
     * Formulario de SonataAdmin para entidad Admin
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
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
                    ->add('email')
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
     * Devuelve el Rol del usuario
     * @return mixed
     */
    public function getRoleFilter()
    {
        $user = $this->getConfigurationPool()->getContainer()->get('security.token_storage')->getToken()->getUser();
        $role = $user->getRole()->getRoleName();
        return $role;
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
}
