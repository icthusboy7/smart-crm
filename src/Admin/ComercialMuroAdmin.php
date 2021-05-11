<?php
/**
 * Configuración para sonata admin de la entidad ComercialMuro
 * @author Jordi Mas jmasj@itteria.com
 */

namespace App\Admin;

use App\Entity\ComercialCotizacion;
use App\Entity\ComercialExpediente;
use App\Entity\ComercialMuroTipo;
use App\Entity\User;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\Filter\ChoiceType;
use Sonata\AdminBundle\Form\Type\ModelType;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ComercialMuroAdmin extends AbstractAdmin
{
    /**
     * @param FormMapper $form
     */
    protected function configureFormFields(FormMapper $form): void
    {

        $form->add('missatge')
            ->add('visto')
            ->add('nivel')
            ->add('grupo')
            ->add('expediente', EntityType::class, [
                'class'        => ComercialExpediente::class,
                'choice_label' => 'titulo',
                'placeholder'  => 'Choose an option',
                'required'     => false,
            ])
            ->add('cotizacion', EntityType::class, [
                'class'        => ComercialCotizacion::class,
                'choice_label' => 'numcoti',
                'placeholder'  => 'Choose an option',
                'required'     => false,
            ])
            ->add('autor', EntityType::class, [
                'class'        => User::class,
                'choice_label' => 'name',
                'placeholder'  => 'Choose an option',
                'required'     => true,
            ])
            ->add('responsable', EntityType::class, [
                'class'        => User::class,
                'choice_label' => 'name',
                'placeholder'  => 'Choose an option',
                'required'     => false,
            ])
            ->add('cerradoPor', EntityType::class, [
                'class'        => User::class,
                'choice_label' => 'name',
                'placeholder'  => 'Choose an option',
                'required'     => false,
            ])
            ->add('motivoCanc');
    }

    /**
     * Filtros para la tabla de listView de SonataAdmin
     * @param DatagridMapper $filter
     */
    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter->add('tipo')
        ->add('autor');
    }

    /**
     * Lista de campos a mostrar en la listView de SonataAdmin
     * @param ListMapper $list
     */
    protected function configureListFields(ListMapper $list): void
    {
        $list->addIdentifier('id')
            ->add('missatge')
            ->add('Autor')
            ->add('Responsible')
            ->add('expediente')
            ->add('cotizacion');
    }

    /**
     * Lista de campos a mostrar en la showView de SonataAdmin
     * @param ShowMapper $show
     */
    protected function configureShowFields(ShowMapper $show): void
    {
        $show->addIdentifier('id')
            ->add('missatge')
            ->add('Autor')
            ->add('Responsible');
    }

}
