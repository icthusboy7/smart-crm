<?php
/**
 * Controlador de Operaciones
 */
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class OperacionController
 * @package App\Controller
 */
class OperacionController extends AbstractController
{
    /**
     * Devuelve la vista de operaciones
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index()
    {
        return $this->render('operaciones/listar_operaciones.html.twig');
    }
}
