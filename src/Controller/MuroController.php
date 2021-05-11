<?php

namespace App\Controller;

use \DateTime;
use \Exception;

use App\Entity\ComercialExpediente;
use App\Entity\ComercialExpedienteCotizaciones;
use App\Entity\ComercialMuro;
use App\Entity\ComercialMuroAdjuntos;
use App\Entity\ComercialMuroEstados;
use App\Entity\ComercialTask;
use App\Entity\ComercialTaskStatus;
use App\Entity\ComercialTaskType;
use App\Entity\User;

use App\Service\AlertasService;
use App\Service\DocumentService;
use App\Service\MuroService;
use App\Service\SAPConnectorService;

use Psr\Container\ContainerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;

class MuroController extends AbstractController
{

    /**
     * Container interface
     * @var ContainerInterface
     */
    protected $container;

    /**
     * SapConector Service
     * @var SAPConnectorService
     */
    protected $sapConnectorService;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * MuroController constructor.
     * @param ContainerInterface  $container
     * @param SAPConnectorService $SAPConnectorService
     * @param TranslatorInterface $translator
     */
    public function __construct(ContainerInterface $container, SAPConnectorService $SAPConnectorService, TranslatorInterface $translator)
    {
        $this->container           = $container;
        $this->sapConnectorService = $SAPConnectorService;
        $this->translator          = $translator;
    }

    /**
     * View Muro
     *
     * @param int|null       $idExpediente
     * @param string|null    $numCoti
     * @param string|null    $tab
     * @param string|nulL    $idTarea
     * @param AlertasService $alertas
     * @param MuroService    $muroService
     *
     * @return Response
     *
     * @throws Exception
     */
    public function viewMuro(?int $idExpediente, ?string $numCoti, ?string $tab, ?string $idTarea, AlertasService $alertas, MuroService $muroService): Response
    {

        $toSearch['idExpediente'] = (null !== $idExpediente && 0 !== $idExpediente) ? $idExpediente : null;
        $toSearch['idCotizacion'] = (0 !== $numCoti) ? (int) $numCoti : null;
        $toSearch['idTarea']      = (0 !== $idTarea) ? (int) $idTarea : null;

        $dataAlert  = $alertas->getMuroAlerts($toSearch, 'ALERT');
        $dataNotice = $alertas->getMuroAlerts($toSearch, 'NOTICE');

        $countNumAlerts    = $alertas->countTotalAlerts($dataNotice, $dataAlert);
        $objExpedienteCoti = !is_null($toSearch['idExpediente'])
            ? $this->getDoctrine()->getRepository(ComercialExpediente::class)->find($toSearch['idExpediente'])
            : null;

        // Obtenemos los mensages del muro
        $messagesWall = (0 !== $toSearch['idTarea'] && (0 === $toSearch['idExpediente'] || 0 === $toSearch['idCotizacion']))
            ? $muroService->getMuroTask(ComercialTask::class, $toSearch)
            : $muroService->getMuro(ComercialMuro::class, $toSearch, $objExpedienteCoti);

        // Obtenemos los datos del expediente y tambien los anteriores a este
        $expedientesLinked = $muroService->getExpedientesToCheck(ComercialExpediente::class, $toSearch['idExpediente']);

        // Obtenemos las cotizaciones asociadas al expediente
        $cotizacionesLinked = $muroService->getCotizacionesExpediente(ComercialExpedienteCotizaciones::class, $toSearch['idExpediente']);

        $typeWall = ((is_null($toSearch['idExpediente']) || is_null($toSearch['idCotizacion'])) && !is_null($toSearch['idTarea'])) ? 'wallTask' : 'wallPipeline';

        return $this->render('area-comercial/muro.html.twig', [
            'mensajesTareas'     => $messagesWall['mensageTarea'],
            'mensajesPeticiones' => $messagesWall['mensajesPetisCotis'],
            'mensajesAdjuntos'   => $messagesWall['mensageAdjunto'],
            'messagesWall'       => $messagesWall['mensageMuro'],
            'expediente'         => $idExpediente,
            'numCoti'            => $toSearch['idCotizacion'],
            'tareaPadre'         => $toSearch['idTarea'],
            'objAlert'           => $dataAlert,
            'objAlertOffices'    => $dataAlert['offices'],
            'objAlertCustomers'  => $dataAlert['customers'],
            'objAlertProviders'  => $dataAlert['providers'],
            'objNotice'          => $dataNotice,
            'objNoticeOffices'   => $dataNotice['offices'],
            'objNoticeCustomers' => $dataNotice['customers'],
            'objNoticeProviders' => $dataNotice['providers'],
            'expedientesLinked'  => $expedientesLinked,
            'cotizacionesLinked' => $cotizacionesLinked,
            'totalCountAlerts'   => $countNumAlerts,
            'taskTypes'          => $muroService->getTypesTask(ComercialTaskType::class),
            'tareaNoti'          => $idTarea,
            'typeWall'           => $typeWall,
        ]);
    }

    /**
     *
     * @param Request     $request
     * @param MuroService $muroService
     *
     * @return Response
     */
    public function findUsers(Request $request, MuroService $muroService): Response
    {
        $type  = $request->query->get('type');
        $query = $request->query->get('q');

        if ('user' === $type) {
            return new JsonResponse($muroService->findUsers($query));
        }

        return new Response('Not Found', 404);
    }


    /**
     * Send Message into Wall and send notification
     *
     * @param Request            $request
     * @param ContainerInterface $container
     * @param DocumentService    $documentService
     * @param MuroService        $muroService
     *
     * @return Response
     *
     * @throws Exception
     */
    public function enviarMessage(Request $request, ContainerInterface $container, DocumentService $documentService, MuroService $muroService): Response
    {

        $now = new DateTime('now');

        $em = $this->getDoctrine()->getManager();

        $muroAdjuntos          = $request->files;
        $muroCierraIDs         = $request->get('cierraID');
        $muroMensaje           = $request->get('wall_message');
        $muroExpediente        = $request->get('expediente');
        $muroCoti              = $request->get('numCoti');
        $muroTareaPadre        = $request->get('tareaPadre');
        $muroTareaForm         = json_encode($request->get('formEsp'));
        $muroCierraIDs         = $request->get('cierraID');
        $muroTipo              = ($request->get('tipoMensaje') === 1 || $request->get('tipoMensaje') === 'notificacion')
            ? $muroService::NOTIFICACION
            : $muroService::ACCION;
        $muroTipoTarea         = $em->getRepository(ComercialTaskType::class)->findOneBy(['id' => $request->get('idTaskType')]);
        $muroRespuestaPetiCoti = $request->get('respuestaPetiCoti');
        $responsableTarea      = $request->get('wall_user_send');
        $usrTarea              = $muroService->getUser($responsableTarea);
        $muroNivel             = $muroGrupo = 1;
        $commercialManagers    = [];

        $tareaPadre = null;
        // If add a new task response of task in wall task
        if ('0' !== $muroTareaPadre) {
            $tareaPadre = $em->getRepository(ComercialTask::class)->findOneBy(['id' => $muroTareaPadre]);
            if (!is_null($tareaPadre->getComercialMuro())) {
                if (!is_null($tareaPadre->getComercialMuro()->getExpediente()) && !is_null($tareaPadre->getComercialMuro()->getCotizacion())) {
                    $muroExpediente = $tareaPadre->getComercialMuro()->getExpediente()->getId();

                    $muroCoti = $tareaPadre->getComercialMuro()->getCotizacion();
                }
            }
        }

        // Get a User Logged in smart
        $userLogged = $this->getUser();

        // If user are Admim get data of user admins
        if (get_class($userLogged) === 'App\Entity\Admin') {
            $userLogged = $muroService->getUser($this->getUser()->getId());
        }

        // Start save data in wall
        $objMuro = new ComercialMuro();
        if (!is_null($muroMensaje) && '' !== $muroMensaje) {
            $objMuro->setMissatge($muroMensaje);
        } else {
            $objMuro->setMissatge('');
        }

        $objMuro->setAutor($userLogged)
            ->setNivel($muroNivel)
            ->setGrupo($muroGrupo)
            ->setTipo($muroTipo)
            ->setVisto(false)
            ->setCreatedAt($now);

        if ('0' !== $muroTareaPadre || !is_null($tareaPadre)) {
            $objMuro->setTareaPadre($tareaPadre);
        }

        $activeExpedient = $em->getRepository(ComercialExpediente::class)->findOneBy(['id' => $muroExpediente]);

        // Add a Quote if exists in param
        if ('' !== $muroCoti) {
            $objMuro->setCotizacion($muroCoti);
            $objMuro->setExpediente($activeExpedient);
        } else {
            $objMuro->setExpediente($activeExpedient);
        }

        // Assign Responsable on task
        $assinatedTo = $muroService->setGestorReponsable($usrTarea, $userLogged, $objMuro);

        if (is_null($assinatedTo) && $muroService->isTypeUser($userLogged, 'Commercial')) {
            // Si no hay usuario assignado a la tarea se mandará una notificación a los comerciales responsables
            $office = ($activeExpedient->getEsDisposicion()) ? $activeExpedient->getExpedientePadre()->getOficina() : $activeExpedient->getOficina();

            $commercialManagers = $muroService->getResponsablesZona($office);
        } elseif ($muroService->isTypeUser($userLogged, 'Commercial')) {
            $objMuro->setResponsable($assinatedTo);
        } elseif (!$muroService->isTypeUser($userLogged, 'Commercial')) {
            $objMuro->setResponsable($assinatedTo);
        }

        $em->persist($objMuro);
        $em->flush();

        // If message is task add to task board
        if (!is_null($muroTipoTarea)) {
            $newTask = new ComercialTask();

            $status = $em->getRepository(ComercialTaskStatus::class)->findOneBy(['id' => 1]);
            $type   = $em->getRepository(ComercialTaskType::class)->findOneBy(['id' => $muroTipoTarea->getId()]);

            $newTask->setResponsible($usrTarea)
                ->setStatus($status)
                ->setType($type)
                ->setCreatedBy($userLogged)
                ->setForm($muroTareaForm)
                ->setDescription($muroMensaje);

            $em->persist($newTask);
            $em->flush();

            // Add relation message wall into Task
            if (isset($newTask)) {
                $newTask->setComercialMuro($objMuro);
                $em->persist($newTask);
                $em->flush();
            }
        }

        // Close messages if not empty var
        if (!empty($muroCierraIDs) && '' !== $muroExpediente) {
            $objExpediente = $em->getRepository(ComercialExpediente::class)->findOneBy(['id' => $muroExpediente]);

            // Into Pipelines have 'gestor responsable interno', if don't have to assigned into Pipeline
            if (empty($objExpediente->getResponsableGestorInterno())) {
                // not null Resposable tarea and ResposableTarea is 'Gestor'
                if (!is_null($responsableTarea) && $muroService->isTypeUser($userLogged, 'Responsable')) {
                    $objExpediente->setResponsableGestorInterno($usrTarea);
                } else {
                    if (is_null($responsableTarea) && $muroService->isTypeUser($userLogged, 'Responsable')) {
                        $objExpediente->setResponsableGestorInterno($userLogged);
                    }
                }

                $em->persist($objExpediente);
                $em->flush();
            }

            $cierreIds = explode(',', $muroCierraIDs[0]);

            // Task to close
            foreach ($cierreIds as $cierraID) {
                $objMuroCierre = $em->getRepository(ComercialMuro::class)->findOneBy(['id' => $cierraID]);

                $objMuroCierre->setCerradoPor($objMuro)
                    ->setUpdatedAt($now);
                $em->persist($objMuroCierre);
                $em->flush();

                $status       = $em->getRepository(ComercialTaskStatus::class)->findOneBy(['description' => 'Closed']);
                $taskWall     = $muroService->getTaskWall($objMuroCierre->getId());
                $tareaToClose = (!is_null($taskWall)) ? $em->getRepository(ComercialTask::class)->find($taskWall->getId()) : null;
                (!is_null($tareaToClose)) ? $this->closeTask($tareaToClose, $status, $userLogged) : null;
            }
        }

        // Task to close without expedient
        if ('' === $muroExpediente && !empty($muroCierraIDs)) {
            $cierreIds = explode(',', $muroCierraIDs[0]);
            // Task to close without expedient
            $status = $em->getRepository(ComercialTaskStatus::class)->findOneBy(['description' => 'Closed']);
            foreach ($cierreIds as $cierraID) {
                $task = $muroService->getTask($cierraID);
                (!is_null($task)) ? $this->closeTask($task, $status, $userLogged) : null;
                $objMuroCierre = $em->getRepository(ComercialTask::class)->findOneBy(['id' => $cierraID]);
                if (!is_null($objMuroCierre->getComercialMuro())) {
                    $objMuroCierre->getComercialMuro()
                        ->setCerradoPor($objMuro)
                        ->setUpdatedAt($now);
                }
                $em->persist($objMuroCierre);
                $em->flush();
            }
        }
        // Add the attachments documents of message
        if (!empty($muroAdjuntos->get('file'))) {
            $muroService->addAttachedFiles($muroAdjuntos->get('file'), $documentService, $objMuro);
        }

        // Enviamos a RabbitMQ el mensaje de notificación de este mensaje a quien corresponda
        $muroService->sendWallMessageRabbit($container, $objMuro, $commercialManagers);

        $tmp = $request->get('viene-de-create');

        if (!is_null($tmp)) {
            return $this->redirectToRoute('comercial_expedientes');
        }

        return new Response('<script type="text/javascript">window.top.formSent();</script>');
    }

    /**
     * Close Task into wall
     * @param ComercialTask       $task
     * @param ComercialTaskStatus $status
     * @param User                $userLogged
     *
     * @throws Exception
     */
    public function closeTask(ComercialTask $task, ComercialTaskStatus $status, User $userLogged): void
    {
        $em = $this->getDoctrine()->getManager();
        $task->setStatus($status)
            ->setDeletedBy($userLogged)
            ->setDeletedAt(new DateTime('now'));
        $em->persist($task);
        $em->flush();
    }

    /**
     * Cancel tarea with Message
     * @param Request            $request
     * @param MuroService        $muroService
     * @param ContainerInterface $container
     *
     * @return JsonResponse
     *
     * @throws Exception
     */
    public function cancelMessage(Request $request, MuroService $muroService, ContainerInterface $container): JsonResponse
    {
        if ($request->isXmlHttpRequest()) {
            $em = $this->getDoctrine()->getManager();

            // Recogemos el request
            $mensajeId        = $request->get('id_mensaje');
            $mensajeMotivo    = $request->get('motivo');
            $typeWall         = $request->get('typeWall');
            $objMessageStatus = null;

            // Obtenemos el mensaje
            $objMensaje = ('wallPipeline' === $typeWall)
                ? $this->getDoctrine()->getRepository(ComercialMuro::class)->findOneBy(['id' => $mensajeId])
                : $this->getDoctrine()->getRepository(ComercialTask::class)->findOneBy(['id' => $mensajeId]);

            // Recogemos el usuario loguejado, o, si es admin, el usuario que coincida con su ID
            $userLogged = $this->getUser();
            if (get_class($userLogged) === 'App\Entity\Admin') {
                $userLogged = $this->getDoctrine()->getRepository(User::class)->findOneBy(
                    ['id' => $this->getUser()->getId()]
                );
            }

            $status = $this->getDoctrine()->getRepository(ComercialTaskStatus::class)->find(['id' => 3]);

            if ('wallPipeline' === $typeWall) {
                // Obtenemos el expediente perteneciente al mensaje
                $objExpediente = $em->getRepository(ComercialExpediente::class)->findOneBy([
                    'id' => $objMensaje->getExpediente(),
                ]);

                $message = 'El mensaje <span class="msgGoTo" data-id="'.$objMensaje->getId().'">#'
                    .$objMensaje->getId().'</span> ha sido cancelado por el usuario';
                // Creamos un mensaje de estado para informar
                $objMessageStatus = $muroService->cancelTaskWall($userLogged, $objExpediente, $message);

                // Cancelamos el mensaje y añadimos un msj de motivo
                $muroService->closeMessageWall($objMensaje, $objMessageStatus, $mensajeMotivo);

                $task = $this->getDoctrine()
                    ->getRepository(ComercialTask::class)
                    ->findOneBy(['comercialMuro' => $objMensaje->getId()]);

                $task->setDeletedBy($userLogged)
                    ->setStatus($status)
                    ->setDeletedAt(new DateTime('now'));
                $em->persist($objMensaje);
                $em->flush();
            } else {
                $message = 'La tarea <span class="msgGoTo" data-id="'.$objMensaje->getId().'">#'.
                    $objMensaje->getId().'</span> ha sido cancelado por el usuario';
                if (!is_null($objMensaje->getComercialMuro())) {
                    $objTaskWall = $objMensaje->getComercialMuro();

                    // Creamos un mensaje de estado para informar
                    $objMessageStatus = $muroService->cancelTaskWall($userLogged, null, $message);

                    // Cancelamos el mensaje y añadimos un msj de motivo
                    $muroService->closeMessageWall($objTaskWall, $objMessageStatus, $mensajeMotivo);

                    $objMensaje->setDeletedBy($userLogged)
                        ->setStatus($status)
                        ->setDeletedAt(new DateTime('now'));

                    $em->persist($objMensaje);
                    $em->flush();
                } else {
                    $objMensajeEstado = new ComercialMuro();
                    $objMensajeEstado->setAutor($userLogged)
                        ->setMissatge($message)
                        ->setTipo($muroService::STATUS)
                        ->setAutor($userLogged)
                        ->setVisto(true)
                        ->setNivel(1)
                        ->setGrupo(1)
                        ->setCreatedAt(new DateTime('now'));
                    $em->persist($objMensajeEstado);
                    $em->flush();

                    $objMensaje->setComercialMuro($objMensajeEstado);
                    $em->persist($objMensaje);
                    $em->flush();
                }
            }

            if (!is_null($objMessageStatus)) {
                $muroService->sendWallMessageRabbit($container, $objMessageStatus);
            }

            $respuesta = ['resultado' => 'ACEPTADA'];

            return new JsonResponse($respuesta);
        }

        $respuesta = ['resultado' => 'RECHAZADA'];

        return new JsonResponse($respuesta);
    }

    /**
     * Get Taks Types
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function getTypeTask(Request $request): JsonResponse
    {
        $em = $this->getDoctrine()->getManager();

        $repository = $em->getRepository(ComercialTaskType::class);

        $data = $repository->createQueryBuilder('ctt')
            ->innerJoin('ctt.comercialMuroTipos', 'ctm')
            ->where('ctm.id = :id')
            ->setParameter('id', $request->get('id'))
            ->getQuery()
            ->getResult();

        $items = [];
        foreach ($data as $item) {
            $items[] = ['id' => $item->getId(), 'description' => $item->getDescription()];
        }

        if (0 === count($data)) {
            return new JsonResponse('Not Send', 404);
        }

        return new JsonResponse($items, 200);
    }

    /**
     * check Document Ajax
     * Verifica el campo idDoku
     * Si idDoku es no nulo, verifica si el archivo existe en dokuflex
     * Si idDoku es nulo, verifica si el archivo existe en local
     * Si el archivo existe, se podrá hacer download
     * Si el archivo no existe devolverá un mensaje de error
     *
     * @param Request         $request
     * @param DocumentService $documentService
     *
     * @return Response
     */
    public function checkDocument(Request $request, DocumentService $documentService): Response
    {
        $logged = (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY') && !$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED')) ? false : true;
        if ($logged) {
            $em          = $this->getDoctrine()->getEntityManager();
            $file        = $request->get('file');
            $objDocument = $em->getRepository(ComercialMuroAdjuntos::class)->findOneBy(['id' => $request->get('attached'), 'filename' => $file]);
            if ($file) {
                try {
                    $type   = ((is_null($objDocument->getDocId()->getIdDoku())) ? 'local' : 'dokuflex');
                    $object = ['attached' => $request->get('attached'), 'file' => $request->get('file')];
                    if ('dokuflex' === $type) {
                        $documentFile = $documentService->downloadWall($objDocument->getDocId()->getId(), $file);
                        $data         = (!$documentFile ? 'ko' : $object);
                    } else {
                        $documentFile = '../'.getenv('FILES_UPLOAD').$objDocument->getDocId()->getId().DIRECTORY_SEPARATOR.$file;
                        $data         = (!file_exists($documentFile) ? 'ko' : $object);
                    }

                    if ('ko' === $data) {
                        return new Response($data);
                    }

                    $url = $this->generateUrl(
                        'comercial_muro_ver_documento',
                        $object
                    );

                    return new Response($url);
                } catch (Exception $e) {
                    return new Response($this->translator->trans('Cannot access file1'));
                }
            }
        }

        return new Response($this->translator->trans('Not access into this site'));
    }

    /**
     * Preview Document Ajax
     *
     * @param Request         $request
     * @param DocumentService $documentService
     *
     * @return Response
     */
    public function previewDocument(Request $request, DocumentService $documentService): Response
    {
        $logged = (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY') && !$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED')) ? false : true;
        // Get the file
        if ($logged) {
            $em          = $this->getDoctrine()->getEntityManager();
            $file        = $request->get('file');
            $objDocument = $em->getRepository(ComercialMuroAdjuntos::class)->findOneBy(['id' => $request->get('attached'), 'filename' => $file]);
            if ($file) {
                try {
                    $fileLocal = ((is_null($objDocument->getDocId()->getIdDoku()))
                        ? '../'.getenv('FILES_UPLOAD').$objDocument->getDocId()->getId().DIRECTORY_SEPARATOR.$file
                        : $documentService->downloadWall($objDocument->getDocId()->getId(), $file));
                    $fileResp  = ((is_null($objDocument->getDocId()->getIdDoku()))
                        ? $file
                        : $documentService->downloadWall($objDocument->getDocId()->getId(), $file));

                    return (is_null($objDocument->getDocId()->getIdDoku()))
                        ? new BinaryFileResponse($fileLocal, 200, ['Content-Type' => $objDocument->getMimeType(), 'Content-Disposition' => 'attachment; filename="'.$file.'"'])
                        : new Response($fileResp, 200, ['Content-Disposition' => 'attachment; filename="'.$file.'"']);
                } catch (Exception $e) {
                    return new Response($this->translator->trans('Can not access file'));
                }
            }
        }

        return new Response($this->translator->trans('Not access into this site'));
    }

    /**
     * Send edited form of type task
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function obtenerCodigoForm(Request $request): JsonResponse
    {
        $id = $request->get('id');

        $em = $this->getDoctrine()->getEntityManager();
        if (!is_null($id)) {
            $form = $em->getRepository(ComercialTaskType::class)->findOneBy(['id' => $id]);

            return new JsonResponse([
                'code'   => $form->getForm(),
                'titulo' => $form->getDescription(),
                'type'   => $id,
            ]);
        }

        return new JsonResponse(null);
    }

    /**
     * Validate Expediente Muro
     *
     * @param Request     $request
     * @param MuroService $muroService
     *
     * @return JsonResponse
     */
    public function validateCotiMuro(Request $request, MuroService $muroService): JsonResponse
    {
        $coti     = ltrim($request->get('coti'), '0');
        $exp      = $request->get('id_expediente');
        $idTask   = $request->get('id_tarea');
        $taskDate = $request->get('fecha_tarea');

        try {
            $validCoti = $this->sapConnectorService->WSCheckNumCoti($coti, ('' === $exp) ? null : $exp);
            ('OK' === $validCoti) ? $muroService->updateDataCotizacionTask($coti, $idTask, $taskDate) : null;

            return new JsonResponse($validCoti);
        } catch (Exception $e) {
            return new JsonResponse('NOK');
        }
    }

    /**
     * @param string $dataParent
     * @param array  $objAlert
     * @param string $typeView
     *
     * @return Response
     */
    public function listAlert(string $dataParent, array $objAlert, string $typeView): Response
    {
        $vista = $dataParent.'-'.$typeView;

        return $this->render(
            'area-comercial/muro/view_alerts_list.html.twig',
            ['dataParent' => $vista, 'varAlert' => $objAlert]
        );
    }

    /**
     * @param string $dataParent
     * @param array  $objNotice
     * @param string $typeView
     *
     * @return Response
     */
    public function listNotice(string $dataParent, array $objNotice, string $typeView): Response
    {
        $vista = $dataParent.'-'.$typeView;

        return $this->render(
            'area-comercial/muro/view_notice_list.html.twig',
            ['dataParent' => $vista, 'varNotice' => $objNotice]
        );
    }

    /**
     * Get Form of comercialTask
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function viewFormTask(Request $request): JsonResponse
    {

        $searchIntoTask = ($request->get('typeWall') === 'wallTask')
            ? ['id' => $request->get('id')]
            : ['comercialMuro' => $request->get('id')];

        $task = $this->getDoctrine()
            ->getEntityManager()
            ->getRepository(ComercialTask::class)
            ->findOneBy($searchIntoTask);

        return new JsonResponse($task->getForm());
    }
}
