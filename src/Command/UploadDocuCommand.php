<?php

namespace App\Command;

use \Exception;

use App\Entity\Document;
use App\Service\DocumentService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UploadDocuCommand extends Command
{
    /**
     * Name command
     * @var string
     */
    protected static $defaultName = 'app:uploadLocalToDocuflex';

    /**
     * @var object
     */
    private $em;

    /**
     * @var object
     */
    private $document;

    /**
     * uploadDocuCommand constructor.
     *.
     * @param EntityManagerInterface $em
     * @param DocumentService        $documentService
     */
    public function __construct(EntityManagerInterface $em, DocumentService $documentService)
    {
        $this->em       = $em;
        $this->document = $documentService;
        parent::__construct();
    }

    /**
     * Configure Command
     */
    protected function configure(): void
    {
        $this->setDescription('Upload files not sended in Dokuflex');
        parent::configure();
    }

    /**
     * Execute command to send local files into docuflex
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $folder = getenv('DOKU_UPLOAD_FOLDER');

        $output->writeln('Get local files');

        $filesToUpload = $this->em->getRepository(Document::class)->findBy(['idDoku' => null]);

        $ticket = $this->document->getTicket();
        if (false === $ticket) {
            $output->writeln('Not connection to docuflex');
            exit;
        } else {
            $output->writeln('Connection to docuflex');
        }

        foreach ($filesToUpload as $file) {
            $directoryUpload = getenv('FILES_UPLOAD').$file->getId().'/';
            $fileToUpload    = $directoryUpload.$file->getName();
            if (file_exists($fileToUpload)) {
                $upload   = $this->document->sendMultipartPostMessage($ticket, $folder, $fileToUpload, $file->getName());
                $response = json_decode($upload, 1);
                if ($response['res'] === 'ok') {
                    $dataMet = [];

                    // document Type add data and datameta
                    $dataMet['TIPO']          = (!is_null($file->getDocumentType())) ? $file->getDocumentType() : null;
                    $dataMet['EXPEDIENTE']    = ($file->getExpedient()) ? $file->getExpedient()->getId() : null;
                    $dataMet['COTIZACION']    = ($file->getQuotation()) ? $file->getQuotation() : null;
                    $dataMet['CLIENTE']       = ($file->getExpedient()) ? $file->getExpedient()->getClienteNIF() : null;
                    $dataMet['PROVEEDOR']     = ($file->getExpedient()) ? $file->getExpedient()->getPrescriptorCIF() : null;
                    $dataMet['OBSERVACIONES'] = '';

                    // metaData
                    $metadatos = $this->document->addMetaData($dataMet, ((!is_null($file->getExpedient()) ? $file->getExpedient()->getClienteNIF() : '')));

                    $dokuType     = getenv('DOKU_TYPE');
                    $metadata     = $this->document->updateMetadata($ticket, $response['nodeId'], $dokuType, json_encode($metadatos));
                    $responseMeta = json_decode($metadata, 1);
                    if ($responseMeta['res'] === 'ok') {
                        try {
                            $file->setIdDoku($response['nodeId']);
                            $this->em->persist($file);
                            $this->em->flush();
                            unlink($fileToUpload);
                            rmdir($directoryUpload);
                            $output->writeln('upload file '.$file->getName().' to dokuflex');
                        } catch (Exception $e) {
                            $output->writeln($e->getMessage());
                        }
                    }
                }
            } else {
                $output->writeln('Not exist file');
            }
        }
    }
}

