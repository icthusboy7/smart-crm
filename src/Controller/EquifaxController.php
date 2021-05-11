<?php
/**
 * Controlador equifax
 */
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class EquifaxController
 * @package App\Controller
 */
class EquifaxController extends AbstractController
{
    /**
     * Render de la vista Esquifax
     * @return \Symfony\Component\HttpFoundation\Response render
     */
    public function index()
    {
        return $this->render('alertas/alertas_equifax.html.twig');
    }
}
