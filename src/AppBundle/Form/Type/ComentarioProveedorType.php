<?php
/**
 * Created by PhpStorm.
 * User: pmary-game
 * Date: 4/16/16
 * Time: 2:30 PM
 */
namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class ComentarioProveedorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            //->add('nota')
            ->add('comentario')
            ->add('nota',HiddenType::class, array(
                'data' => '4',))
            ->add('save', SubmitType::class)
        ;
    }
}