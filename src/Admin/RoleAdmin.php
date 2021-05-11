<?php
/**
 * Configuración para sonata admin de la entidad Role
 */
namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\TextType;

use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

/**
 * Class RoleAdmin
 * @package App\Admin
 */
final class RoleAdmin extends AbstractAdmin
{
    /**
     * Formulario de SonataAdmin para entidad Admin
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('roleName', TextType::class);


//        $permissions = [
//            'ROLE_USER'        => 'ROLE_USER',
//            'ROLE_ADMIN'     => 'ROLE_ADMIN'
//        ];
//
//        $formMapper->add('roles',ChoiceType::class, [
//            'choices'  => [
//                $permissions
//            ],
//        ]);

        /*$formMapper->add('roles',ChoiceType::class, [
            'choices'  => [
                $permissions
            ],
        ]);*/

//        $formMapper->add('roles',ChoiceType::class, [
//            'choices'  => [
//                $this->flattenRoles()
//            ],
//        ]);

//        $formMapper->add('roles',ChoiceType::class, [
//            'choices'  => [
//                $this->getConfigurationPool()->getContainer()->getParameter('security.role_hierarchy.roles')
//            ],
//        ]);



        // sintaxis dentro de admin class:
        /*$roleHierarchy = $this->getConfigurationPool()->getContainer()->getParameter('security.role_hierarchy.roles');
        // sintaxis dentro de un controlador:
        // $roleHierarchy = $this->container->getParameter('security.role_hierarchy.roles');
        $roles = array_keys($roleHierarchy);

        $theRoles = array();

        foreach ($roles as $role) {
            $theRoles[$role] = $role;
        }
        //$formMapper->add('roles',ChoiceType::class, [
          //  'choices'  => array(0 => $theRoles),
        //]);*/

//        $formMapper->add('roles', CollectionType::class, [
//            'entry_type'   => ChoiceType::class,
//            'entry_options'  => [
//                'choices'  => [
//                    Group::getRoleList(),
//                ],
//            ],
//        ]);


        $rolesChoices = self::flattenRoles();

        /*$formMapper->add('roles',ChoiceType::class, [
          'choices'  => [self::flattenRoles()],
        ]);*/
    }

    /**
     * Filtros para la tabla de listView de SonataAdmin
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('roleName');
    }

    /**
     * Lista de campos a mostrar en la listView de SonataAdmin
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('roleName')
        ;
    }

    /**
     * Devuelve todos los roles existentes
     * @return array
     */
    public function getExistingRoles()
    {
        // sintaxis dentro de admin class:
        $roleHierarchy = $this->getConfigurationPool()->getContainer()->getParameter('security.role_hierarchy.roles');
        // sintaxis dentro de un controlador:
        // $roleHierarchy = $this->container->getParameter('security.role_hierarchy.roles');
        $roles = array_keys($roleHierarchy);
        $theRoles = array();

        foreach ($roles as $role) {
            $theRoles[$role] = $role;
        }
        return $theRoles;
    }

    /**
     * Turns the role's array keys into string <ROLES_NAME> keys.
     * @todo Move to convenience or make it recursive ? ;-)
     * @return array
     */
    public function flattenRoles()
    {
        $rolesHierarchy = $this->getConfigurationPool()->getContainer()->getParameter('security.role_hierarchy.roles');

        $flatRoles = [];
        foreach ($rolesHierarchy as $key => $roles) {
            $flatRoles[$key] = $key;
            if (empty($roles)) {
                continue;
            }

            foreach ($roles as $role) {
                if (!isset($flatRoles[$role])) {
                    $flatRoles[$role] = $role;
                }
            }
        }

        return $flatRoles;
    }
}
