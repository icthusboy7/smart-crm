<?php

namespace App\Service;

use \DateTime;
use \Exception;

use App\Entity\ComercialExpediente;
use App\Entity\Document;
use App\Entity\DocumentType;
use App\Entity\MasterCustomer;
use App\Entity\MasterOffice;
use App\Entity\MasterProvider;
use App\Entity\MasterQuotation;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\Security;

/**
 * Class DocumentService
 */
class DocumentService
{
    /**
     * @var object
     */
    private $em;

    /**
     * @var Security
     */
    private $security;

    /**
     * @var object
     */
    private $dataDocument;

    /**
     * Parametro Entity manager para obtener doctrine y parámetro de seguridad del symfony para obtener userId
     * DocumentService constructor.
     * @param EntityManagerInterface $em
     * @param Security               $security
     */
    public function __construct(EntityManagerInterface $em, Security $security)
    {
        $this->dataDocument = new Document();
        $this->em           = $em;
        $this->security     = $security;
    }

    /**
     * New Data Document
     * @return Document|object
     */
    public function newDataDocument(): Document
    {
        $this->dataDocument = new Document();

        return $this->dataDocument;
    }

    /**
     * Get Data Document
     * @return Document|object
     */
    public function getDataDocument(): Document
    {
        return $this->dataDocument;
    }

    /**
     * Download files from DOKUFLEX
     * @param string $file
     *
     * @return mixed
     */
    public function download(string $file): string
    {
        $ticket   = $this->getTicket();
        $document = $this->em->getRepository(Document::class)->findOneBy(['name' => $file]);
        $fileResp = $this->downloadDoku($ticket, $document->getIdDoku());

        return $fileResp;
    }

    /**
     * Download files into wall from DOKUFLEX
     *
     * @param int    $idDocument
     * @param string $file
     *
     * @return false|string
     */
    public function downloadWall(int $idDocument, string $file)
    {
        $ticket   = $this->getTicket();
        $document = $this->em->getRepository(Document::class)->findOneBy(['id' => $idDocument]);
        $fileResp = $this->downloadDoku($ticket, $document->getIdDoku());

        return $fileResp;
    }


    /**
     * Upload file to DOKUFLEX
     * @param object      $file
     * @param string      $type
     * @param string|null $expedient
     * @param string|null $quotation
     * @param string|null $office
     * @param string|null $customer
     * @param string|null $provider
     * @param string|null $obs
     *
     * @return bool
     *
     * @throws Exception
     */
    public function upload(object $file, string $type, ?string $expedient = null, ?string $quotation = null, ?string $office = null, ?string $customer = null, ?string $provider = null, ?string $obs = null): bool
    {
        $filename = $file->getClientOriginalName();

        /**
         * GET TICKET DOKUFLEX
         */
        $ticket = $this->getTicket();
        if (false === $ticket) {
            return false;
        }

        /**
         * UPLOAD DOKUFLEX
         */
        $folder   = getenv('DOKU_UPLOAD_FOLDER');
        $upload   = $this->sendMultipartPostMessage($ticket, $folder, $file->getRealPath(), $filename);
        $response = json_decode($upload, 1);
        if ($response['res'] !== 'ok') {
            return false;
        }

        $user    = $this->security->getUser();
        $data    = [];
        $dataMet = [];
        $dataNif = '';

        /**
         * FileName
         */
        $data['NOMBRE'] = $filename;

        /**
         * TypeDocument
         */
        if (isset($type)) {
            $this->documentType(
                $this->em->getRepository(DocumentType::class)->findOneBy(['id' => $type]),
                $dataMet,
                $data
            );
        }

        /**
         * Expediente
         */
        if (isset($expedient)) {
            $this->addExpedienteMeta(
                $this->em->getRepository(ComercialExpediente::class)->findOneBy(['id' => $expedient]),
                $dataMet,
                $data
            );
        }

        /**
         * cotizacion
         */
        if (isset($quotation)) {
            $this->addQuoteMeta(
                $quotation,
                $dataMet,
                $data
            );
        }

        /**
         * oficina
         */
        if (isset($office)) {
            $masterOffice = $this->em->getRepository(MasterOffice::class)->findOneBy(['id' => $office]);
            if ($masterOffice) {
                $dataMet['OFICINA'] = $masterOffice->getCodigo();
                $data['OFICINA']    = $masterOffice->getCodigo();
            }
        }

        /**
         * cliente
         */
        if (isset($customer)) {
            $masterCustomer = $this->em->getRepository(MasterCustomer::class)->findOneBy(['id' => $customer]);
            if ($masterCustomer) {
                $dataMet['CLIENTE'] = $masterCustomer->getNif();
                $data['CLIENTE']    = $masterCustomer->getNif();
                $dataNif            = $masterCustomer->getNif();
            }
        }

        /**
         * proveedor
         */
        if (isset($provider)) {
            $masterProvider = $this->em->getRepository(MasterProvider::class)->findOneBy(['id' => $provider]);
            if ($masterProvider) {
                $dataMet['PROVEEDOR'] = $masterProvider->getNif();
                $data['PROVEEDOR']    = $masterProvider->getNif();
                $dataNif              = $masterProvider->getNif();
            }
        }

        /**
         * observaciones
         */
        if (isset($obs)) {
            $dataMet['OBSERVACIONES'] = $obs;
            $data['OBSERVACIONES']    = $obs;
        }

        $data['IDDOKU'] = $response['nodeId'];
        $data['USER']   = $user;

        /**
         * Metadatos
         */
        $metadatos = $this->addMetaData($dataMet, $dataNif);

        $dokuType     = getenv('DOKU_TYPE');
        $metadata     = $this->updateMetadata($ticket, $response['nodeId'], $dokuType, json_encode($metadatos));
        $responseMeta = json_decode($metadata, 1);

        if ($responseMeta['res'] !== 'ok') {
            return false;
        }

        /**
         * Document Entity
         */
        $this->dataDocument = new Document();
        $this->dataDocument->setData($data);
        try {
            $this->em->persist($this->dataDocument);
        } catch (Exception $e) {
            return false;
        }
        $this->em->flush();

        return true;
    }

    /**
     * Get ticket from DOKUFLEX
     * @return mixed
     */
    public function getTicket()
    {
        try {
            $ticket = $this->getTicketLogin();
            $ticket = $ticket['ticket'];

            return (is_null($ticket) ? false : (('' === $ticket) ? false : $ticket));
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Set metadata document type
     *
     * @param DocumentType $documentType
     * @param array        $dataMet
     * @param array        $data
     */
    public function documentType(DocumentType $documentType, array &$dataMet, array &$data): void
    {
        $documentTypeName = ($documentType) ? $documentType->getCode() : null;
        $typeText         = (!is_null($documentTypeName))
            ? (('ANOTHER' === $documentTypeName)
                ? $documentType->getDescription()
                : $documentType->getCode().' '.$documentType->getDescription())
            : null;
        $dataMet['TIPO']  = (!is_null($typeText)) ? $typeText : null;
        $data['TIPO']     = ($documentType) ? $documentType : null;
    }

    /**
     * Add metadata expedient
     *
     * @param ComercialExpediente $comercialExpediente
     * @param array               $dataMet
     * @param array               $data
     */
    public function addExpedienteMeta(ComercialExpediente $comercialExpediente, array &$dataMet, array &$data): void
    {
        $dataMet['EXPEDIENTE'] = ($comercialExpediente) ? $comercialExpediente->getId() : null;
        $data['EXPEDIENTE']    = ($comercialExpediente) ? $comercialExpediente : null;
    }

    /**
     * Add metadata quote
     *
     * @param string $comercialQuote
     * @param array  $dataMet
     * @param array  $data
     */
    public function addQuoteMeta(string $comercialQuote, array &$dataMet, array &$data): void
    {
        $dataCotizacion = $this->em->getRepository(MasterQuotation::class)->findOneBy(['id' => $comercialQuote]);

        $dataMet['COTIZACION'] = $data['COTIZACION'] = ($dataCotizacion) ? $dataCotizacion->getCodigoCoti() : null;
    }

    /**
     * Add MetaData
     *
     * @param array  $dataMet
     * @param string $dataNif
     *
     * @return array
     */
    public function addMetaData(array $dataMet, ?string $dataNif): array
    {
        return ($dataNif ? ['DESCRIPCION' => json_encode($dataMet), 'NIF' => json_encode($dataNif), ] : ['DESCRIPCION' => json_encode($dataMet), ]);
    }

    /**
     * Get ticket to login Dokuflex
     * @return false|mixed|string|null
     */
    public function getTicketLogin()
    {
        $user = getenv('DOKU_USER');
        $pass = getenv('DOKU_PASS');
        $url  = getenv('DOKU_URL_TICKET');
        $data = ['emailAddress' => $user, 'password' => $pass];

        $options = [
            'http' => [
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query($data),
            ],
            'ssl'  => [
                'verify_peer'      => false,
                'verify_peer_name' => false,
            ],
        ];

        $context = stream_context_create($options);

        $result = file_get_contents($url, false, $context);

        $result = json_decode($result, true);

        return $result = ($result === false ? null : $result);
    }

    /**
     * @param string $ticket
     * @param string $folder
     * @param string $fileName
     * @param string $originalName
     * @param bool   $newVersion
     *
     * @return false|string
     */
    public function sendMultipartPostMessage(string $ticket, string $folder, string $fileName, string $originalName, bool $newVersion = false)
    {
        //echo "send_multipart_post_message";die();
        $url  = getenv('DOKU_URL_UPLOAD');
        $eol  = "\r\n";
        $data = '';
        $file = file_get_contents($fileName);

        $dataValues = [
            'ticket'     => $ticket,
            'groupId'    => getenv('DOKU_GROUPID'),
            'folderId'   => $folder,
            'newVersion' => $newVersion,
        ];

        $mimeBoundary = md5(time());
        $data        .= '--'.$mimeBoundary.$eol;
        foreach ($dataValues as $key => $value) {
            $data .= 'Content-Disposition: form-data; name="'.$key.'"'.$eol.$eol;
            $data .= $value.$eol;
            $data .= '--'.$mimeBoundary.$eol;
        }

        $nombre    = $originalName;
        $nombreTmp = explode('.', $nombre);
        $nombre    = $nombreTmp[0].'_'.uniqid().'.'.$nombreTmp[1];

        $data .= 'Content-Disposition: form-data; name="file"; filename="'.$nombre.'"'.$eol;
        $data .= 'Content-Type: application/json'.$eol;
        $data .= 'Content-Transfer-Encoding: 8bit'.$eol.$eol;

        $data .= $file.$eol;
        $data .= '--'.$mimeBoundary.'--'.$eol.$eol;

        $params = [
            'http' => [
                'method'  => 'POST',
                'header'  => 'Content-Type: multipart/form-data; boundary='.$mimeBoundary,
                'content' => $data,
            ],
            'ssl'  => [
                'verify_peer'      => false,
                'verify_peer_name' => false,
            ],
        ];

        $ctx      = stream_context_create($params);
        $response = file_get_contents($url, FILE_TEXT, $ctx);

        return $response;
    }

    /**
     * @param UploadedFile $filename
     *
     * @return Document|bool|object
     *
     * @throws Exception
     */
    public function docSaveTmpLocalPath(UploadedFile $filename)
    {
        $name = $filename->getClientOriginalName();

        $data = file_get_contents($filename->getPathname());

        // Make a new name of file into local for not repeat
        $nameFile      = pathinfo($name, PATHINFO_FILENAME);
        $extensionFile = pathinfo($name, PATHINFO_EXTENSION);
        $newNameFile   = $nameFile.'.'.$extensionFile;

        $this->dataDocument->setName($newNameFile);

        try {
            $this->em->persist($this->dataDocument);

            $this->em->flush();

            $tmpdir = '..'.DIRECTORY_SEPARATOR.getenv('FILES_UPLOAD').$this->dataDocument->getId().DIRECTORY_SEPARATOR;

            if (!mkdir($tmpdir, 0755, true)) {
                return false;
            }
            // Upload the file in the temp folder
            $newPathFile = $tmpdir.$newNameFile;

            file_put_contents($newPathFile, $data);

        } catch (Exception $e) {
            return false;
        }

        return $this->dataDocument;
    }

    /**
     * @param string $ticket
     * @param string $uuid
     * @param string $documenttype
     * @param string $json
     *
     * @return false|string|null
     */
    public function updateMetadata(string $ticket, string $uuid, string $documenttype, string $json)
    {
        //echo "send_multipart_post_message";die();

        $url = getenv('DOKU_URL_UPDATE');

        $data = [
            'ticket'       => $ticket,
            'uuid'         => $uuid,
            'documentType' => $documenttype,
            'data'         => $json,
        ];

        $options = [
            'http' => [
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query($data),
            ],
            'ssl'  => [
                'verify_peer'      => false,
                'verify_peer_name' => false,
            ],
        ];

        $context = stream_context_create($options);
        $result  = file_get_contents($url, false, $context);

        return $result = ($result === false ? null : $result);
    }

    /**
     * @param string $ticket
     * @param string $idFile
     *
     * @return false|string
     */
    public function downloadDoku(string $ticket, string $idFile)
    {
        $user       = getenv('DOKU_USER');
        $pass       = getenv('DOKU_PASS');
        $url        = getenv('DOKU_URL_VIEW');
        $specialKey = getenv('DOKU_URL_SPECIALKEY');

        $data = [
            'emailAddress' => $user,
            'password'     => $pass,
        ];

        $options = [
            'http' => [
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'GET',
                'content' => http_build_query($data),
            ],
            'ssl'  => [
                'verify_peer'      => false,
                'verify_peer_name' => false,
            ],
        ];

        $context = stream_context_create($options);

        try {
            $file = file_get_contents($url.$ticket.'/'.$idFile.$specialKey, false, $context);

            return $file;
        } catch (\Exception $e) {
            if ($e->getCode() === 404) {
                return false;
            }
        }
    }
}
