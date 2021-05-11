<?php

namespace App\Controller;

use \DateTime;
use \Exception;
use App\Entity\ComercialTaskType;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TaskTypeController extends AbstractController
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
        $taskTypes = $this->getDoctrine()->getRepository(ComercialTaskType::class)
            ->findBy(
                ['deletedAt' => null],
                ['id' => 'asc']
            );

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
     * @return Response
     *
     * @throws Exception
     */
    public function create(Request $request): Response
    {
        $taskType = new ComercialTaskType();

        $form = $this->createFormBuilder($taskType, ['attr' => ['class' => 'form-inline']])
            ->add('description', TextType::class, [
                'attr' => [
                    'class'       => 'form-control mb-2 mr-sm-2 mb-sm-0',
                    'placeholder' => 'Description',
                ],
            ])
            ->add('isSpecial', CheckboxType::class, [
                'required'   => false,
                'attr'       => ['class' => 'custom-control-input'],
                'label_attr' => ['class' => 'custom-control-label'],
            ])
            ->add('icon', TextType::class, [
                'required' => false,
                'attr'     => [
                    'class'       => 'form-control mb-2 mr-sm-2 mb-sm-0',
                    'placeholder' => 'Icon',
                ],
            ])
            ->add('color', TextType::class, [
                'required' => false,
                'attr'     => [
                    'class'       => 'form-control mb-2 mr-sm-2 mb-sm-0 jscolor',
                    'placeholder' => 'Color',
                ],
            ])
            ->add('form', HiddenType::class)
            ->add('save', SubmitType::class, [
                'label' => 'Create Type',
                'attr'  => [
                    'class' => 'btn btn-sm btn-primary',
                ],
            ])
            ->getForm()
        ;

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $taskType = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($taskType);
            $entityManager->flush();

            return $this->redirectToRoute('task_types');
        }

        return $this->render('area-comercial/task_types_create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param int     $id
     * @param Request $request
     *
     * @return Response
     */
    public function edit(int $id, Request $request): Response
    {
        $taskType = $this->entityManager->getRepository(ComercialTaskType::class)->find($id);

        $form = $this->createFormBuilder($taskType, ['attr' => ['class' => 'form-inline']])
            ->add('description', TextType::class, [
                'attr' => [
                    'class'       => 'form-control mb-2 mr-sm-2 mb-sm-0',
                    'placeholder' => 'Description',
                ],
            ])
            ->add('isSpecial', CheckboxType::class, [
                'required'   => false,
                'attr'       => ['class' => 'custom-control-input'],
                'label_attr' => ['class' => 'custom-control-label'],
            ])
            ->add('icon', TextType::class, [
                'required' => false,
                'attr'     => [
                    'class'       => 'form-control mb-2 mr-sm-2 mb-sm-0',
                    'placeholder' => 'Icon',
                ],
            ])
            ->add('color', TextType::class, [
                'required' => false,
                'attr'     => [
                    'class'       => 'form-control mb-2 mr-sm-2 mb-sm-0 jscolor',
                    'placeholder' => 'Color',
                ],
            ])
            ->add('form', HiddenType::class)
            ->add('save', SubmitType::class, [
                'label' => 'Save Type',
                'attr'  => [
                    'class' => 'btn btn-sm btn-primary',
                ],
            ])
            ->getForm()
        ;

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $taskType = $form->getData();

            $em = $this->entityManager;
            $em->persist($taskType);
            $em->flush();

            $this->addFlash('success', 'Task type edited correct!');

            return $this->redirectToRoute('task_types');
        }

        return $this->render('area-comercial/task_types_create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     *
     * @return Response
     *
     * @throws Exception
     */
    public function delete(Request $request): Response
    {
        $entityManager = $this->entityManager;

        $id = $request->request->get('id');

        $taskType = $entityManager->getRepository(ComercialTaskType::class)->find($id);

        try {
            $taskType->setDeletedAt(new DateTime());
            $entityManager->persist($taskType);
            $entityManager->flush();

            $data = [
                'error'   => false,
                'message' => '',
            ];
        } catch (Exception $e) {
            $data = [
                'error'   => true,
                'message' => $e->getMessage(),
            ];
        }

        return new Response(json_encode($data));
    }
}
