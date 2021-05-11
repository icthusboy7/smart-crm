<?php
/**
 * Controlador para actualizar las traducciones en archivos
 */
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Sepia\PoParser\SourceHandler\FileSystem;
use Sepia\PoParser\Parser;
use Sepia\PoParser\Catalog\Entry;
use Sepia\PoParser\Catalog\EntryFactory;
use Symfony\Component\HttpFoundation\Response;
use Sepia\PoParser\PoCompiler;
use Matrix\Exception;

/**
 * Class TranslationsUpdatesController
 * @package App\Controller
 */
class TranslationsUpdatesController extends AbstractController
{
    /**
     * Mediante un string ID actualiza las traducciones del archivo .po
     * @param Request $request
     * @return Response
     */
    public function updateTranslations(Request $request)
    {
        $this->updateTranslation('pt', $request->request->get('string_id_modal'), $request->request->get('string_pt_modal'));
        $this->updateTranslation('es', $request->request->get('string_id_modal'), $request->request->get('string_es_modal'));
        $this->updateTranslation('en', $request->request->get('string_id_modal'), $request->request->get('string_en_modal'));
        $this->updateTranslation('ca', $request->request->get('string_id_modal'), $request->request->get('string_ca_modal'));
        $response = new Response;
        return $response;
    }

    /**
     * Funcion de traduccion individual de strings, recibe un "locale"(en, es, ca, pt), el id del mensage y
     * la cadena traduccida para ese idioma y lo inserta en el archivo
     * @param $lang
     * @param $msgid
     * @param $msgstr
     * @return Response
     */
    public function updateTranslation($lang, $msgid, $msgstr)
    {

        $fileHandler = new FileSystem('../translations/messages.'.$lang.'.po');
        $parser = new Parser($fileHandler);
        $catalog = $parser->parse();
        $entry = $catalog->getEntry($msgid);
        if ($entry == null) {
            $catalog->addEntry(new Entry($msgid, $msgstr));
        } else {
            $entry->setMsgStr($msgstr);
        }
        $compiler = new PoCompiler();
        $fileHandler->save($compiler->compile($catalog));
    }
}
