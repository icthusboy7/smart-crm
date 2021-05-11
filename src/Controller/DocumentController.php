<?php
/**
 * Controlador para el dashboard de Area de comerciales
 */
namespace App\Controller;

// ENTITIES

//uploadDocument Entities
use App\Entity\Document;
use App\Entity\DocumentType;

// BUNDLES
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

// COMPONENTS
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;

// UTILS
use App\Service\DocumentService;

/**
 * Class DocumentController
 */
class DocumentController extends AbstractController
{
    /**
     * Documents
     * @var DocumentService
     */
    private $documents;

    /**
     * Translator
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * DashboardController constructor.
     * @param DocumentService     $documents
     * @param TranslatorInterface $translator
     */
    public function __construct(DocumentService $documents, TranslatorInterface $translator)
    {
        $this->documents  = $documents;
        $this->translator = $translator;
    }

    /**
     * Render de la pagina de subida de documentos
     * @return Response
     */
    public function document(): Response
    {
        $resultado = $this->getDoctrine()->getRepository(Document::class)->findAll();

        return $this->render('document/document.html.twig', ['result' => $resultado]);
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function downloadDocument(Request $request): Response
    {
        $file = $request->get('file');

        if ($file) {
            $fileResp = $this->documents->download($file);

            return new Response($fileResp, 200, ['Content-Type' => 'application/pdf', 'Content-Disposition' => 'attachment; filename="'.$file.'"']);
        }

        return new Response();
    }

    /**
     * Funcion de subida de documentos en el directorio de subida
     * @param Request $request
     *
     * @return Response
     */
    public function uploadDocument(Request $request): Response
    {
        foreach ($request->files as $file) {
            $expediente   = null;
            $cotizacion   = null;
            $office       = null;
            $customer     = null;
            $provider     = null;
            $obs          = null;
            $filename     = $file->getClientOriginalName();
            $documentType = 'documentType'.substr($filename, 0, strrpos($filename, '.'));
            $relation1    = 'relation1'.substr($filename, 0, strrpos($filename, '.'));
            $relation2    = 'relation2'.substr($filename, 0, strrpos($filename, '.'));
            $relationId1  = 'relationId1'.substr($filename, 0, strrpos($filename, '.'));
            $relationId2  = 'relationId2'.substr($filename, 0, strrpos($filename, '.'));
            $obsFile      = $request->get('obs'.substr($filename, 0, strrpos($filename, '.')));

            if ($obsFile) {
                $obs = $obsFile;
            }

            switch ($request->get($relation1)) {
                case '1':
                    $customer = $request->get($relationId1);
                    break;
                case '2':
                    $provider = $request->get($relationId1);
                    break;
                case '3':
                    $office = $request->get($relationId1);
                    break;
                default:
                    break;
            }

            switch ($request->get($relation2)) {
                case '4':
                    $expediente = $request->get($relationId2);
                    break;
                case '5':
                    $cotizacion = $request->get($relationId2);
                    break;
                default:
                    break;
            }

            try {
                $this->documents->upload($file, $request->get($documentType), $expediente, $cotizacion, $office, $customer, $provider, $obs);

                return new Response();
            } catch (\Exception $e) {
                return new Response($this->translator->trans('Upload document error'), 400);
            }
        }
    }

    /**
     * Buscar el tipo de documento
     *
     * @return JsonResponse
     */
    public function findDocumentType(): JsonResponse
    {
        $allTypes = [];
        //busca todos los tipos de documento
        $allDocumentTypes = $this->getDoctrine()->getRepository(DocumentType::class)->findAll();
        if ($allDocumentTypes) {
            foreach ($allDocumentTypes as $type) {
                $types = ['id' => $type->getId(), 'code' => $type->getCode(), 'description' => $type->getDescription()];
                array_push($allTypes, $types);
            }
        }

        return new JsonResponse($allTypes);
    }
}
