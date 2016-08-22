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
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\OptionsResolver\OptionsResolver;


class ProveedorAdminProfileType extends AbstractType
{
    private $categorias;
    public function __construct($categorias) {
        $this->categorias = $categorias;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $entity = $builder->getData();
        $builder->add('categoriasListado','entity', array(
            'class' => 'AppBundle:CategoriaListado',
            'choice_label' => 'nombre',
            'choices' =>
                $this->categorias,

            'multiple'      => true,
            'expanded'      => true,
            /*'mapped' => true*/
        ));
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