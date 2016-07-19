<?php
/**
 * Created by PhpStorm.
 * User: pmary-game
 * Date: 4/13/16
 * Time: 7:39 AM
 */
namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Util\LegacyFormHelper;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('nombres');
        $builder->add('apellidos');
        $builder->add('dni');
        $builder->add('telefono');
        $builder->add('plainPassword', LegacyFormHelper::getType('Symfony\Component\Form\Extension\Core\Type\RepeatedType'), array(
        'type' => LegacyFormHelper::getType('Symfony\Component\Form\Extension\Core\Type\PasswordType'),
        'options' => array('translation_domain' => 'FOSUserBundle','always_empty' =>false),
        'first_options' => array('label' => 'form.password'),
        'second_options' => array('label' => 'form.password_confirmation'),
        'invalid_message' => 'fos_user.password.mismatch',
        ));
    }

    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\RegistrationFormType';


    }

    public function getBlockPrefix()
    {
        return 'app_user_registration';
    }

    // For Symfony 2.x
    public function getName()
    {
        return $this->getBlockPrefix();
    }
}