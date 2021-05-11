<?php
/**
 * Configuración para sonata admin de la entidad Alertas
 */
namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

/**
 * Class ComercialTaskTypeAdmin
 * @namespace App\Admin
 */
class ComercialTaskTypeAdmin extends AbstractAdmin
{
    /**
     * Formulario de SonataAdmin para entidad Alertas
     * @param FormMapper $form
     */
    protected function configureFormFields(FormMapper $form): void
    {
        $form->add('description')
            ->add('isSpecial')
            ->add('icon')
            ->add('color')
            ->add('form');
    }

    /**
     * Filtros para la tabla de listView de SonataAdmin
     * @param DatagridMapper $filter
     */
    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter->add('description');
        $filter->add('isSpecial');
    }

    /**
     * Lista de campos a mostrar en la listView de SonataAdmin
     * @param ListMapper $list
     */
    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->addIdentifier('id')
            ->add('description')
            ->add('isSpecial')
        ;
    }

    /**
     * Lista de campos a mostrar en la showView de SonataAdmin
     * @param ShowMapper $show
     */
    protected function configureShowFields(ShowMapper $show): void
    {
        $show->add('description');
    }
}
