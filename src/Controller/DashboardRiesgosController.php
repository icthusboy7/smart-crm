<?php
/**
 * Controlador de riesgos
 */
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class DashboardRiesgosController
 * @package App\Controller
 */
class DashboardRiesgosController extends AbstractController
{
    /**
     * Render de la vista listar operaciones
     * @return \Symfony\Component\HttpFoundation\Response render
     */
    public function index()
    {
        return $this->render('area-riesgos/listar_operaciones.html.twig');
    }
}
