<?php
/**
 * Configuración para sonata admin de la entidad Alertas
 */
namespace App\Admin;

use App\Entity\ComercialExpediente;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

/**
 * Class ComercialAlertasAdmin
 */
class ComercialAlertasAdmin extends AbstractAdmin
{


    /**
     * Formulario de SonataAdmin para entidad Alertas
     * @param FormMapper $form
     */
    protected function configureFormFields(FormMapper $form): void
    {
        $form
            ->add('expediente', EntityType::class, ['class' => ComercialExpediente::class, 'choice_label' => 'titulo'])
            ->add('cotizacion')
            ->add('personaNif')
            ->add('oficina')
            ->add('horizontal')
            ->add('vertical')
            ->add('missatge')
            ->add('nivel')
            ->add('updatedAt')
            ->add('createdAt')
            ->add('active')
            ->add('deleted')
            ->add('deletedBy')
            ->add('deletedAt');
    }

    /**
     * Filtros para la tabla de listView de SonataAdmin
     * @param DatagridMapper $filter
     */
    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add('expediente')
            ->add('cotizacion')
            ->add('personaNif')
            ->add('oficina')
            ->add('horizontal')
            ->add('vertical')
            ->add('missatge')
            ->add('nivel')
            ->add('updatedAt')
            ->add('createdAt')
            ->add('active')
            ->add('deleted')
            ->add('deletedBy')
            ->add('deletedAt');
    }

    /**
     * Lista de campos a mostrar en la listView de SonataAdmin
     * @param ListMapper $list
     */
    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->addIdentifier('id')
            ->add('expediente', EntityType::class, ['class' => ComercialExpediente::class, 'choice_label' => 'titulo'])
            ->add('cotizacion')
            ->add('personaNif')
            ->add('oficina')
            ->add('horizontal')
            ->add('vertical')
            ->add('missatge')
            ->add('nivel')
            ->add('updatedAt')
            ->add('createdAt')
            ->add('active')
            ->add('deleted')
            ->add('deletedBy')
            ->add('deletedAt');
    }

    /**
     * Lista de campos a mostrar en la showView de SonataAdmin
     * @param ShowMapper $show
     */
    protected function configureShowFields(ShowMapper $show): void
    {
        $show
            ->add('expediente')
            ->add('cotizacion')
            ->add('personaNif')
            ->add('oficina')
            ->add('horizontal')
            ->add('vertical')
            ->add('missatge')
            ->add('nivel')
            ->add('updatedAt')
            ->add('createdAt')
            ->add('active')
            ->add('deleted')
            ->add('deletedBy')
            ->add('deletedAt');
    }
}
