<?php
/**
 * Controlador de informes
 */
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class InformeController
 * @package App\Controller
 */
class InformeController extends AbstractController
{
    /**
     * Render para la vista informes
     * @return \Symfony\Component\HttpFoundation\Response render
     */
    public function lastReports()
    {
        return $this->render('informes/ultimos_informes.html.twig');
    }
}
