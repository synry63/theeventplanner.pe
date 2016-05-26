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
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Ivory\GoogleMap\Places\AutocompleteComponentRestriction;
use Ivory\GoogleMap\Places\AutocompleteType;

class ProveedorType extends AbstractType
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
        $builder->add('description');
        $builder->add('nombre');
        $builder->add('direccion');
        $builder->add('departamento');
        $builder->add('distrito');
        $builder->add('telefono');
        $builder->add('email', EmailType::class);
        $builder->add('username', TextType::class);
        $builder->add('plainPassword', RepeatedType::class, array(
            'type' => PasswordType::class,
            'first_options'  => array('label' => 'Password'),
            'second_options' => array('label' => 'Repeat Password'),
            'constraints' => array(
                new NotBlank(array('message' => 'Password should not be blank.')),

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
        $builder->add('categoriasListado',EntityType::class, array(
            'class' => 'AppBundle:CategoriaListado',
            'choice_label' => 'nombre',
            'choices' =>
                $this->categorias,

            'multiple'      => true,
            'expanded'      => false,
            /*'mapped' => true*/
        ));
        //$builder->add('logo', LogoType::class);
        $builder->add('tempFile', 'file',array(
            'constraints' => array(
                new NotBlank(array('message' => 'File should not be blank.')),

            ),
        ));

        $builder->add('field', 'places_autocomplete', array(
            'mapped' => false,
            // Javascript prefix variable
            'prefix' => 'js_prefix_',

            // Autocomplete bound (array|Ivory\GoogleMap\Base\Bound)
            'bound'  => array(-2.1, -3.9, 2.6, 1.4, true, true),

            // Autocomplete types
            /*'types'  => array(
                AutocompleteType::CITIES,
                // ...
            ),*/

            // Autocomplete component restrictions
            /*'component_restrictions' => array(
                AutocompleteComponentRestriction::COUNTRY => 'pe',
                // ...
            ),*/

            // TRUE if the autocomplete is loaded asynchonously else FALSE
            'async' => false,

            // Autocomplete language
            'language' => 'es',
        ));

        $builder->add('Registrar', SubmitType::class);
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