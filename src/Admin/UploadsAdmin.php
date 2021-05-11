<?php
/**
 * Configuración para sonata admin de la vista de Uploads
 */
namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Route\RouteCollection;

/**
 * Class UploadsAdmin
 * @package App\Admin
 */
final class UploadsAdmin extends AbstractAdmin
{
    /**
     * Ruta para acceder a la vista de uploads
     * @var string
     */
    protected $baseRoutePattern = 'uploads';
    /**
     * Nombre de ruta para acceder a la vista de uploads
     * @var string
     */
    protected $baseRouteName = 'uploads';

    /**
     * Funcion configureRoutes
     * @param RouteCollection $collection
     */
    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->clearExcept(['list']);
    }
}
