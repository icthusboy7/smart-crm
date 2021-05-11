<?php

namespace App\Controller;

use App\Entity\ComercialExpediente;
use App\Entity\ComercialExpedienteCotizaciones;
use App\Entity\ComercialTask;
use App\Entity\Visit;

use App\Service\DataService;

use Psr\Container\ContainerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SmartQlikController extends AbstractController
{
    /**
     * @var ContainerInterface
     */
    protected $container;


    /**
     * @var
     */
    protected $dataService;

    /**
     * SmartQlikController constructor.
     * @param ContainerInterface $container
     * @param DataService        $dataService
     */
    public function __construct(ContainerInterface $container, DataService $dataService)
    {
        $this->container   = $container;
        $this->dataService = $dataService;
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function pipelinesDump(Request $request): Response
    {
        $headTitles   = $this->dataService->headTitles(
            $this->dataService->getFieldsTitles(new ComercialExpediente(), 'pipelinesDump')
        );
        $dataResponse = $this->dataService->getFields(
            $this->dataService->getEm()->getRepository(ComercialExpediente::class)->findAll(),
            $this->dataService->getFieldsTitles(new ComercialExpediente(), 'pipelinesDump'),
            'pipelinesDump'
        );

        $response = (getenv('APP_ENV') === 'dev' || ($request->get('user') === getenv('QLIK_USER') && $request->get('pass') === getenv('QLIK_PASS')))
            ? $headTitles.$dataResponse
            : '';

        return new Response($response);
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function visitsDump(Request $request): Response
    {

        $headTitles = $this->dataService->headTitles(
            $this->dataService->getFieldsTitles(new Visit(), 'visitDump')
        );

        // TODO - es tindrà que revisar l'entitat VISIT ja que no cuadra en lo que s'envia a qlik
        $arrayHeadFinal = [
            'Id',
            'CustomerID',
            'ProviderID',
            'Office',
            'DateIni',
            'Duration',
            'Observations',
            'Reason',
            'MOTIVOACCION',
            'Type',
            'Status',
            'Feedback',
            $this->dataService::VCREATEDAT ,
            $this->dataService::VUPDATEDAT ,
            $this->dataService::AUTORVISIT ,
            'CustomerCharge',
            'ProviderCharge',
            'Vertical',
            'NOMBRECLIENTE',
            'NOMBREPROVEEDOR',
        ];

        $dataResponse = $this->dataService->getFields(
            $this->dataService->getEm()->getRepository(Visit::class)->findAll(),
            $arrayHeadFinal,
            'visitDump'
        );

        $response = (getenv('APP_ENV') === 'dev' || ($request->get('user') === getenv('QLIK_USER') && $request->get('pass') === getenv('QLIK_PASS')))
            ? $headTitles.$dataResponse
            : '';

        return new Response($response);
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function quotesDump(Request $request): Response
    {
        $arrayFields  = ['ExpedienteID', 'cotizacion', 'createdAt', 'autor', 'idTarea', 'fechaTarea', 'fechacreacioncoti'];
        $headTitles   = $this->dataService->headTitles($arrayFields);
        $dataResponse = $this->dataService->getFieldsQuote(
            $this->dataService->getEm()->getRepository(ComercialExpedienteCotizaciones::class)->findQuoteWithExpedient(),
            $arrayFields,
            'quotesDump'
        );

        $response = (getenv('APP_ENV') === 'dev' || ($request->get('user') === getenv('QLIK_USER') && $request->get('pass') === getenv('QLIK_PASS')))
            ? $headTitles.$dataResponse
            : '';

        return new Response($response);
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function tasksDump(Request $request): Response
    {
        $arrayHeadTitles = [
            'Id',
            'Description',
            'CreatedAt',
            'Seen',
            $this->dataService::NIVEL,
            $this->dataService::GRUPO,
            'Expediente',
            'Cotización',
            'CreatedAt',
            'UpdatedAt',
            'AutorTask',
            'Type',
            $this->dataService::PETLINEAID,
            $this->dataService::TAREANOTI,
            $this->dataService::MOTIVOCANC,
        ];

        $headTitles   = $this->dataService->headTitles($arrayHeadTitles);
        $dataResponse = $this->dataService->getFields(
            $this->dataService->getEm()->getRepository(ComercialTask::class)->findAll(),
            $arrayHeadTitles,
            'taskDump'
        );

        $response = (getenv('APP_ENV') === 'dev' || ($request->get('user') === getenv('QLIK_USER') && $request->get('pass') === getenv('QLIK_PASS')))
            ? $headTitles.$dataResponse
            : '';

        return new Response($response);
    }
}
