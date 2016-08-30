<?php
/**
 * Created by PhpStorm.
 * User: pmary-game
 * Date: 4/18/16
 * Time: 9:35 AM
 */
namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;

class ProveedorType extends AbstractType
{
    private $categorias;
    public function __construct($categorias) {
            $this->categorias = $categorias;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
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
        $builder->add('web');
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

        $builder->add('plainPassword', 'Symfony\Component\Form\Extension\Core\Type\RepeatedType', array(
            'type' => 'Symfony\Component\Form\Extension\Core\Type\PasswordType',
            'invalid_message' => 'Los campos de contraseña deben coincidir.',
            'first_options'  => array('label' => 'Password'),
            'second_options' => array('label' => 'Repeat Password'),
            'options' => array('always_empty' =>false),
            'constraints' => array(
                new NotBlank(),

            ),
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

        /*$builder->add('tempFile', 'file',array(
            'constraints' => array(
                new NotBlank(),
                new Image(array(
                    'maxSize'       => '1M',
                    //'maxWidth'=>250,
                    //'maxHeight'=>250,
                    'minWidth'=>250,
                    'minHeight'=>250,
                    'allowLandscape'=>true,
                    'allowSquare'=>true,
                    'allowPortrait'=>true
                    //'mimeTypes'=>array('image/jpeg')
                ))

            ),
        ));*/

        /*$builder->add('field', 'places_autocomplete', array(
            'mapped' => false,
            // Javascript prefix variable
            'prefix' => 'js_prefix_',

            // Autocomplete bound (array|Ivory\GoogleMap\Base\Bound)
            'bound'  => array(-2.1, -3.9, 2.6, 1.4, true, true),

            // Autocomplete types
            //'types'  => array(
                AutocompleteType::CITIES,
                // ...
           // ),

            // Autocomplete component restrictions
            //'component_restrictions' => array(
            //    AutocompleteComponentRestriction::COUNTRY => 'pe',
                // ...
           // ),

            // TRUE if the autocomplete is loaded asynchonously else FALSE
            'async' => false,

            // Autocomplete language
            'language' => 'es',
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