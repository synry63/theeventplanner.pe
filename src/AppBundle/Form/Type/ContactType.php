<?php
/**
 * Created by PhpStorm.
 * User: pmary-game
 * Date: 4/12/16
 * Time: 12:27 PM
 */
namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\Email;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array(
                'attr' => array(
                    'placeholder' => 'What\'s your name?',
                    'pattern'     => '.{2,}' //minlength
                ),
                'constraints' => array(
                    new NotBlank(array('message' => 'Name should not be blank.')),

                ),

            ))
            ->add('email', 'email', array(
                'attr' => array(
                    'placeholder' => 'So I can get back to you.',

                ),
                'constraints' => array(
                    new NotBlank(array('message' => 'Email should not be blank.')),
                    new Email(array('message' => 'Invalid email address.'))

                ),
            ))
            ->add('subject', 'text', array(
                'attr' => array(
                    'placeholder' => 'The subject of your message.',
                    'pattern'     => '.{3,}' //minlength
                ),
                'constraints' => array(
                    new NotBlank(array('message' => 'Subject should not be blank.')),


                ),
            ))
            ->add('message', 'textarea', array(
                'attr' => array(
                    'cols' => 90,
                    'rows' => 10,
                    'placeholder' => 'And your message to me...'
                ),
                'constraints' => array(
                    new NotBlank(array('message' => 'Message should not be blank.')),


                ),
            ))
            ->add('send', SubmitType::class
            );

    }
    public function getName()
    {
        return 'contact';
    }
}