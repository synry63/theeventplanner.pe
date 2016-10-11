<?php
/**
 * Created by PhpStorm.
 * User: pmary-game
 * Date: 6/24/16
 * Time: 10:35 AM
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
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Image;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NoticiaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $entity = $builder->getData();

        $builder->add('type', 'Symfony\Component\Form\Extension\Core\Type\ChoiceType',array(
            'choices'  => array(
                'wedding' => 'Wedding',
                'dinner' => 'Dinner',
                'party' => 'Party',
                'kids' => 'Kids',
            ),
        ));
        $builder->add('nombre');
        $builder->add('author','entity', array(
            'class' => 'AppBundle:Author',
            'placeholder' => '',
            'choice_label' => 'nombre',
            'constraints' => array(
                new NotBlank(),

            )
        ));

        if($entity->getId()==NULL) { // so new one
            $builder->add('imgFile', 'file',array(
                'constraints' => array(
                    new NotBlank(),
                    new Image(array(
                            'maxSize'       => '300ki')
                    )

                ),
            ));
        }
        else{
            $builder->add('imgFile', 'file',array(
                'constraints' => array(
                    new Image(array(
                            'maxSize'       => '300ki')
                    )

                ),
            ));
        }



        $builder->add('parafos', 'Symfony\Component\Form\Extension\Core\Type\CollectionType', array(
            // each entry in the array will be an "source" field
            'entry_type'   => 'AppBundle\Form\Type\ParafoType',
            // these options are passed to each "email" type
            'allow_add'=>true,
            'allow_delete'=>true,
            'by_reference' => false
        ));

        $builder->add('submit', 'Symfony\Component\Form\Extension\Core\Type\SubmitType');
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Noticia',
        ));
    }
}