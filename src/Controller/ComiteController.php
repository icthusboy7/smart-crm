<?php
/**
 * Controlador Comite
 */
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class ComiteController
 * @package App\Controller
 */
class ComiteController extends AbstractController
{
    /**
     * Acceso a la vista detalles_comite
     * @return \Symfony\Component\HttpFoundation\Response render
     */
    public function detalleComite()
    {
        return $this->render('comites/ver_detalle_comite.html.twig');
    }
}
