<?php
/**
 * Controlador de subida de archivos desde Sonata Admin
 */
namespace App\Controller;

use Sonata\AdminBundle\Controller\CRUDController;

/**
 * Class UploadsController
 * @package App\Controller
 */
class UploadsController extends CRUDController
{
    /**
     * Extrae el listado de archivos del directorio .../files
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction()
    {
        $max_size = ini_get('upload_max_filesize');
        $directory_files = scandir("../files");
        return $this->renderWithExtraParams('admin/uploads.html.twig', ['max_size' => $max_size, 'directory_files' => $directory_files]);
    }
}
