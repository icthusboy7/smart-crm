<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * This form type represent the search filters of the visits calendar.
 * It is used to validate and parse the search filters used by the
 * {@see EventRepository#search} method.
 */
class EventSearchType extends AbstractType
{
    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('author_id', CollectionType::class, [
            'allow_add' => true,
            'entry_type' => IntegerType::class,
        ]);

        $builder->add('status_id', CollectionType::class, [
            'allow_add' => true,
            'entry_type' => IntegerType::class,
        ]);

        $builder->add('start', DateTimeType::class, [
            'widget' => 'single_text',
            'required' => true,
        ]);

        $builder->add('end', DateTimeType::class, [
            'widget' => 'single_text',
            'required' => true,
        ]);
    }


    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'allow_extra_fields' => true,
            'csrf_protection' => false,
        ]);
    }
}
