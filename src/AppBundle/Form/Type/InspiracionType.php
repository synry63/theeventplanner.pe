<?php
/**
 * Created by PhpStorm.
 * User: pmary-game
 * Date: 6/27/16
 * Time: 10:59 AM
 */
namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Image;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InspiracionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /*$builder->add('tendencia',EntityType::class, array(
            'class' => 'AppBundle:Tendencia',
            'choice_label' => 'nombre',
        ));*/
        $builder->add('nombre');
        $builder->add('sort');
        $builder->add('imgFile', 'file',array(
            'constraints' => array(
                new NotBlank(array('message' => 'File should not be blank.')),
                new Image(array(
                        'maxSize'       => '250K')
                )

            ),
        ));
        $builder->add('submit', SubmitType::class);
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Inspiracion',
        ));
    }
}