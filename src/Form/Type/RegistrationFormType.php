<?php
/**
 * Creador de formulario de registro
 */

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseRegistrationFormType;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * Class RegistrationFormType
 * @package App\Form\Type
 */
class RegistrationFormType extends AbstractType
{
    /**
     * Construccion de los campos del formulario
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('regNumber', TextType::class, array('label'=>'Usuario CCF/CEF', 'attr' => array('maxlength' => '8')));
        $builder->add('regNumberCaixabank', TextType::class, array('label'=>'Usuario CXB', 'attr'=>array('placeholder'=>'Si no dispones de usuario CaixaBank, introduce tu usuario CCF/CEF', 'maxlength' => '8')));
        $builder->add('name', TextType::class, array('label'=>'Nombre'));
        $builder->add('surname', TextType::class, array('label'=>'Apellidos'));
        $builder->add('email', EmailType::class, array('label' => 'Email', 'required' => false, 'attr' => array('placeholder'=>'email@caixabankconsumer.com')));
        $builder->add('plainPassword', TextType::class, array('label'=>'pass', 'required' => false));
        //$builder->add('plainPassword', TextType::class, array( 'attr' => array('required' => false)));
        //$builder->remove('plainPassword', TextType::class, array( 'attr' => array('required' => false)));
        $builder->remove('username');
        $builder->remove('plainPassword');
    }

    /**
     * Obtener formulario padre para sobreescribirlo
     * @return string
     */
    public function getParent()
    {
        return BaseRegistrationFormType::class;
    }
}
