<?php

namespace App\Service;

use \DateTime;
use \Exception;
use \SoapFault;
use \stdClass;
use Psr\Log\LoggerInterface;

use App\Entity\ComercialCotizacion;
use App\Entity\ComercialExpediente;
use App\Entity\ComercialExpedienteCotizaciones;
use App\Entity\MasterQuotation;

use App\Utils\Sap\CRMDatosOperacion;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class SAPConnectorService
 */
class SAPConnectorService
{

    /**
     * @var object
     */
    private $em;

    /**
     * @var object
     */
    private $CRMDatosOperacion;

    /**
     * @var object
     */
    private $muroService;

    /**
     * @var object
     */
    private $user;

    /**
     * @var object
     */
    private $logger;
    /**
     * SAPConnectorService constructor.
     * @param EntityManagerInterface $em
     * @param CRMDatosOperacion      $CRMDatosOperacion
     * @param MuroService            $muroService
     * @param TokenStorageInterface  $tokenStorage
     * @param Logger                 $logger
     */
    public function __construct(EntityManagerInterface $em, CRMDatosOperacion $CRMDatosOperacion, MuroService $muroService, TokenStorageInterface $tokenStorage, LoggerInterface $logger)
    {
        $this->em                = $em;
        $this->CRMDatosOperacion = $CRMDatosOperacion;
        $this->muroService       = $muroService;
        $this->user              = $tokenStorage;
        $this->logger            = $logger;
    }

    /**
     * @param string|null $pathWsdl
     * @param string|null $wsdl
     * @param string|null $entorno
     *
     * @return CRMDatosOperacion|object
     *
     * @throws SoapFault
     */
    public function setParamsConnect(?string $pathWsdl = null, ?string $wsdl = null, ?string $entorno = null)
    {
        $this->CRMDatosOperacion = new CRMDatosOperacion($pathWsdl, $wsdl, $entorno);

        return $this->CRMDatosOperacion;
    }

    /**
     * Connect SAP
     *
     * @return bool
     */
    public function WSConnectSapDataOperation(): bool
    {
        try {
            $this->setParamsConnect(getenv('SAP_WSDL_PATH'), getenv('SAP_CRM_DATOS_OPERACION'));
        } catch (Exception $e) {
            $this->logger->error('Error: '.$e->getCode().' Description: '.$e->getMessage());
            
            return false;
        }

        return true;
    }

    /**
     * Check if num coti exist in SAPMasters
     *
     * @param string   $numCoti
     * @param int|null $exp
     *
     * @return string
     *
     * @throws Exception
     */
    public function WSCheckNumCoti(string $numCoti, ?int $exp = null): string
    {
        $correct = 'KO';

        if (strlen(trim($numCoti)) !== 8) {
            return $correct;
        }

        if (!$this->WSConnectSapDataOperation()) {
            $correct = (!is_null($this->em->getRepository(ComercialCotizacion::class)->findOneBy(['numCoti' => $numCoti])) ? 'OK' : 'KO');
            if ('dev' === getenv('APP_ENV') && 'KO' === $correct) {
                $correctBBDD = $this->em->getRepository(MasterQuotation::class)->findOneBy(['codigoCoti' => $numCoti]);

                $value = (empty($correctBBDD)) ? $correct : $this->addDataStdClass($correctBBDD, $exp);

                return $value;
            }

            if ('OK' === $correct && !is_null($exp)) {
                $this->addExpedienteCotizacion($numCoti, $exp);
            }

            return $correct;
        }

        $correctSap = $this->CRMDatosOperacion->ZWSCRMDatosOperacion($numCoti, 0);

        $response = $correctSap->EF_RESPUESTA;

        if ('NOK' === $response && 'dev' === getenv('APP_ENV')) {
            $correctBBDD = $this->em->getRepository(MasterQuotation::class)->findBy(['codigoCoti' => $numCoti]);
            if (!empty($correctBBDD)) {
                $correct = 'OK';
            }
        } elseif ('OK' === $response) {
            if ($this->addSapDataIntoQuotes(json_decode($correctSap->EF_DATOS))) {
                $correct = (!is_null($exp))
                    ? (($this->addExpedienteCotizacion($numCoti, $exp)) ? 'OK' : $correct)
                    : $response;
            }
        }

        return $correct;
    }

    /**
     * Check if num coti exist in SAPMasters
     *
     * @param string $numLinea
     *
     * @return array
     *
     * @throws Exception
     */
    public function WSCheckNumLinea(string $numLinea): array
    {
        $this->WSConnectSapDataOperation();

        $correctSap = $this->CRMDatosOperacion->ZWSCRMDatosOperacion($numLinea, 1);

        if ('OK' === $correctSap->EF_RESPUESTA) {
            return $correctSap->EF_DATOS;
        }

        return [];
    }

    /**
     * @param MasterQuotation $data
     * @param int|null        $exp
     *
     * @return string
     *
     * @throws Exception
     */
    public function addDataStdClass(MasterQuotation $data, ?int $exp = null): string
    {
        $dataSAP = new stdClass();

        $dataSAP->sCotizacion = new stdClass();
        $dataSAP->sDocVta     = new stdClass();

        $dataSAP->sDocVta->fCambioEstado  = '';
        $dataSAP->sCotizacion->cotizacion = $data->getCodigoCoti();
        $dataSAP->sCotizacion->plazo      = $data->getPlazo();
        $dataSAP->sCotizacion->inversion  = $data->getInversion();
        $dataSAP->sDocVta->estadoTxt      = $data->getEstado();
        $dataSAP->sCotizacion->fechaAlta  = DateTime::createFromFormat('d-m-Y', $data->getFecha());
        $dataSAP->sCotizacion->nifCli     = $data->getNifCliente();
        $dataSAP->sCotizacion->nomCli     = $data->getNombreCliente();
        $dataSAP->sCotizacion->cuota      = (float) $data->getTotalCuota();


        $correct = 'KO';
        try {
            if ($this->addSapDataIntoQuotes($dataSAP)) {
                $correct = (!is_null($exp))
                    ? (($this->addExpedienteCotizacion($data->getCodigoCoti(), $exp)) ? 'OK' : 'KO')
                    : 'KO';
            }

            return $correct;
        } catch (Exception $e) {
            $this->logger->error('Error: '.$e->getCode().' Description: '.$e->getMessage());

            return 'KO';
        }
    }

    /**
     * @param string $cotizacion
     * @param int    $expediente
     *
     * @return bool
     */
    public function addExpedienteCotizacion(string $cotizacion, int $expediente): bool
    {
        try {
            $dataCotExp = $this
                ->em
                ->getRepository(ComercialExpedienteCotizaciones::class)
                ->findOneBy(
                    [
                        'cotizacion'   => $cotizacion,
                        'expedienteID' => $expediente,
                    ]
                );
            if (is_null($dataCotExp)) {
                $newCotExp = new ComercialExpedienteCotizaciones();

                $newCotExp->setCotizacion($cotizacion)
                    ->setExpedienteID($this->em->getRepository(ComercialExpediente::class)->find($expediente))
                    ->setCreatedAt(new DateTime());

                $this->em->persist($newCotExp);
                $this->em->flush();

                $this->muroService->changeStatusWall(
                    $newCotExp->getExpedienteID(),
                    'Cotización',
                    $this->user->getToken()->getUser(),
                    null,
                    $newCotExp->getCotizacion()
                );
            }

            return true;
        } catch (Exception $e) {
            $this->logger->error('Error: '.$e->getCode().' Description: '.$e->getMessage());

            return false;
        }
    }

    /**
     * @param string $numCoti
     *
     * @return mixed|null
     */
    public function getQuoteData(string $numCoti)
    {
        try {
            return $this->CRMDatosOperacion->ZWSCRMDatosOperacion($numCoti, 0);
        } catch (Exception $e) {
            $this->logger->error('Error: '.$e->getCode().' Description: '.$e->getMessage());

            return 'NOK';
        }
    }

    /**
     * Save Sap Data into ComercialCotizacion
     * @param stdClass $dataSAP
     *
     * @return bool
     *
     * @throws Exception
     */
    public function addSapDataIntoQuotes(stdClass $dataSAP): bool
    {
        $quote = $this->em
            ->getRepository(ComercialCotizacion::class)
            ->findOneBy(
                [
                    'numCoti' => $dataSAP->sCotizacion->cotizacion,
                ]
            );

        if (empty($quote)) {
            $quote = new ComercialCotizacion();
            $quote->setCreatedAt(new DateTime('now'));
        }
        try {
            if ($dataSAP->sDocVta->fCambioEstado !== '') {
                $fechaEstado = DateTime::createFromFormat('Y-m-d', $dataSAP->sDocVta->fCambioEstado);
                $fechaEstado->setTime(0, 0, 0);
                $quote->setFechaEstado($fechaEstado);
            }

            $quote->setNumCoti($dataSAP->sCotizacion->cotizacion);
            $quote->setPlazo($dataSAP->sCotizacion->plazo);
            $quote->setCuota($dataSAP->sCotizacion->cuota);
            $quote->setInversion($dataSAP->sCotizacion->inversion);
            $quote->setEstado($dataSAP->sDocVta->estadoTxt);
            $quote->setSolicitanteNif($dataSAP->sCotizacion->nifCli);
            $quote->setSolicitanteNombre($dataSAP->sCotizacion->nomCli);
            $quote->setUpdatedAt(new DateTime('now'));

            $this->em->persist($quote);
            $this->em->flush();

            return true;
        } catch (Exception $e) {
            $this->logger->error('Error: '.$e->getCode().' Description: '.$e->getMessage());

            return false;
        }
    }
}
