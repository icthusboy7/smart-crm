<?php

namespace App\Controller;

use \DateTime;
use \Exception;
use App\Entity\ComercialCotizacion;
use App\Entity\ComercialExpediente;
use App\Entity\ComercialTask;
use App\Entity\ComercialTaskStatus;
use App\Entity\ComercialTaskType;
use App\Entity\User;
use App\Entity\Views\Manager;
use App\Service\DocumentService;
use App\Service\MuroService;
use App\Service\NotificationTaskService;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TaskController extends AbstractController
{
    /**
     * @var PaginatorInterface
     */
    private $paginator;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * TaskController constructor.
     * @param PaginatorInterface     $knpPaginator
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(PaginatorInterface $knpPaginator, EntityManagerInterface $entityManager)
    {
        $this->paginator     = $knpPaginator;
        $this->entityManager = $entityManager;
    }

    /**
     * @param Request|null $request
     *
     * @return Response
     */
    public function index(?Request $request = null): Response
    {
        $params = [
            'zonas'  => $request->query->get('zonas'),
            'type'   => $request->query->get('find_task_type'),
            'status' => $request->query->get('find_task_status'),
            'desc'   => $request->query->get('f_name'),
        ];

        if ($request->query->get('author_id')) {
            $params['idUsuario'] = $request->query->get('author_id');
        }

        $em = $this->entityManager;

        $tasks = $em->getRepository(ComercialTask::class)
            ->findAllWithExpedientes($params);

        $counts = [
            'total' => count($tasks),
            'mine'  => $em->getRepository(ComercialTask::class)->countUserTareas($this->getUser()->getId()),
            'cib'   => [
                'sin' => $em->getRepository(ComercialTask::class)->countTareas('cib', true),
                'all' => $em->getRepository(ComercialTask::class)->countTareas('cib'),
            ],
            'este'  => [
                'sin' => $em->getRepository(ComercialTask::class)->countTareas('este', true),
                'all' => $em->getRepository(ComercialTask::class)->countTareas('este'),
            ],
            'norte' => [
                'sin' => $em->getRepository(ComercialTask::class)->countTareas('norte', true),
                'all' => $em->getRepository(ComercialTask::class)->countTareas('norte'),
            ],
            'sud'   => [
                'sin' => $em->getRepository(ComercialTask::class)->countTareas('sud', true),
                'all' => $em->getRepository(ComercialTask::class)->countTareas('sud'),
            ],
        ];

        $taskTypes = $this->getDoctrine()->getRepository(ComercialTaskType::class)->findBy(
            ['deletedAt' => null],
            ['id' => 'asc']
        );
        $users     = $this->getDoctrine()->getRepository(User::class)->findAll();

        $paginator  = $this->paginator;
        $pagination = $paginator->paginate(
            $tasks,
            $request->query->getInt('page', 1),
            10
        );
        $pagination->setCustomParameters(['position' => 'centered']);

        $data = [
            'tasks'        => $pagination,
            'taskTypes'    => $taskTypes,
            'users'        => $users,
            'counts'       => $counts,
            'managers'     => $this->getManagers(),
            'taskStatus'   => $this->getDoctrine()->getRepository(ComercialTaskStatus::class)->findAll(),
            'filter'       => [],
            'usersFilter'  => [],
            'statusFilter' => [],
            'typeFilter'   => [],
        ];

        /**
         * Fil filter form with params
         */
        if (isset($params['idUsuario'])) {
            if (in_array($this->getUser()->getId(), $params['idUsuario'])) {
                $data['filter'] = 'SELF';
            }
            $data['usersFilter'] = $this->getDoctrine()->getRepository(User::class)->findBy(['id' => $params['idUsuario']]);
        }
        if (!is_null($params['zonas'])) {
            $data['filter'] = $params['zonas'];
        }
        if (!is_null($params['status'])) {
            $data['statusFilter'] = $params['status'];
        }
        if (!is_null($params['type'])) {
            $data['typeFilter'] = $params['type'];
        }

        return $this->render('area-comercial/tasks.html.twig', $data);
    }

    /**
     * Create Task
     * @param Request                 $request
     * @param ContainerInterface      $container
     * @param NotificationTaskService $taskMessageService
     * @param MuroService             $wallService
     * @param DocumentService         $documentService
     *
     * @return Response
     *
     * @throws Exception
     */
    public function create(Request $request, ContainerInterface $container, NotificationTaskService $taskMessageService, MuroService $wallService, DocumentService $documentService): Response
    {
        $responsible = $request->request->get('responsible');
        $attachFiles = (!empty($request->files)) ? $request->files : null;
        $status      = 2;
        $pipeline    = !empty($request->request->get('pipeline')) ? $request->request->get('pipeline') : null;
        $quote       = !empty($request->request->get('quote')) ? $request->request->get('quote') : null;

        if (!is_null($responsible)) {
            $responsible = $this->getDoctrine()->getRepository(User::class)->find($responsible);
            $status      = 1;
        }

        $status = $this->getDoctrine()->getRepository(ComercialTaskStatus::class)->find($status);

        $taskType = $request->request->get('taskType');
        $taskType = $this->getDoctrine()->getRepository(ComercialTaskType::class)->find($taskType);

        $newTask = new ComercialTask();
        $newTask->setCreatedAt(new DateTime('now'))
            ->setCreatedBy($this->getUser())
            ->setResponsible($responsible)
            ->setStatus($status)
            ->setForm(($request->request->get('formSpecial') === '') ? null : $request->request->get('formSpecial'))
            ->setDescription($request->request->get('description'))
            ->setType($taskType)
            ->setSeen(false);

        $data = ['error' => false, 'message' => '', ];
        try {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($newTask);
            $entityManager->flush();

            // Publish in wall when task are assigned a pipeline or $quote
            $data = (true === $wallService->addTaskWall($pipeline, $quote, $newTask, $attachFiles->get('file'), $documentService))
                ? ['error' => false, 'message' => '', ]
                : ['error' => true, 'message' => 'error create task into wall', ];

            /**
             * @TODO - En el cas que s'enviï que la tasca tingui assignat un Expedient o Cotització
             * s'hauria d'enviar notificació al mur de tasques?
             */
            $taskMessageService->commercialTaskMessage($container, $newTask);
        } catch (Exception $e) {
            $message = $e->getMessage();
            if (getenv('APP_ENV') !== 'dev') {
                $message = 'Ha habido un error.';
            }
            $data = [
                'error'   => true,
                'message' => $message,
            ];
        }

        return new Response(json_encode($data));
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse|Response
     */
    public function selectResponsible(Request $request)
    {
        $name = $request->query->get('q');

        if (is_null($name)) {
            return new Response('Not Found', 404);
        }
        $em    = $this->entityManager;
        $query = $em->createQuery(
            'SELECT u.id id, concat(\'(\', u.username, \') \',u.name, \' \', u.surname) text 
            FROM App:User u 
            WHERE u.username LIKE :name
            OR u.name LIKE :name
            OR u.surname LIKE :name'
        )->setParameter('name', '%'.$name.'%');
        $users = $query->getResult();

        return new JsonResponse($users);
    }

    /**
     * @param Request                 $request
     * @param ContainerInterface      $container
     * @param NotificationTaskService $taskMessageService
     *
     * @return Response
     */
    public function setResponsible(Request $request, ContainerInterface $container, NotificationTaskService $taskMessageService): Response
    {
        $responsibleId = $request->request->get('responsible_modal');
        $idTask        = $request->request->get('task_id');

        if (is_null($responsibleId) || is_null($idTask)) {
            $data = [
                'error'   => true,
                'message' => 'data: '.print_r($responsible.' - '.$idTask, true),
            ];

            return new Response(json_encode($data));
        }
        $responsible = $this->getDoctrine()->getRepository(User::class)->find($responsibleId);
        $task        = $this->getDoctrine()->getRepository(ComercialTask::class)->find($idTask);
        $task->setResponsible($responsible);
        if (!is_null($task->getComercialMuro())) {
            $task->getComercialMuro()->setResponsable($responsible);
        }
        try {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($task);
            $entityManager->flush();

            $taskMessageService->commercialTaskMessage($container, $task);

            $data     = [
                'error'   => false,
                'message' => '',
            ];
            $response = new Response(json_encode($data));
        } catch (Exception $e) {
            $message = $e->getMessage();
            if (getenv('APP_ENV') !== 'dev') {
                $message = 'Ha habido un error.';
            }
            $data     = [
                'error'   => true,
                'message' => $message,
            ];
            $response = new Response(json_encode($data));
        }

        return $response;
    }

    /**
     * @param Request|null $request
     *
     * @return Response
     */
    public function indexTaskTypes(?Request $request = null): Response
    {
        $taskTypes = $this->getDoctrine()->getRepository(ComercialTaskType::class)->findAll();

        $paginator  = $this->paginator;
        $pagination = $paginator->paginate(
            $taskTypes,
            $request->query->getInt('page', 1),
            10
        );
        $pagination->setCustomParameters(['position' => 'centered']);

        $data = [
            'task_types' => $pagination,
        ];

        return $this->render('area-comercial/task_types.html.twig', $data);
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function findPipeline(Request $request): JsonResponse
    {
        $search = $request->query->get('q');

        if (is_null($search)) {
            return new Response('Not Found', 404);
        }

        return new JsonResponse($this->getDoctrine()
            ->getRepository(ComercialExpediente::class)
            ->filterPipelines($search));
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function findQuote(Request $request): JsonResponse
    {
        $search = $request->query->get('q');

        if (is_null($search)) {
            return new Response('Not Found', 404);
        }

        return new JsonResponse($this->getDoctrine()
            ->getRepository(ComercialCotizacion::class)
            ->filterQuotes($search));
    }

    /**
     * @param Request|null            $request
     * @param ContainerInterface      $container
     * @param NotificationTaskService $taskMessageService
     *
     * @return Response
     */
    public function sendNotification(?Request $request, ContainerInterface $container, NotificationTaskService $taskMessageService): Response
    {
        $idTask = $request->request->get('q');
        $task   = $this->getDoctrine()->getRepository(ComercialTask::class)->find($idTask);

        try {
            $taskMessageService->commercialTaskMessage($container, $task);
        } catch (Exception $e) {
            return new Response('Error');
        }

        return new Response('200');
    }

    /**
     * Returns all the users that are managers ordered by name.
     *
     * @return array        Users array
     */
    private function getManagers(): array
    {
        $em         = $this->getDoctrine();
        $repository = $em->getRepository(Manager::class);

        return $repository->findBy([], ['name' => 'DESC']);
    }

    /**
     * This utility method explodes a comma-separated string value of
     * an array into an array of values. Returns an empty array if the
     * key does not exist or is not a valid string.
     *
     * @param string $key    A key in the array
     * @param array  $values Array that contains the value
     *
     * @return array of values
     */
    private function asArray(string $key, array $values): array
    {
        if (key_exists($key, $values)) {
            if (is_string($values[$key])) {
                return explode(',', $values[$key]);
            }
        }

        return [];
    }
}
