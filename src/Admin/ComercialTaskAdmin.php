<?php
/**
 * Configuración para sonata admin de la entidad ComercialTask
 * @author Adrià Garrido agarrido@itteria.com
 */
namespace App\Admin;

use App\Entity\ComercialTaskStatus;
use App\Entity\ComercialTaskType;
use App\Entity\User;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelType;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

/**
 * Class ComercialTaskAdmin
 * @namespace App\Admin
 */
class ComercialTaskAdmin extends AbstractAdmin
{
    /**
     * @param FormMapper $form
     */
    protected function configureFormFields(FormMapper $form): void
    {
        $form
            ->with('General', ['class' => 'col-md-9'])
                ->add('type', ModelType::class, [
                    'class'       => ComercialTaskType::class,
                    'property'    => 'description',
                    'placeholder' => 'Choose an option',
                ])
                ->add('description')
                ->add('responsible', EntityType::class, [
                    'class'        => User::class,
                    'choice_label' => 'name',
                    'placeholder'  => 'Choose an option',
                    'required'     => false,
                ])
            ->end()
            ->with('Status', ['class' => 'col-md-3'])
                ->add('status', ModelType::class, [
                    'class'       => ComercialTaskStatus::class,
                    'property'    => 'description',
                    'placeholder' => 'Choose an option',
                ])
                ->add('seen')
            ->end()
        ;
    }

    /**
     * Filtros para la tabla de listView de SonataAdmin
     * @param DatagridMapper $filter
     */
    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter->add('description');
    }

    /**
     * Lista de campos a mostrar en la listView de SonataAdmin
     * @param ListMapper $list
     */
    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->addIdentifier('id')
            ->add('status')
            ->add('responsible')
            ->add('description')
            ->add('type')
            ->add('seen')
            ->add('createdAt')
            ->add('createdBy')
            ->add('updatedAt')
            ->add('deletedAt')
            ->add('deletedBy')
        ;
    }

    /**
     * Lista de campos a mostrar en la showView de SonataAdmin
     * @param ShowMapper $show
     */
    protected function configureShowFields(ShowMapper $show): void
    {
        $show
            ->add('responsible')
            ->add('type')
            ->add('createdBy')
            ->add('deletedBy')
            ->add('status')
            ->add('description')
            ->add('seen')
            ->add('createdAt')
            ->add('updatedAt')
            ->add('deletedAt')
        ;
    }
}
