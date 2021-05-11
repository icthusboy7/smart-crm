<?php
/**
 * Controlador de traducciones
 */
namespace App\Controller;

use Sepia\PoParser\SourceHandler\FileSystem;
use Sepia\PoParser\Parser;
use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\BrowserKit\Request;

/**
 * Class TranslationsController
 * @package App\Controller
 */
class TranslationsController extends CRUDController
{
    /**
     * Lista todos los string ID que se encuentran en el archivo de traducciones de ingles y sus
     * respectivas traducciones a los demás idiomas
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction()
    {
        $fileHandler_en = new FileSystem('../translations/messages.en.po');
        $fileHandler_ca = new FileSystem('../translations/messages.ca.po');
        $fileHandler_es = new FileSystem('../translations/messages.es.po');
        $fileHandler_pt = new FileSystem('../translations/messages.pt.po');
        $parser_en = new Parser($fileHandler_en);
        $parser_ca = new Parser($fileHandler_ca);
        $parser_es = new Parser($fileHandler_es);
        $parser_pt = new Parser($fileHandler_pt);
        $catalog_en = $parser_en->parse();
        $catalog_ca = $parser_ca->parse();
        $catalog_es = $parser_es->parse();
        $catalog_pt = $parser_pt->parse();

        $catalogs = array();
        $tmp_catalog = array();

        foreach ($catalog_en->getEntries() as $cat) {
            $id_cat = $cat->getMsgId();
            $ca = $catalog_ca->getEntry($id_cat);
            if ($ca != null) {
                $ca = $ca->getMsgStr();
            } else {
                $ca = null;
            }
            $es = $catalog_es->getEntry($id_cat);
            if ($es != null) {
                $es = $es->getMsgStr();
            } else {
                $es = null;
            }
            $pt = $catalog_pt->getEntry($id_cat);
            if ($pt != null) {
                $pt = $pt->getMsgStr();
            } else {
                $pt = null;
            }
            $tmp_catalog = array($cat->getMsgId(),$cat->getMsgStr(), $es, $ca, $pt);
            array_push($catalogs, $tmp_catalog);
        }
        return $this->renderWithExtraParams('admin/translations.html.twig', ['catalogs' => $catalogs]);
    }
}
