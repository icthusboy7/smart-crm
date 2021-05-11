<?php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class ComercialExpedienteCotizacionesAdmin extends AbstractAdmin{


    protected function configureFormFields(FormMapper $form)
    {
        $form
            ->add('expedienteID')
            ->add('cotizacion')
            ->add('createdAt');
    }

    protected function configureDatagridFilters(DatagridMapper $filter)
    {
        $filter
            ->add('expedienteID')
            ->add('cotizacion')
            ->add('createdAt');
    }

    protected function configureListFields(ListMapper $list)
    {
        $list
            ->addIdentifier('id')
            ->add('expedienteID')
            ->add('cotizacion')
            ->add('createdAt');
    }

    protected function configureShowFields(ShowMapper $show)
    {
        $show
            ->add('expedienteID')
            ->add('cotizacion')
            ->add('createdAt');
    }
}
