<?php
/**
 * Configuración para sonata admin de la entidad Company
 */
namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\TextType;

/**
 * Class CompanyAdmin
 * @package App\Admin
 */
final class CompanyAdmin extends AbstractAdmin
{
    /**
     * Formulario de SonataAdmin para entidad Company
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('companyName', TextType::class);
        $formMapper->add('companyShort', TextType::class);
    }

    /**
     * Filtros para la tabla de listView de SonataAdmin
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('companyName');
    }

    /**
     * Lista de campos a mostrar en la listView de SonataAdmin
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('companyName')
            ->add('companyShort');
    }
}
