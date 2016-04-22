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
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Form\Type\CategoriaListadoType;

class ProveedorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $entity = $builder->getData();
        //$categorias =$options['data'];

        //var_dump($categorias[0]->getNombre());
        //exit;

        $builder->add('nombre');
        $builder->add('direccion');
        $builder->add('categoriasListado', CategoriaListadoType::class,array(
            'multiple' => true,
            'expanded' => true,
        ));
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
            'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u');
                },
        ));*/
        /*$builder->add('categoriasListado',EntityType::class, array(
            'class' => 'AppBundle:CategoriaListado',
            'choices' => $group->getUsers(),
            'multiple'      => true,
            'expanded'      => true
        ));*/

        /*$builder->add('logoFile', 'file');

        $builder->add('code', 'choice', array(
            'choices' => array(
                'Food'  => array('pizza', 'burger', 'icecream'),
                'Music' => array('poney', 'little', 'pocket'),
            ),
            'multiple' => true,
            'expanded' => false,
            'required' => true
         ));*/
        $builder->add('send', SubmitType::class);
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Proveedor',
        ));
    }
}