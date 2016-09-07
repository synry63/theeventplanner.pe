<?php
/**
 * Created by PhpStorm.
 * User: pmary-game
 * Date: 5/12/16
 * Time: 9:56 AM
 */
namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\OptionsResolver\OptionsResolver;


class ProveedorProfileType extends AbstractType
{
    private $categorias;
    public function __construct($categorias) {
        $this->categorias = $categorias;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {


        $entity = $builder->getData();
        //$test =$options['data'];

        //var_dump($categorias[0]->getNombre());
        //exit;
        $builder->add('description','Symfony\Component\Form\Extension\Core\Type\TextareaType',array(
            'constraints' => array(
                new NotBlank(),

            ),
        ));
        $builder->add('nombre','Symfony\Component\Form\Extension\Core\Type\TextType',array(
            'constraints' => array(
                new NotBlank(),

            )
        ));
        $builder->add('direccion','Symfony\Component\Form\Extension\Core\Type\TextType');
        $builder->add('departamento','Symfony\Component\Form\Extension\Core\Type\TextType',array(
            'constraints' => array(
                new NotBlank(),

            )
        ));
        $builder->add('distrito','Symfony\Component\Form\Extension\Core\Type\TextType',array(
            'constraints' => array(
                new NotBlank(),

            )
        ));
        $builder->add('telefono','Symfony\Component\Form\Extension\Core\Type\TextType',array(
            'constraints' => array(
                new NotBlank(),

            )
        ));
        $builder->add('web','Symfony\Component\Form\Extension\Core\Type\UrlType');
        $builder->add('facebookLink');
        $builder->add('twitterLink');
        $builder->add('pinteresLink');
        $builder->add('instagramLink');
        $builder->add('email', 'Symfony\Component\Form\Extension\Core\Type\EmailType',array(
            'constraints' => array(
                new NotBlank(),

            )
        ));
        $builder->add('username', 'Symfony\Component\Form\Extension\Core\Type\TextType',array(
            'constraints' => array(
                new NotBlank(),

            )
        ));
        /*$builder->add('categoriasListado', CategoriaListadoType::class,array(
            'multiple' => true,
            'expanded' => true,
        ));*/
        /*$builder->add('categoriasListado',EntityType::class, array(
            'class' => 'AppBundle:CategoriaListado',
            'choice_label' => 'nombre',
            'multiple'      => true,
            'expanded'      => true
        ));*/
        /*$builder->add('categoriasListado', EntityType::class, array(
            'class' => 'AppBundle:CategoriaListado',
            'choices' => $categorias,
            'choice_label' => 'nombre'
        ));*/
        /*$builder->add('categoriasListado', EntityType::class, array(
            'class' => 'AppBundle:CategoriaListado',
            'choice_label' => 'nombre',
            'query_builder' => function (EntityRepository  $er) {
                    //var_dump('here');
                    //exit;
                    return $er->createQueryBuilder('c');
                },
        ));*/
        /*$builder->add('code', 'choice', array(
        'choices' => array(
            'Food'  => array('pizza', 'burger', 'icecream'),
            'Music' => array('poney', 'little', 'pocket'),
        ),
        'multiple' => true,
        'expanded' => true,
        'required' => true,
        'mapped' => false,
        //'choice_name' => 1,
        //'choices_as_values' => true,
         ));*/
        $builder->add('categoriasListado','entity', array(
            'class' => 'AppBundle:CategoriaListado',
            'choice_label' => 'nombre',
            'choices' =>
                $this->categorias,

            'multiple'      => true,
            'expanded'      => true,
            /*'mapped' => true*/
        ));
        //$builder->add('logo', LogoType::class);

        /*$builder->add('code', 'choice', array(
            //'class' => 'AppBundle:CategoriaListado',
            'choices' =>
                array(
                'Food'  => array('45'=>'toto', 'burger', 'icecream'),
                'Music' => array('poney', 'little', 'pocket'),
            ),
            //'choices_as_values' => true,
            //'choice_label' => 'nombre',
            'multiple' => true,
            'expanded' => false,
            'mapped' => false
//            'data' => array('0','1')
         ));*/
        $builder->add('submit', 'Symfony\Component\Form\Extension\Core\Type\SubmitType');
    }
    /*public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\RegistrationFormType';
    }*/

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Proveedor',
        ));
    }
}