<?php
/**
 * Configuración para sonata admin de la entidad GestorHorizontal
 */
namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;

use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Route\RouteCollection;

/**
 * Class GestorHorizontalAdmin
 * @package App\Admin
 */
final class GestorHorizontalAdmin extends AbstractAdmin
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
            : 'Gestor Horizontal'; // shown in the breadcrumb on the create view
    }

    /**
     * Funcion configureRoutes
     * @param RouteCollection $collection
     */
    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->remove('create');
        $collection->remove('delete');
    }

    /**
     * Lista de campos a mostrar en la listView de SonataAdmin
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            //->addIdentifier('id', null, ['header_style' => 'width: 5%', 'label' => 'Code'])

            ->add('Gestor', null, ['label' => 'Gestor'])
            ->add('Horizontal', null, ['label' => 'Horizontal'])
        ;
    }

    /**
     * Filtros para la tabla de listView de SonataAdmin
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('Gestor')
            ->add('Horizontal')
        ;
    }
}
