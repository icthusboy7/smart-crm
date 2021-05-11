<?php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class ComercialExpedientesAdmin extends AbstractAdmin{

    protected function configureFormFields(FormMapper $form)
    {
        $form
            ->add('clienteNIF')
            ->add('oficina')
            ->add('importe')
            ->add('fechaOportunidad')
            ->add('fechaPosibleActivacion')
            ->add('porcentajeProbabilidad')
            ->add('status')
            ->add('productoID')
            ->add('prescriptorCIF')
            ->add('observaciones')
            ->add('createdAt')
            ->add('updatedAt');
    }

    protected function configureDatagridFilters(DatagridMapper $filter)
    {
        $filter
            ->add('clienteNIF')
            ->add('oficina')
            ->add('importe')
            ->add('fechaOportunidad')
            ->add('fechaPosibleActivacion')
            ->add('porcentajeProbabilidad')
            ->add('status')
            ->add('productoID')
            ->add('prescriptorCIF')
            ->add('observaciones')
            ->add('createdAt')
            ->add('updatedAt');
    }

    protected function configureListFields(ListMapper $list)
    {
        $list
            ->addIdentifier('id')
            ->add('clienteNIF')
            ->add('oficina')
            ->add('importe')
            ->add('fechaOportunidad')
            ->add('fechaPosibleActivacion')
            ->add('porcentajeProbabilidad')
            ->add('status')
            ->add('productoID')
            ->add('prescriptorCIF')
            ->add('observaciones')
            ->add('createdAt')
            ->add('updatedAt');
    }

    protected function configureShowFields(ShowMapper $show)
    {
        $show
            ->add('clienteNIF')
            ->add('oficina')
            ->add('importe')
            ->add('fechaOportunidad')
            ->add('fechaPosibleActivacion')
            ->add('porcentajeProbabilidad')
            ->add('status')
            ->add('productoID')
            ->add('prescriptorCIF')
            ->add('observaciones')
            ->add('createdAt')
            ->add('updatedAt');
    }
}
