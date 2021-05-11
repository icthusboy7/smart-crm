<?php
/**
 * Configuración para sonata admin de la vista de Translations
 */
namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Route\RouteCollection;

/**
 * Class TranslationAdmin
 * @package App\Admin
 */
final class TranslationAdmin extends AbstractAdmin
{
    /**
     * Ruta para acceder a la vista de traducciones
     * @var string
     */
    protected $baseRoutePattern = 'translation';
    /**
     * Nombre de ruta para acceder a la vista de traducciones
     * @var string
     */
    protected $baseRouteName = 'translation';

    /**
     * Funcion configureRoutes
     * @param RouteCollection $collection
     */
    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->clearExcept(['list']);
    }
}
