<?php
/**
 * Created by PhpStorm.
 * User: sbernadas
 * Date: 23/07/2019
 * Time: 9:26
 */

namespace App\Utils\Sap;

use \Exception;
use \SoapClient;
use \SoapFault;

class CRMDatosOperacion extends SoapClient
{

    /**
     * @var string
     */
    private $pathWsdl = null;

    /**
     * CRMDatosOperacion constructor.
     *
     * @param string|null $pathWsdl
     * @param string|null $wsdl
     * @param string|null $entorno
     * @param bool        $cheto
     * @param array       $options
     *
     * @throws SoapFault
     */
    public function __construct(?string $pathWsdl = null, ?string $wsdl = null, ?string $entorno = null, bool $cheto = false, array $options = ['features' => 1, 'trace' => 1])
    {
        $this->pathWsdl = $wsdl;
        if (is_null($wsdl)) {
            $wsdl = $pathWsdl.'Z_WS_CRM_DATOS_OPERACIONN.wsdl';
        } else {
            if (!$cheto) {
                $arrContextOptions = stream_context_create(
                    [
                        'ssl' => [
                            'verify_peer'      => false,
                            'verify_peer_name' => false,
                        ],
                    ]
                );

                $fileTemp = file_get_contents($wsdl, false, $arrContextOptions);

                $fileTemp = str_replace(
                    '<wsp:UsingPolicy wsdl:required="true"',
                    '<wsp:UsingPolicy wsdl:required="false"',
                    $fileTemp
                );

                file_put_contents($pathWsdl.$entorno.'Z_WS_CRM_DATOS_OPERACION.wsdl', $fileTemp);
                $wsdl = $pathWsdl.$entorno.'Z_WS_CRM_DATOS_OPERACION.wsdl';
                chmod($wsdl, 0777);
            } else {
                $wsdl = $pathWsdl.'ZMF_CRM_DATOS_OPERACION.wsdl';
            }

            parent::__construct($wsdl, $options);
        }
    }

    /**
     * Rewrite __doReQuest of SoapClient
     *
     * @param string $request
     * @param string $location
     * @param string $action
     * @param int    $version
     * @param bool   $one_way
     *
     * @return bool|string
     *
     * @throws Exception
     */
    public function __doRequest($request, $location, $action, $version, $one_way = NULL)
    {
        // Call via Curl and use the timeout
        $curl = curl_init($location);
        curl_setopt($curl, CURLOPT_VERBOSE, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $request);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: text/xml']);
        $response = curl_exec($curl);
        if (curl_errno($curl)) {
            throw new Exception(curl_error($curl));
        }
        curl_close($curl);

        return ($response);
    }

    /**
     * @param string $coti
     * @param int    $eslinea
     *
     * @return mixed|null
     */
    public function ZWSCRMDatosOperacion(string $coti, int $eslinea = 0)
    {
        //construir respuesta

        $parametros = $eslinea === 1 ? [
            'IF_COMPRIMIR_JSON' => '',
            'IF_COTIZACION'     => '',
            'IF_DOC_VTAS_LINEA' => $coti,
        ] : [
            'IF_COMPRIMIR_JSON' => '',
            'IF_COTIZACION'     => $coti,
            'IF_DOC_VTAS_LINEA' => '',
        ];

        $resultado = null;

        try {
            $resultado = $this->__soapCall('Z_WS_CRM_DATOS_OPERACION', ['parameters' => $parametros]);
        } catch (Exception $e) {
        }

        return $resultado;
    }
}
