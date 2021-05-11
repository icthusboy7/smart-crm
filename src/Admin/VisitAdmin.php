<?php
/**
 * Configuración para sonata admin de la entidad Company
 */
namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

/**
 * Class VisitAdmin
 * @package App\Admin
 */
final class VisitAdmin extends AbstractAdmin
{

    protected $datagridValues = [
        '_page' => 1,
        '_sort_order' => 'DESC',
        '_sort_by' => 'DateIni',
    ];

    /**
     * Formulario de SonataAdmin para entidad Visit
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('CustomerID');
        $formMapper->add('CustomerCharge');
        $formMapper->add('CustomerChargeAnother');
        $formMapper->add('ProviderID');
        $formMapper->add('ProviderCharge');
        $formMapper->add('ProviderChargeAnother');
        $formMapper->add('office');
        $formMapper->add('DateIni');
        $formMapper->add('duration');
        $formMapper->add('vertical');
        $formMapper->add('reason');
        $formMapper->add('type', ChoiceType::class, [
            'choices'  => ['Presencial' => 1, 'Virtual' => 0],
            'multiple' => false,
            'expanded' => true,
        ]);
        $formMapper->add('observations');
        $formMapper->add('feedback');
        $formMapper->add('status');
    }

    /**
     * Filtros para la tabla de listView de SonataAdmin
     * @param DatagridMapper $datagridMapper
     *
     * @return void
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $datagridMapper->add('DateIni');
        $datagridMapper->add('CustomerID');
        $datagridMapper->add('ProviderID');
        $datagridMapper->add('reason');
        $datagridMapper->add('status');
    }

    /**
     * Lista de campos a mostrar en la listView de SonataAdmin
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('id')
            ->add('DateIni')
            ->add('duration')
            ->add('reason')
            ->add('CustomerID')
            ->add('ProviderID')
            ->add('office');
    }
}
