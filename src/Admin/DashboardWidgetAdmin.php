<?php
/**
 * Configuración para sonata admin de la entidad DashboardWidget
 */
namespace App\Admin;

use App\Entity\DashboardWidgets;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

/**
 * Class DashboardWidgetAdmin
 * @package App\Admin
 */
class DashboardWidgetAdmin extends AbstractAdmin
{


    /**
     * Formulario de SonataAdmin para entidad Dashboards
     * @param FormMapper $form
     */
    protected function configureFormFields(FormMapper $form)
    {
        $widget = $this->getSubject();

        $form
            ->add('nombre')
            ->add('titulo')
            ->add('padre', null, [
                'class' => 'App\Entity\DashboardWidgets',
                'query_builder' =>
                    function ($er) use ($widget) {
                        $qb = $er->createQueryBuilder('w');
                        if ($widget) {
                            $qb->where('w.id != :id')
                                ->setParameter('id', $widget->getId());
                        }
                        $qb->orderBy('w.nombre', 'ASC');
                        return $qb;
                    }
            ])
            ->add('orden')
            ->add('role')
            ->add('rutaInterna')
            ->add('href')
            ->add('descripcion')
            ->add('isPlantilla', null, ['help' => 'Marcar el campo para usar un Twig como plantilla.'])
            ->add('plantilla', null, ['help' => 'Introducir el path del Twig si está marcado el campo isPlantilla, en caso contrario rellenar el campo con código HTML'])
            ->add('faIcon')
            ->add('attributes', null, ['help' => 'Añadir atributos del div. Se insertarán de la siguiente manera: &lt;div attributes &gt; &lt;/div&gt;'])
            ->add('isActive')
            ->add('createdAt')
            ->add('updatedAt')
            ;
    }

    /**
     * Filtros para la tabla de listView de SonataAdmin
     * @param DatagridMapper $filter
     */
    protected function configureDatagridFilters(DatagridMapper $filter)
    {
        $filter
            ->add('nombre');
    }

    /**
     * Lista de campos a mostrar en la listView de SonataAdmin
     * @param ListMapper $list
     */
    protected function configureListFields(ListMapper $list)
    {
        $list
            ->addIdentifier('id')
            ->addIdentifier('nombre', null, [
                'collapse' => true
            ])
            ->add('titulo', null, [
                    'collapse' => true
                ])
            ->add('padre', null, [
                    'collapse' => true
                ])
            ->add('orden', null, [
                'collapse' => true
            ])
            ->add('role', null, [
                'collapse' => true
            ])
            ->add('rutaInterna', null, [
                'collapse' => true
            ])
            ->add('href', null, [
                'collapse' => true
            ])
            ->add('descripcion', null, [
                'collapse' => true
            ])
            ->add('isPlantilla', null, [
                'collapse' => true
            ])
            ->add('plantilla', null, [
                'collapse' => true
            ])
            ->add('faIcon', null, [
                'collapse' => true
            ])
            ->add('attributes', null, [
                    'collapse' => true
                ])
            ->add('isActive', null, [
                'collapse' => true
            ])
            ->add('createdAt', null, [
                'collapse' => true
            ])
            ->add('updatedAt', null, [
                'collapse' => true
            ]);
    }

    /**
     * Lista de campos a mostrar en la showView de SonataAdmin
     * @param ShowMapper $show
     */
    protected function configureShowFields(ShowMapper $show)
    {
        $show
            ->add('nombre')
            ->add('titulo')
            ->add('padre')
            ->add('orden')
            ->add('role')
            ->add('rutaInterna')
            ->add('href')
            ->add('descripcion')
            ->add('plantilla')
            ->add('faIcon')
            ->add('attributes')
            ->add('isActive')
            ->add('createdAt')
            ->add('updatedAt');
    }

    /**
     * Interceptar evento postUpdate para añadir timeStamps
     * @param object $widgets
     */
    public function postUpdate($widgets)
    {
        $container = $this->getConfigurationPool()->getContainer();
        $em = $container->get('doctrine.orm.entity_manager');
        $widgets->setUpdatedAt(new \DateTime('now'));
        $em->persist($widgets);
        $em->flush();
    }
}
