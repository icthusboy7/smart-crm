<?php

namespace App\Service;

use \DateTime;
use \Exception;

use App\Entity\ComercialCotizacion;
use App\Entity\ComercialExpediente;
use App\Entity\ComercialExpedienteCotizaciones;
use App\Entity\ComercialMuro;
use App\Entity\ComercialMuroAdjuntos;
use App\Entity\ComercialTask;
use App\Entity\ComercialTaskType;
use App\Entity\CommercialOffice;
use App\Entity\CommercialResponsable;
use App\Entity\Document;
use App\Entity\DocumentType;
use App\Entity\User;

use Doctrine\ORM\EntityManagerInterface;

use Psr\Container\ContainerInterface;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class MuroService
{
    public const NOTIFICACION = 1;
    public const ACCION       = 2;
    public const STATUS       = 4;
    public const MIMETYPES    = [
        'jpg'  => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'gif'  => 'image/gif',
        'png'  => 'image/png',
        'tiff' => 'image/tiff',
        'bmp'  => 'image/bmp',
        'doc'  => 'application/msword',
        'xls'  => 'application/msexcel',
        'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'xltx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.template',
        'potx' => 'application/vnd.openxmlformats-officedocument.presentationml.template',
        'ppsx' => 'application/vnd.openxmlformats-officedocument.presentationml.slideshow',
        'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        'sldx' => 'application/vnd.openxmlformats-officedocument.presentationml.slide',
        'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'dotx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.template',
        'xlsm' => 'application/vnd.ms-excel.addin.macroEnabled.12',
        'xlsb' => 'application/vnd.ms-excel.sheet.binary.macroEnabled.12',
        'pdf'  => 'application/pdf',
        'ppt'  => 'application/mspowerpoint',
        'pps'  => 'application/mspowerpoint',
        'psd'  => 'application/octet-stream',
        'txt'  => 'text/plain',
    ];

    /**
     * @var object
     */
    private $em;

    /**
     * @var object
     */
    private $translator;

    /**
     * @var object
     */
    public $muroData;

    /**
     * @var object
     */
    public $logger;

    /**
     * @var object
     */
    public $router;

    /**
     * @var object
     */
    public $container;

    /**
     * MuroService constructor.
     * @param EntityManagerInterface $em
     * @param TranslatorInterface    $translator
     * @param UrlGeneratorInterface  $router
     * @param ContainerInterface     $container
     * @param LoggerInterface|null   $logger
     *
     * @throws Exception
     */
    public function __construct(EntityManagerInterface $em, TranslatorInterface $translator, UrlGeneratorInterface $router, ContainerInterface $container, ?LoggerInterface $logger = null)
    {
        $this->em         = $em;
        $this->translator = $translator;
        $this->router     = $router;
        $this->muroData   = new ComercialMuro();
        $this->logger     = $logger;
        $this->container  = $container;
    }

    /**
     * @param string                   $class
     * @param array                    $toSearch
     * @param ComercialExpediente|null $objExpedienteCoti
     *
     * @return array
     *
     * @throws Exception
     */
    public function getMuro(string $class, array $toSearch, ?ComercialExpediente $objExpedienteCoti): array
    {
        $muroSearch   = [];
        $messagesWall = null;
        $arrayQuotes  = [];

        if (null !== $toSearch['idExpediente']) {
            $muroSearch['expediente'] = $toSearch['idExpediente'];
            $pipelineQuotes           = $objExpedienteCoti->getCotizaciones();

            foreach ($pipelineQuotes as $pipelineQuote) {
                array_push($arrayQuotes, $pipelineQuote->getCotizacion());
            }
        }

        if (0 !== $toSearch['idCotizacion']) {
            unset($muroSearch['expediente']);
            $arrayQuotes              = [];
            $muroSearch['cotizacion'] = $toSearch['idCotizacion'];
        }

        if (0 !== $toSearch['idTarea']) {
            $muroSearch['tareaPadre'] = $toSearch['idTarea'];
            $taskWall                 = $this->getTask($toSearch['idTarea']);
        }

        $messagesWall = (empty($arrayQuotes))
            ? $this->em->getRepository($class)->findByPipeLinesAndProvision($muroSearch, ['createdAt' => 'ASC'])
            : $this->em->getRepository($class)->findByJoinQuotes($muroSearch['expediente'], $arrayQuotes);

        // Buscamos la cotización en la entidad de Cotizaciones de Expedientes y cogemos la fecha de creación de la asociación
        (isset($objExpedienteCoti)) ? $createdKey = $objExpedienteCoti->getCreatedAt() : $createdKey = new DateTime('now');

        $mensajesMuro['mensageTarea']       =
        $mensajesMuro['mensajesPetisCotis'] =
        $mensajesMuro['mensageAdjunto']     =
        $mensajesMuro['mensageMuro']        = [];
        // Construimos el array de mensajes e introducimos la fecha de creación como el primero
        $mensajesMuro['mensageMuro'][$createdKey->format('YmdHis').'0'] = [
            'id'       => '0',
            'type'     => 'statusMessage',
            'tipo'     => self::STATUS,
            'tipoID'   => 3,
            'message'  => 'start',
            'adjuntos' => [],
        ];

        $auxKey = 0;
        foreach ($messagesWall as $message) {
            // Contruimos la clave en base a la fecha y hora (+ contador para evitar sobreescritura)

            do {
                $auxKey++;
                $key = $message->getCreatedAt()->format('YmdHis');
            } while (isset($mensajesMuro[$key.$auxKey]));

            // close
            $cerrando  = null;
            $respuesta = null;

            if (!is_null($message->getCerradoPor())) {
                if (!isset($cerrando[$message->getCerradoPor()->getId()]) && !is_null($message->getMotivoCanc())) {
                    $cerrando = $this->setCloseArray($message);
                }

                if (!isset($cerrando[$message->getCerradoPor()->getId()])
                    && is_null($message->getMotivoCanc())
                    && !is_null($message->getCerradoPor()->getId())
                ) {
                    $respuesta = $this->setCloseArray($message);
                }
                // Miramos si hay adjuntos
                $objAdjuntos = $this->em->getRepository(ComercialMuroAdjuntos::class)->findBy([
                    'muro' => $message->getCerradoPor()->getId(),
                ]);

                if (!empty($objAdjuntos)) {
                    foreach ($objAdjuntos as $objAdjunto) {
                        if (!is_null($cerrando)) {
                            array_push($cerrando, [
                                'id'       => $objAdjunto->getId(),
                                'idMuro'   => $objAdjunto->getMuro()->getId(),
                                'ext'      => $objAdjunto->getExt(),
                                'filename' => $objAdjunto->getFilename(),
                            ]);
                        } elseif (!is_null($respuesta)) {
                            array_push($respuesta, [
                                'id'       => $objAdjunto->getId(),
                                'idMuro'   => $objAdjunto->getMuro()->getId(),
                                'ext'      => $objAdjunto->getExt(),
                                'filename' => $objAdjunto->getFilename(),
                            ]);
                        }
                    }
                }
            }

            // Add message into array tab message wall
            $mensajesMuro['mensageMuro'][$key.$auxKey] = [
                'id'          => $message->getId(),
                'type'        => 'statusMessage',
                'tipo'        => strtolower($message->getTipo()),
                'tipoID'      => (1 === $message->getTipo())
                    ? self::NOTIFICACION
                    : $this->getTaskWall($message->getId()),
                'autor'       => $message->getAutor(),
                'assignado'   => $message->getResponsable(),
                'message'     => $message->getMissatge(),
                'motivo_canc' => $message->getMotivoCanc(),
                'adjuntos'    => [],
                'cerrado_por' => $cerrando,
                'respuesta'   => $respuesta,
            ];

            // add atthach files
            $objAdjuntos = $this->em->getRepository(ComercialMuroAdjuntos::class)->findBy(['muro' => $message->getId()]);

            $mensajesMuro['mensageAdjunto'][$key.$auxKey]['adjuntos'] = [];

            if (!empty($objAdjuntos)) {
                foreach ($objAdjuntos as $objAdjunto) {
                    $mensajesMuro['mensageAdjunto'][$key.$auxKey]['message'] = $message->getMissatge();
                    $mensajesMuro['mensageAdjunto'][$key.$auxKey]['idMuro']  = $objAdjunto->getMuro()->getId();

                    $adjuntar = [
                        'id'       => $objAdjunto->getId(),
                        'idMuro'   => $objAdjunto->getMuro()->getId(),
                        'ext'      => $objAdjunto->getExt(),
                        'filename' => $objAdjunto->getFilename(),
                    ];
                    array_push($mensajesMuro['mensageAdjunto'][$key.$auxKey]['adjuntos'], $adjuntar);
                    array_push($mensajesMuro['mensageMuro'][$key.$auxKey]['adjuntos'], $adjuntar);
                }
            }

            // If is task open and in wall message tab
            ((int) $mensajesMuro['mensageMuro'][$key.$auxKey]['tipo'] === self::ACCION && is_null($message->getCerradoPor())) ? $this->setTaskWall($mensajesMuro, $key, $auxKey) : null;
        }

        return $mensajesMuro;
    }

    /**
     * @param string $class
     * @param array  $toSearch
     *
     * @return array
     */
    public function getMuroTask(string $class, array $toSearch): array
    {
        $muroSearch                             =
        $messagesTaskWall['mensageTarea']       =
        $messagesTaskWall['mensajesPetisCotis'] =
        $messagesTaskWall['mensageAdjunto']     =
        $messagesTaskWall['mensageMuro']        = [];
        $attachTaskWall                         = [];

        if (0 !== $toSearch['idTarea']) {
            $muroSearch['tareaPadre'] = $toSearch['idTarea'];
            $taskWall                 = $this->getTask($toSearch['idTarea']);
        }

        $messagesWall = $this->em->getRepository(ComercialMuro::class)->findBy($muroSearch, ['createdAt' => 'ASC']);
        if (0 !== $toSearch['idTarea']) {
            $auxKey    = 1;
            $key       = $taskWall->getCreatedAt()->format('YmdHis');
            $cerrando  = null;
            $respuesta = null;

            // Construimos el array de mensajes e introducimos la fecha de creación como el primero
            // Add message into array tab message wall
            if (!is_null($taskWall->getComercialMuro()) && !is_null($taskWall->getComercialMuro()->getCerradoPor())) {
                $cerrando = $this->setCloseArray($taskWall->getComercialMuro());
            }

            if (!is_null($taskWall->getComercialMuro()) && is_null($taskWall->getComercialMuro()->getMotivoCanc()) && !is_null($taskWall->getComercialMuro()->getCerradoPor())) {
                $respuesta = $this->setCloseArray($taskWall->getComercialMuro());
            }

            $messagesTaskWall['mensageMuro'][$key.'0'] = [
                'id'       => '0',
                'type'     => 'statusMessage',
                'tipo'     => self::STATUS,
                'tipoID'   => 3,
                'message'  => 'start',
                'adjuntos' => [],
            ];

            $messagesTaskWall['mensageMuro'][$key.$auxKey] = [
                'id'          => $taskWall->getId(),
                'type'        => 'statusMessage',
                'tipo'        => self::ACCION,
                'tipoID'      => $taskWall,
                'autor'       => $taskWall->getCreatedBy(),
                'assignado'   => $taskWall->getResponsible(),
                'message'     => $taskWall->getDescription(),
                'cerrado_por' => $cerrando,
                'respuesta'   => $respuesta,
            ];

            $messagesTaskWall['mensageAdjunto'][$key.$auxKey]['adjuntos'] = [];
            $messagesTaskWall['mensageMuro'][$key.$auxKey]['adjuntos']    = [];

            if (!is_null($taskWall->getComercialMuro())) {
                $objAdjuntos = $this->em->getRepository(ComercialMuroAdjuntos::class)
                    ->findBy(['muro' => $taskWall->getComercialMuro()->getId()]);
                if (!empty($objAdjuntos)) {
                    $messagesTaskWall['mensageAdjunto'][$key.$auxKey]['idMuro']  = $taskWall->getComercialMuro()
                        ->getId();
                    $messagesTaskWall['mensageAdjunto'][$key.$auxKey]['message'] = $taskWall->getComercialMuro()
                        ->getMissatge();
                    foreach ($objAdjuntos as $objAdjunto) {
                        $attached = [
                            'id'       => $objAdjunto->getId(),
                            'idMuro'   => $objAdjunto->getMuro()->getId(),
                            'ext'      => $objAdjunto->getExt(),
                            'filename' => $objAdjunto->getFilename(),
                        ];
                        array_push($messagesTaskWall['mensageAdjunto'][$key.$auxKey]['adjuntos'], $attached);
                        array_push($messagesTaskWall['mensageMuro'][$key.$auxKey]['adjuntos'], $attached);
                    }
                }
            }

            ((int) $messagesTaskWall['mensageMuro'][$key.$auxKey]['tipo'] === self::ACCION && is_null($taskWall->getDeletedBy()))
                ? $this->assingTallWall($messagesTaskWall, $key, $auxKey, $taskWall)
                : null;

            $auxKey = 2;
            foreach ($messagesWall as $message) {
                // Contruimos la clave en base a la fecha y hora (+ contador para evitar sobreescritura)
                do {
                    $auxKey++;
                    $key = $message->getCreatedAt()->format('YmdHis');
                } while (isset($messagesTaskWall[$key.$auxKey]));

                // close
                $cerrando  = null;
                $respuesta = null;

                if (!is_null($message->getCerradoPor())) {
                    if (!isset($cerrando[$message->getCerradoPor()->getId()]) && !is_null($message->getMotivoCanc())) {
                        $cerrando = $this->setCloseArray($message);
                    }

                    if (!isset($cerrando[$message->getCerradoPor()->getId()])
                        && is_null($message->getMotivoCanc())
                        && !is_null($message->getCerradoPor()->getId())
                    ) {
                        $respuesta = $this->setCloseArray($message);
                    }
                    // Miramos si hay adjuntos
                    $objAdjuntos = $this->em->getRepository(ComercialMuroAdjuntos::class)->findBy([
                        'muro' => $message->getCerradoPor()->getId(),
                    ]);

                    if (!empty($objAdjuntos)) {
                        foreach ($objAdjuntos as $objAdjunto) {
                            if (!is_null($cerrando)) {
                                array_push($cerrando, [
                                    'id'       => $objAdjunto->getId(),
                                    'idMuro'   => $objAdjunto->getMuro()->getId(),
                                    'ext'      => $objAdjunto->getExt(),
                                    'filename' => $objAdjunto->getFilename(),
                                ]);
                            } elseif (!is_null($respuesta)) {
                                array_push($respuesta, [
                                    'id'       => $objAdjunto->getId(),
                                    'idMuro'   => $objAdjunto->getMuro()->getId(),
                                    'ext'      => $objAdjunto->getExt(),
                                    'filename' => $objAdjunto->getFilename(),
                                ]);
                            }
                        }
                    }
                }
                // Add message into array tab message wall
                $messagesTaskWall['mensageMuro'][$key.$auxKey] = [
                    'id'          => (1 === $message->getTipo() || 4 === $message->getTipo())
                        ? $message->getId()
                        : $this->getTaskWall($message->getId())->getId(),
                    'type'        => 'statusMessage',
                    'tipo'        => strtolower($message->getTipo()),
                    'tipoID'      => (1 === $message->getTipo())
                        ? self::NOTIFICACION
                        : $this->getTaskWall($message->getId()),
                    'autor'       => $message->getAutor(),
                    'assignado'   => $message->getResponsable(),
                    'message'     => $message->getMissatge(),
                    'motivo_canc' => $message->getMotivoCanc(),
                    'adjuntos'    => [],
                    'cerrado_por' => $cerrando,
                    'respuesta'   => $respuesta,
                ];

                // add atthach files
                $objAdjuntos = $this->em->getRepository(ComercialMuroAdjuntos::class)->findBy(['muro' => $message->getId()]);

                $messagesTaskWall['mensageAdjunto'][$key.$auxKey]['adjuntos'] = [];

                if (!empty($objAdjuntos)) {
                    foreach ($objAdjuntos as $objAdjunto) {
                        $messagesTaskWall['mensageAdjunto'][$key.$auxKey]['message'] = $message->getMissatge();
                        $messagesTaskWall['mensageAdjunto'][$key.$auxKey]['idMuro']  = $objAdjunto->getMuro()->getId();

                        $adjuntar = [
                            'id'       => $objAdjunto->getId(),
                            'idMuro'   => $objAdjunto->getMuro()->getId(),
                            'ext'      => $objAdjunto->getExt(),
                            'filename' => $objAdjunto->getFilename(),
                        ];
                        array_push($messagesTaskWall['mensageAdjunto'][$key.$auxKey]['adjuntos'], $adjuntar);
                        array_push($messagesTaskWall['mensageMuro'][$key.$auxKey]['adjuntos'], $adjuntar);
                    }
                }

                ((int) $messagesTaskWall['mensageMuro'][$key.$auxKey]['tipo'] === self::ACCION && is_null($message->getCerradoPor())) ? $this->setTaskWall($messagesTaskWall, $key, $auxKey) : null;
            }
        }

        return $messagesTaskWall;
    }

    /**
     * @param int|null        $pipeline
     * @param string|null     $quote
     * @param ComercialTask   $task
     * @param array|null      $attachFiles
     * @param DocumentService $documentService
     *
     * @return bool
     *
     * @throws Exception
     */
    public function addTaskWall(?int $pipeline, ?string $quote, ComercialTask $task, ?array $attachFiles, DocumentService $documentService): bool
    {
        $commercialManagers = [];
        $activeQuote        = null;
        $activePipeline     = (!is_null($pipeline))
            ? $this->em->getRepository(ComercialExpediente::class)->findOneBy(['id' => $pipeline])
            : null;

        if (!is_null($quote)) {
            $activePipelineQuote = $this->em->getRepository(ComercialExpedienteCotizaciones::class)->findOneBy(['cotizacion' => $quote]);

            $activeQuote = !empty($activePipelineQuote)
                ? $activePipelineQuote
                : $this->em->getRepository(ComercialCotizacion::class)->findOneBy(['numCoti' => $quote]);
        }

        // Start save data in wall
        $wallObject = new ComercialMuro();

        $wallObject->setAutor($task->getCreatedBy())
            ->setMissatge($task->getDescription())
            ->setNivel(1)
            ->setGrupo(1)
            ->setTipo(self::ACCION)
            ->setVisto(false)
            ->setCreatedAt(new DateTime('now'));

        // Add a Quote if exists in param
        if (!is_null($activeQuote)) {
            $wallObject->setCotizacion(method_exists($activeQuote , 'getCotizacion')
                ? $activeQuote->getCotizacion()
                : $activeQuote->getNumCoti()
            );
            $wallObject->setExpediente(
                method_exists($activeQuote , 'getExpedienteID')
                    ? $activeQuote->getExpedienteID()
                    : null
            );
        // only select Pipeline
        } else if (!is_null($activePipeline)) {
            $wallObject->setExpediente($activePipeline);
        }

        // Assign Responsible of task
        $assignedTo = $this->setGestorReponsable($task->getResponsible(), $task->getCreatedBy(), $wallObject);

        if (is_null($assignedTo) && $this->isTypeUser($task->getCreatedBy(), 'Commercial')) {
            // Si no hay usuario assignado a la tarea se mandará una notificación a los comerciales responsables
            $office = ($activePipeline->getEsDisposicion()) ? $activePipeline->getExpedientePadre()->getOficina() : $activePipeline->getOficina();

            $commercialManagers = $this->getResponsablesZona($office);
        } elseif ($this->isTypeUser($task->getCreatedBy(), 'Commercial')) {
            $wallObject->setResponsable($assignedTo);
        } elseif (!$this->isTypeUser($task->getCreatedBy(), 'Commercial')) {
            $wallObject->setResponsable($assignedTo);
        }
        try {
            $this->em->persist($wallObject);
            $this->em->flush();

            // set relation wall into task
            $task->setComercialMuro($wallObject);
            $this->em->persist($task);
            $this->em->flush();

            // Add the attachments documents of message
            if (!is_null($attachFiles) && !empty($attachFiles)) {
                $this->addAttachedFiles($attachFiles, $documentService, $wallObject);
            }

            // Enviamos a RabbitMQ el mensaje de notificación de este mensaje a quien corresponda
            if(!is_null($activePipeline) || !is_null($activeQuote)) {
                $this->sendWallMessageRabbit($this->container, $wallObject, $commercialManagers);
            }
        } catch (Exception $e) {
            return false;
        }

        return true;
    }

    /**
     * @param int $id
     *
     * @return ComercialTask|null
     */
    public function getTask(int $id): ?ComercialTask
    {
        return $this->em->getRepository(ComercialTask::class)->findOneBy(['id' => $id]);
    }

    /**
     * @param int $idWall
     *
     * @return ComercialTask|null
     */
    public function getTaskWall(int $idWall): ?ComercialTask
    {
        $wallTask = $this->em->getRepository(ComercialTask::class)->findOneBy(['comercialMuro' => $idWall]);

        return is_null($wallTask) ? $this->getTask($idWall) : $wallTask;
    }

    /**
     * Set task in view wall
     *
     * @param array  $mensajesMuro
     * @param string $key
     * @param int    $auxKey
     */
    public function setTaskWall(array &$mensajesMuro, string $key, int $auxKey): void
    {
        $tareaMsg = $this->getTaskWall($mensajesMuro['mensageMuro'][$key.$auxKey]['id']);
        (!is_null($tareaMsg) && is_null($tareaMsg->getDeletedBy()))
            ? $this->assingTallWall($mensajesMuro, $key, $auxKey, $tareaMsg)
            : null;
    }

    /**
     * @param array         $mensajesMuro
     * @param string        $key
     * @param int           $auxKey
     * @param ComercialTask $task
     */
    public function assingTallWall(array &$mensajesMuro, string $key, int $auxKey, ComercialTask $task): void
    {
        if (!is_null($task->getType()->getIsSpecial()) && !$task->getType()->getIsSpecial()) {
            $mensajesMuro['mensageTarea'][$key.$auxKey]             = $mensajesMuro['mensageMuro'][$key.$auxKey];
            $mensajesMuro['mensageTarea'][$key.$auxKey]['adjuntos'] = $mensajesMuro['mensageMuro'][$key.$auxKey]['adjuntos'];
        } else {
            $mensajesMuro['mensajesPetisCotis'][$key.$auxKey]             = $mensajesMuro['mensageMuro'][$key.$auxKey];
            $mensajesMuro['mensajesPetisCotis'][$key.$auxKey]['adjuntos'] = $mensajesMuro['mensageMuro'][$key.$auxKey]['adjuntos'];
        }
    }

    /**
     * @param array         $mensajesMuro
     * @param string        $key
     * @param int           $auxKey
     * @param ComercialTask $task
     */
    public function assingTaskWall(array &$mensajesMuro, string $key, int $auxKey, ComercialTask $task): void
    {
        if (!is_null($task->getType()->getIsSpecial()) && !$task->getType()->getIsSpecial()) {
            $mensajesMuro['mensageTarea'][$key.$auxKey] = $this->addDataTask($task);
        } else {
            $mensajesMuro['mensajesPetisCotis'][$key.$auxKey] = $this->addDataTask($task);
        }
    }

    /**
     * @param ComercialTask $task
     *
     * @return array
     */
    public function addDataTask(ComercialTask $task): array
    {
        return [
            'id'          => $task->getId(),
            'type'        => 'statusMessage',
            'tipo'        => $this::ACCION,
            'tipoID'      => $task,
            'autor'       => $task->getCreatedBy(),
            'assignado'   => $task->getResponsible(),
            'message'     => $task->getDescription(),
            'motivo_canc' => '',
            'adjuntos'    => [],
            'cerrado_por' => null,
            'respuesta'   => null,
        ];
    }
    /**
     * @param string $class
     * @param int    $id
     *
     * @return array
     */
    public function getAdjuntosExpediente(string $class, int $id): array
    {
        return $this->em->getRepository($class)->findBy(['muro' => $id]);
    }

    /**
     * Return array expediente and origin experiente
     *
     * @param string   $class
     * @param int|null $idExpediente
     *
     * @return array
     */
    public function getExpedientesToCheck(string $class, ?int $idExpediente): array
    {
        $expedientes['Vinculados'] = $expedientes['VinculadosText'] = [];

        $expedientesAChequear = [];

        $objExpediente = $this->em->getRepository($class)->findOneBy(['id' => $idExpediente]);

        $expedientesToCheck = (!is_null($idExpediente)) ? $this->em->getRepository($class)->findExpedientesPadre($idExpediente) : [];

        $expedienteText  = $this->translator->trans('expediente');
        $disposicionText = $this->translator->trans('disposicion');

        $i = 0;
        foreach ($expedientesToCheck as $expedienteToCheck) {
            if (!in_array($expedienteToCheck->getId(), $expedientes['Vinculados'])) {
                array_push($expedientes['Vinculados'], $expedienteToCheck->getId());

                if ($expedienteToCheck->getEsDisposicion()) {
                    $i++;
                } else {
                    $expedienteText = $expedienteToCheck->getTitulo();
                }

                $expedientes['VinculadosText'][(string) $expedienteToCheck->getId()] = (!$expedienteToCheck->getEsDisposicion() ? $expedienteToCheck->getTitulo() : 'disposicion '.$i);
            }

            ($expedienteToCheck->getExpedientePadre() && !in_array($expedienteToCheck->getExpedientePadre()->getId(), $expedientes['Vinculados']))
                ? $this->arrayPushDataExpedientes($expedientes, $expedientesAChequear, $expedienteToCheck, $objExpediente, $expedienteText, $disposicionText)
                : null;
        }

        return $expedientes;
    }

    /**
     * @param array               $expedientes
     * @param array               $expedientesAChequear
     * @param ComercialExpediente $expedienteToCheck
     * @param ComercialExpediente $objExpediente
     * @param string              $expedienteText
     * @param string              $disposicionText
     */
    public function arrayPushDataExpedientes(array &$expedientes, array &$expedientesAChequear, ComercialExpediente $expedienteToCheck, ComercialExpediente $objExpediente, string $expedienteText, string $disposicionText): void
    {
        array_push($expedientes['Vinculados'], $expedienteToCheck->getExpedientePadre()->getId());
        array_push($expedientesAChequear, $objExpediente->getExpedientePadre()->getId());
        $expedientes['VinculadosText'][(string) $expedienteToCheck->getExpedientePadre()->getId()] =
            (!$expedienteToCheck->getExpedientePadre()->getEsDisposicion() ? $expedienteText : $disposicionText);
    }

    /**
     * Get Cotizaciones of linked into Expediente selected
     *
     * @param string   $class
     * @param int|null $idExpediente
     *
     * @return array
     */
    public function getCotizacionesExpediente(string $class, ?int $idExpediente): array
    {
        return  $this->em->getRepository($class)->getCotizacionesExpediente($idExpediente);
    }

    /**
     * Get Task to send in wall
     * @param string $class
     *
     * @return array
     */
    public function getTypesTask(string $class): array
    {
        return $this->em->getRepository($class)
                    ->findBy(
                        ['deletedAt' => null],
                        ['id' => 'asc']
                    );
    }

    /**
     * Find data users smart
     * @param string $query
     *
     * @return array
     */
    public function findUsers(string $query): array
    {
        $results = $this->em
            ->getRepository(User::class)
            ->createQueryBuilder('p')
            ->select('p.id, p.regNumber, p.name, p.surname')
            ->where('p.regNumber like :iden OR p.name like :iden OR p.surname like :iden')
            ->setParameter('iden', '%'.$query.'%')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult();

        $respuesta = [];
        foreach ($results as $result) {
            $respuesta[] = [
                'id'   => $result['id'],
                'text' => '('.$result['regNumber'].')'.$result['name'].' '.$result['surname'],
            ];
        }

        return $respuesta;
    }

    /**
     * Get Resposable
     *
     * @param string|null $responsableTarea
     *
     * @return User|null
     */
    public function getUser(?string $responsableTarea): ?User
    {
        if (!is_null($responsableTarea)) {
            return $this->em->getRepository(User::Class)->findOneBy(['id' => $responsableTarea]);
        }

        return null;
    }

    /**
     * @param ComercialExpediente $expediente
     * @param string              $field
     * @param User                $user
     * @param string|null         $oldValue
     * @param string|null         $newValue
     *
     * @return bool
     *
     * @throws Exception
     */
    public function changeStatusWall(ComercialExpediente $expediente, string $field, User $user, ?string $oldValue = null, ?string $newValue = null): bool
    {
        $message = $this->textStatusMessagePipeline($oldValue, $newValue, $field);

        $objMensajeEstado = new ComercialMuro();
        $objMensajeEstado->setAutor($user)
            ->setExpediente($expediente)
            ->setMissatge($message)
            ->setVisto(0)
            ->setTipo(self::STATUS)
            ->setNivel(1)
            ->setGrupo(1)
            ->setCreatedAt(new DateTime('now'));
        try {
            $this->em->persist($objMensajeEstado);
            $this->em->flush();

            $this->sendWallMessageRabbit($this->container, $objMensajeEstado, []);
        } catch (Exception $e) {
            $this->logger->error('changeStatusWall', [$e->getMessage()]);

            return false;
        }

        return true;
    }

    /**
     * Text status message
     * @param string|null $oldValue
     * @param string|null $newValue
     * @param string $field
     * @return string
     */
    public function textStatusMessagePipeline(?string $oldValue, ?string $newValue, string $field): string
    {
        $oldValueText = (null === $oldValue) ? '' : '<p class="valorAntiguo"><strong>Valor antiguo: </strong>'.$oldValue.'</p>';
        $newValueText = (null === $newValue) ? '' : '<p class="valorNuevo"><strong>Valor nuevo: </strong>'.$newValue.'</p>';

        return 'El expediente ha sido modificado. El campo <span class="campoModificado">'.$field.'</span> ha cambiado. <span class="msgVerCambio">Ver cambio</span><div class="valoresOcultos"><hr>'.$oldValueText.$newValueText.'</div>';
    }
    /**
     * Write new status into Wall when change Data of quote and send Rabbit Message
     *
     * @param string      $numberQuote
     * @param string      $field
     * @param User        $user
     * @param string|null $oldValue
     * @param string|null $newValue
     *
     * @return bool
     *
     * @throws Exception
     */
    public function changeStatusWallQuote(string $numberQuote, string $field, User $user, ?string $oldValue = null, ?string $newValue = null): bool
    {
        $oldValueText = (null === $oldValue) ? '' : '<p class="valorAntiguo"><strong>Valor antiguo: </strong>'.$oldValue.'</p>';
        $newValueText = (null === $newValue) ? '' : '<p class="valorNuevo"><strong>Valor nuevo: </strong>'.$newValue.'</p>';

        $message = 'Cotizacion ha sido modificada. El campo <span class="campoModificado">'.
            $field.'</span> ha cambiado. <span class="msgVerCambio">Ver cambio</span><div class="valoresOcultos"><hr>'.
            $oldValueText.$newValueText.'</div>';

        $objMensajeEstado = new ComercialMuro();
        $objMensajeEstado->setAutor($user)
            ->setCotizacion($numberQuote)
            ->setMissatge($message)
            ->setVisto(0)
            ->setTipo(self::STATUS)
            ->setNivel(1)
            ->setGrupo(1)
            ->setCreatedAt(new DateTime('now'));
        try {
            $this->em->persist($objMensajeEstado);
            $this->em->flush();

            $this->sendWallMessageRabbit($this->container, $objMensajeEstado, []);
        } catch (Exception $e) {
            $this->logger->error('changeStatusWall', [$e->getMessage()]);

            return false;
        }

        return true;
    }

    /**
     * Get one muro Type
     *
     * @param int $type
     *
     * @return ComercialMuroTipo
     */
    public function findTypesMessage(int $type): ComercialMuroTipo
    {
        return $this->em->getRepository(ComercialMuroTipo::class)->find($type);
    }

    /**
     * Change Status Quote on Wall
     *
     * @param User                $userLogged
     * @param string              $status
     * @param ComercialMuro       $objMuro
     * @param DateTime            $now
     * @param string              $isCoti
     * @param ComercialExpediente $objExpediente
     *
     * @throws Exception
     */
    public function changeStatusQuoteWall(User $userLogged, string $status, ComercialMuro $objMuro, DateTime $now, string $isCoti, ComercialExpediente $objExpediente): void
    {
        $objMuroEstados = new ComercialMuroEstados();

        $objMuroEstados->setAutor($userLogged)
            ->setMissatge($objMuro)
            ->setEstado($status)
            ->setCreatedAt($now);

        $this->em->persist($objMuroEstados);
        $this->em->flush();

        ('' !== $isCoti && (self::CERRADO === $status || self::RECHAZADA === $status))
            ? $this->setDataComercialMuro($userLogged, $objExpediente, $objMuro)
            : null;
    }

    /**
     * Add status into Muro
     *
     * @param User                $userLogged
     * @param ComercialExpediente $objExpediente
     * @param ComercialMuro       $objMuro
     *
     * @throws Exception
     */
    public function setDataComercialMuro(User $userLogged, ComercialExpediente $objExpediente, ComercialMuro $objMuro): void
    {
        $message = 'El mensaje <span class="msgGoTo" data-id="'.$objMuro->getId().'">#'.$objMuro->getId().'</span> ha sido cerrado por el usuario';

        $objMensajeEstado = new ComercialMuro();

        $objMensajeEstado->setAutor($userLogged)
            ->setVisto(false)
            ->setExpediente($objExpediente)
            ->setMissatge($message)
            ->setTipo(self::STATUS)
            ->setNivel(1)
            ->setGrupo(1)
            ->setCreatedAt(new DateTime('now'));

        $this->em->persist($objMensajeEstado);
        $this->em->flush();
    }

    /**
     * Set Data Request
     *
     * @param Request $request
     */
    public function setRequestData(Request $request): void
    {
        $this->muroData->setMissatge($request->get('wall_message'));
        $this->muroData->setExpediente($request->get('expediente'));
        $this->muroData->setCotizacion($request->get('numCoti'));
        $this->muroData->setTareaPadre($request->get('tareaPadre'));
        $this->muroData->setTarea($request->get('tareaNoti'));
        $this->muroData->setTipo(($request->get('tipoMensaje') === 'notificacion') ? self::NOTIFICACION : self::ACCION);
        $this->muroData->setTareaTipo($this->getTaskType(ComercialTaskType::class, $request->get('idTaskType')));
    }

    /**
     * @param string $class
     * @param int    $id
     *
     * @return ComercialTask
     */
    public function getTaskType(string $class, int $id): ComercialTask
    {
        return $this->em->getRepository($class)->findOneBy(['id' => $id]);
    }

    /**
     * @param string $coti
     * @param int    $idTask
     * @param string $dateTask
     */
    public function updateDataCotizacionTask(string $coti, int $idTask, string $dateTask): void
    {
        // get task created in wall
        $task = $this->getTaskWall($idTask);
        // task is null into wall search on task table
        $task = (is_null($task) ? $this->getTask($idTask) : $task);

        $quoteUpdate = $this->em->getRepository(ComercialCotizacion::class)->findOneBy(['numCoti' => $coti]);

        if (is_null($quoteUpdate)) {
            return;
        }

        $quoteUpdate->setIdTarea($task->getId());
        if(is_null($quoteUpdate->getFechaTarea())) {
            $quoteUpdate->setFechaTarea($dateTask);
        }
        $quoteUpdate->setAutor($task->getComercialMuro()->getAutor());

        $this->em->persist($quoteUpdate);
        $this->em->flush();
    }

    /**
     * @param User|null     $wallUserSend
     * @param User          $userLogged
     * @param ComercialMuro $objMuro
     *
     * @return User|null
     */
    public function setGestorReponsable(?User $wallUserSend, User $userLogged, ComercialMuro $objMuro): ?User
    {
        $gestorReturn = (is_null($wallUserSend)) ? $this->getResponsable($userLogged, $objMuro) : $wallUserSend;

        return $gestorReturn;
    }

    /**
     * @param User          $userLogged
     * @param ComercialMuro $objMuro
     *
     * @return User|null
     */
    public function getResponsable(User $userLogged, ComercialMuro $objMuro): ?User
    {
        if ($this->isTypeUser($userLogged, 'Commercial') && !is_null($objMuro->getExpediente()) && !is_null($objMuro->getExpediente()->getResponsableGestorInterno())) {
            return $objMuro->getExpediente()->getResponsableGestorInterno();
        }

        if ($this->isTypeUser($userLogged, 'Responsable') && !is_null($objMuro->getExpediente()) && !is_null($objMuro->getExpediente()->getResponsable())) {
            return $objMuro->getExpediente()->getResponsable();
        }

        return null;
    }

    /**
     * Type user registered
     *
     * @param User|null $user
     * @param string    $typeUser
     *
     * @return bool
     */
    public function isTypeUser(?User $user, string $typeUser): bool
    {
        return (!is_null($user) && !is_null($this->em->getRepository(CommercialResponsable::Class)->findOneBy([$typeUser => $user->getRegNumber()])) ? true : false);
    }

    /**
     * Get gestors of zone office
     *
     * @param string $office
     *
     * @return array
     */
    public function getResponsablesZona(string $office): array
    {
        return $this->em->getRepository(CommercialOffice::class)->findResponsibleZone((int) $office);
    }

    /**
     * Add Attachfiles in message/task in wall
     * @param array           $attachedFiles
     * @param DocumentService $documentService
     * @param ComercialMuro   $muroData
     *
     * @throws Exception
     */
    public function addAttachedFiles(array $attachedFiles, DocumentService $documentService, ComercialMuro $muroData): void
    {
        foreach ($attachedFiles as $doc) {
            // Obtenemos el nombre del fichero
            $filename = $doc->getClientOriginalName();
            // Obtenemos la extensión
            $extension = explode('.', $filename);
            $extension = $extension[count($extension) - 1];
            // Obtenemos el mimeType
            $mimeTypes = self::MIMETYPES;
            $mimeType  = 'application/force-download';
            if (isset($mimeTypes[$extension])) {
                $mimeType = $mimeTypes[$extension];
            }

            $pipelineId = (!is_null($muroData->getExpediente()) ? $muroData->getExpediente()->getId() : null);
            // Guardamos el documento en Docuflex
            $idDoc = null;
            if (!$documentService->upload($doc, 18, $pipelineId , $muroData->getCotizacion())) {
                $documentService->newDataDocument();
                // Offline Files
                $documentService->getDataDocument()->setDocumentType($this->em->getRepository(DocumentType::class)->findOneBy(['id' => 18]));
                $documentService->getDataDocument()->setExpedient($muroData->getExpediente());
                $documentService->getDataDocument()->setQuotation($muroData->getCotizacion());
                $documentService->getDataDocument()->setUser($muroData->getAutor());
                $documentService->getDataDocument()->setCreatedAt(new DateTime('now'));

                $documentUpload = $documentService->docSaveTmpLocalPath($doc);

                $idDoc = (false !== $documentUpload) ? $documentUpload : null;
                // File Name changed when upload in a local folder for not repeat
                $filename = (false !== $documentUpload) ? $documentUpload->getName() : $filename;
            } else {
                $idDoc = $documentService->getDataDocument();
            }

            (!is_null($idDoc)) ? $this->saveFileAttached($idDoc, $extension, $filename, $mimeType, $muroData) : null;
        }
    }

    /**
     * Save Document
     * @param Document      $idDoc
     * @param string        $extension
     * @param string        $filename
     * @param string        $mimeType
     * @param ComercialMuro $muroData
     *
     * @throws Exception
     */
    public function saveFileAttached(Document $idDoc, string $extension, string $filename, string $mimeType, ComercialMuro $muroData): void
    {
        // Creamos el registro en la base de datos
        $objMuroAdjunto = new ComercialMuroAdjuntos();
        $objMuroAdjunto->setDocId($idDoc)
            ->setMuro($muroData)
            ->setExt($extension)
            ->setFilename($filename)
            ->setMimeType($mimeType)
            ->setCreatedAt(new DateTime('now'));
        $this->em->persist($objMuroAdjunto);
        $this->em->flush();
    }

    /**
     * Send notification wall to Rabbit
     *
     * @param ContainerInterface $container
     * @param ComercialMuro      $objWall
     * @param array              $commercialManagers
     *
     * @return bool
     */
    public function sendWallMessageRabbit(ContainerInterface $container, ComercialMuro $objWall, array $commercialManagers = []): Bool
    {
        try {
            $container->get('old_sound_rabbit_mq.notification_producer');
            $container->get('old_sound_rabbit_mq.notification_producer')->setContentType('application/json');
            $container->get('old_sound_rabbit_mq.notification_producer')->publish(
                json_encode([
                    'Type'                 => 'Muro',
                    'Muro'                 => $objWall->getId(),
                    'comercialResponsible' => $commercialManagers,
                ])
            );
        } catch (Exception $exception) {
            $this->logger->error('Error Send Message With in Wall Service', [$exception->getMessage()]);

            return false;
        } catch (\Error $error) {
            $this->logger->error('Error Send Message With in Wall Service', [$error->getMessage()]);

            return false;
        }

        return true;
    }

    /**
     * Get data wall to add Notification message
     * @param int $wallId
     *
     * @return array
     */
    public function getNotificationData(int $wallId): array
    {
        $objWall = $this->em->getRepository(ComercialMuro::class)->findOneBy(['id' => $wallId]);

        $textMessage = $objWall->getAutor()->getName().' '.$objWall->getAutor()->getSurname();

        switch ($objWall->getTipo()) {
            case 4:
                $textMessage .= $this->translator->trans(' has changed ');
                break;
            case 2:
                $textMessage .= $this->translator->trans(' has a task in ');
                break;
            default:
                $textMessage .= $this->translator->trans(' has written in ');
                break;
        }

        $textMessage .= (!is_null($objWall->getExpediente()))
            ? $objWall->getExpediente()->getTitulo().' '.$this->translator->trans('pipeline')
            : '';

        $whereMuro = '';
        $paramMuro = [];
        $dateTime  = null;
        if (!is_null($objWall->getExpediente()) && is_null($objWall->getCotizacion())) {
            $titlePipeline = (!is_null($objWall->getExpediente()->getTitulo())
                ? $objWall->getExpediente()->getTitulo()
                : $objWall->getExpediente()->getId());
            $textMessage  .= $this->generateLinkText(
                $this->router->generate('comercial_ver_muro_expediente', ['idExpediente' => $objWall->getExpediente()->getId(), '_locale' => 'es']),
                $this->translator->trans(' wall pipeline #'),
                $titlePipeline
            );

            $whereMuro = 'm.expediente = :expID';
            $paramMuro = [
                'key'   => ':expID',
                'value' => $objWall->getExpediente()->getId(),
            ];
            $dateTime  = $objWall->getCreatedAt();
        } elseif (!is_null($objWall->getExpediente()) && !is_null($objWall->getCotizacion())) {
            $textMessage .= $this->generateLinkText(
                $this->router->generate('comercial_ver_muro_coti', ['idExpediente' => $objWall->getExpediente()->getId(), 'numCoti' => $objWall->getCotizacion(), '_locale' => 'es']),
                $this->translator->trans(' wall Quote #'),
                $objWall->getCotizacion()
            );

            $whereMuro = 'm.cotizacion = :cotiID';
            $paramMuro = [
                'key'   => ':cotiID',
                'value' => $objWall->getCotizacion(),
            ];
            $dateTime  = $objWall->getCreatedAt();
        } elseif (is_null($objWall->getExpediente()) && is_null($objWall->getCotizacion())) {
            $tareaObj = $this->em->getRepository(ComercialTask::class)->findOneBy(['comercialMuro' => $wallId]);

            if (!is_null($tareaObj)) {
                $textMessage  = $tareaObj->getCreatedBy()->getName().' '.$tareaObj->getCreatedBy()->getSurname();
                $textMessage .= $this->translator->trans(' has written in ');
                $textMessage .= $this->generateLinkText(
                    $this->router->generate('comercial_ver_muro_tarea', ['idTarea' => $tareaObj->getId(), '_locale' =>'es',]),
                    $this->translator->trans(' wall Task #'),
                    $tareaObj->getId()
                );

                $whereMuro = 'm.id = :id';
                $paramMuro = [
                    'key'   => ':id',
                    'value' => $tareaObj->getComercialMuro()->getId(),
                ];
                $dateTime  = $tareaObj->getCreatedAt();
            } else {
                $tareaObj = $this->em->getRepository(ComercialMuro::class)->findOneBy(['id' => $wallId]);

                if (!is_null($tareaObj)) {
                    $textMessage  = $tareaObj->getAutor()->getName().' '.$tareaObj->getAutor()->getSurname();
                    $textMessage .= $this->translator->trans(' has written in ');
                    $textMessage .= $this->generateLinkText(
                        $this->router->generate('comercial_ver_muro_tarea', ['idTarea' => $tareaObj->getId(), '_locale' =>'es',]),
                        $this->translator->trans(' wall Task #'),
                        $tareaObj->getId()
                    );

                    $whereMuro = 'm.id = :id';
                    $paramMuro = [
                        'key'   => ':id',
                        'value' => $tareaObj->getId(),
                    ];
                    $dateTime  = $tareaObj->getCreatedAt();
                }
            }
        }

        return [
            'message'    => $textMessage,
            'dateTime'   => $dateTime,
            'objectWall' => $objWall,
            'whereMuro'  => $whereMuro,
            'paramMuro'  => $paramMuro,
        ];
    }

    /**
     * @param int $idClose
     *
     * @return ComercialTask|null
     */
    public function isCloseTask(int $idClose): ?ComercialTask
    {
        $wallClosed = $this->em->getRepository(ComercialMuro::class)->findOneBy(['cerradoPor' => $idClose]);

        return (!is_null($wallClosed))
            ? $this->em->getRepository(ComercialTask::class)->findOneBy(['comercialMuro' => $wallClosed->getId()])
            : null;
    }

    /**
     * @param ComercialMuro $objMessage
     * @param ComercialMuro $messageStatus
     * @param string        $message
     *
     * @throws Exception
     */
    public function closeMessageWall(ComercialMuro $objMessage, ComercialMuro $messageStatus, string $message): void
    {
        $objMessage->setCerradoPor($messageStatus)
            ->setMotivoCanc($message)
            ->setUpdatedAt(new DateTime('now'));
        $this->em->persist($objMessage);
        $this->em->flush();
    }

    /**
     * @param ComercialMuro $wallObject
     *
     * @return array
     */
    public function setCloseArray(ComercialMuro $wallObject): array
    {
        return [
            'id'          => $wallObject->getCerradoPor()->getId(),
            'type'        => 'statusMessage',
            'tipo'        => strtolower($wallObject->getCerradoPor()->getTipo()),
            'tipoID'      => (1 === $wallObject->getCerradoPor()->getTipo())
                ? self::NOTIFICACION
                : $this->getTaskWall($wallObject->getCerradoPor()->getId()),
            'autor'       => $wallObject->getCerradoPor()->getAutor(),
            'message'     => $wallObject->getCerradoPor()->getMissatge(),
            'motivo_canc' => $wallObject->getCerradoPor()->getMotivoCanc(),
            'adjuntos'    => [],
            'createdAt'   => $wallObject->getCerradoPor()->getCreatedAt(),
            'updatedAt'   => $wallObject->getCerradoPor()->getUpdatedAt(),
        ];
    }


    /**
     * generateLink
     * @param string $url
     * @param string $text
     * @param string $dataId
     *
     * @return string
     */
    public function generateLinkText(string $url, string $text, string $dataId): string
    {
        return '<a href="'.$url.'">'.$text.$dataId.'</a>';
    }

    /**
     * @param User                     $user
     * @param ComercialExpediente|null $pipeline
     * @param string                   $message
     *
     * @return ComercialMuro|null
     */
    public function cancelTaskWall(User $user, ?ComercialExpediente $pipeline, string $message): ?ComercialMuro
    {
        try {
            $changeWallStatus = new ComercialMuro();
            $changeWallStatus->setAutor($user)
                ->setExpediente($pipeline)
                ->setMissatge($message)
                ->setTipo(self::STATUS)
                ->setAutor($user)
                ->setVisto(true)
                ->setNivel(1)
                ->setGrupo(1)
                ->setCreatedAt(new DateTime('now'));
            $this->em->persist($changeWallStatus);
            $this->em->flush();

            return $changeWallStatus;
        } catch (Exception $e) {
            return null;
        }
    }
}
