<?php

namespace App\Form\Type;

use App\Entity\ComercialAlertas;
use App\Entity\ComercialExpediente;
use App\Core\Entity\Contact;
use App\Entity\Horizontal;
use App\Entity\MasterOffice;
use App\Entity\MasterQuotation;
use App\Entity\Vertical;

use App\Core\Entity\Vocabs\ContactKind;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class AlertasFormType extends AbstractType
{
    /**
     * Entity manager
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * AlertasFormType constructor.
     * @param EntityManagerInterface $em
     * @param TranslatorInterface    $translator
     */
    public function __construct(EntityManagerInterface $em, TranslatorInterface $translator)
    {
        $this->em         = $em;
        $this->translator = $translator;
    }

    /**
     * Create Form Alertas
     *
     * @param FormBuilderInterface $builder
     * @param array                $options
     *
     * @return FormBuilderInterface|void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $alertId          = null;
        $choiceExpediente = [];
        $choiceCotizacion = [];
        $choicePersonaNIF = [];
        $choiceOficina    = [];
        $choiceHorizontal = [];
        $choiceVertical   = [];


        if (isset($options['data'])) {
            if (!is_null($options['data']->getId())) {
                $alertId = $options['data']->getId();
            }
            if (!is_null($options['data']->getExpediente())) {
                $data = $this->em->getRepository(ComercialExpediente::class)->findBy(['id' => $options['data']->getExpediente()]);

                if (!empty($data)) {
                    $choiceExpediente = [$data[0]->getTitulo() => $data[0]->getId()];
                }
            }

            if (!is_null($options['data']->getCotizacion())) {
                $data = $this->em->getRepository(MasterQuotation::class)->findBy(['codigoCoti' => $options['data']->getCotizacion()]);

                if (!empty($data)) {
                    $choiceCotizacion = [$data[0]->getCodigoCoti() => $data[0]->getCodigoCoti()];
                }
            }

            $choicePersonaNIF = $this->findTypeUserAlert($options['data']->getPersonaNif());

            $data = $this->em->getRepository(MasterOffice::class)->findBy(['codigo' => $options['data']->getOficina()]);

            if (!empty($data)) {
                $choiceOficina = [$data[0]->getNombre() => $data[0]->getCodigo()];
            }

        }

        $data = $this->em->getRepository(Horizontal::class)->findAll();

        if (!empty($data)) {
            $choiceHorizontal[$this->translator->trans('No selected Horizontal')] = '';
            foreach ($data as $item) {
                $choiceHorizontal[$item->getName()] = $item->getId();
            }
        }

        $data = $this->em->getRepository(Vertical::class)->findAll();

        if (!empty($data)) {
            $choiceVertical[$this->translator->trans('No selected Vertical')] = '';
            foreach ($data as $item) {
                $choiceVertical[$item->getName()] = $item->getId();
            }
        }

        return $builder
            ->add('id', HiddenType::class, ['attr' => ['value' => $alertId]])
            ->add('expediente', ChoiceType::class, ['choices' => $choiceExpediente, 'attr' => ['class' => 'form-control expedient-select2 js-example-basic-single select2-selection--single']])
            ->add('cotizacion', ChoiceType::class, ['choices' => $choiceCotizacion, 'attr' => ['class' => 'form-control cotizacion-select2 js-example-basic-single select2-selection--single' ]])
            ->add('personanif', ChoiceType::class, ['choices' => $choicePersonaNIF, 'attr' => ['class' => 'form-control personanif-select2 js-example-basic-single select2-selection--single']])
            ->add('oficina', ChoiceType::class, ['choices' => $choiceOficina, 'attr' => ['class' => 'form-control oficina-select2 js-example-basic-single select2-selection--single']])
            ->add('vertical', ChoiceType::class, ['choices' => $choiceVertical, 'attr' => ['class' => 'form-control', 'required' => false, ]])
            ->add('horizontal', ChoiceType::class, ['choices' => $choiceHorizontal, 'attr' => [ 'class' => 'form-control', 'required' => false, ]])
            ->add('missatge', TextareaType::class, ['attr' => ['class' => 'form-control', 'required' => true, ]])
            ->add('active', CheckboxType::class, ['attr' => ['class' => 'form-control', 'required' => false, ]])
            ->add('isAlert', CheckboxType::class, ['attr' => ['class' => 'form-control', 'required' => false, ]])
            ->add('nivel', ChoiceType::class, [
                'choices'  => [
                    '10' => 10,
                    '9'  => 9,
                    '8'  => 8,
                    '7'  => 7,
                    '6'  => 6,
                    '5'  => 5,
                    '4'  => 4,
                    '3'  => 3,
                    '2'  => 2,
                    '1'  => 1,
                    '0'  => 0,
                ],
                'attr'     => ['class' => 'form-control'],
                'required' => true,
            ]);
    }

    /**
     * Configure Options Form
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => ComercialAlertas::class, 'form_type' => 'new_alert']);
    }

    /**
     * @param string|null $nif
     *
     * @return array
     */
    public function findTypeUserAlert(?string $nif): array
    {
        $choicePersonaNIF = [];
        if (is_null($nif)) {
            return $choicePersonaNIF;
        }
        $data = $this->em->getRepository(Contact::class)->findOneBy(['nif' => $nif]);
        if (!empty($data)) {
            $kind = $data->getKind()->getId();
            switch ($kind) {
                case ContactKind::CUSTOMER()->getId():
                    $type = 'Cl';
                    break;
                case ContactKind::SUPPLIER()->getId():
                    $type = 'Pr';
                    break;
                case ContactKind::CUSTOMER_SUPPLIER()->getId():
                    $type = 'Cl - Pr';
                    break;
                default:
                    $type = 'Co';
                    break;
            }
            $choicePersonaNIF = ['['.$data->getNif().'] '.$data->getName().' - '.$type => $data->getNif()];
        }

        return $choicePersonaNIF;
    }
}
