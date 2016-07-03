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

class TendenciaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $entity = $builder->getData();

        $builder->add('type', ChoiceType::class,array(
            'choices'  => array(
                'wedding' => 'Wedding',
                'dinner' => 'Dinner',
                'party' => 'Party',
                'kids' => 'Kids',
            ),
        ));
        $builder->add('nombre');
        $builder->add('description');
        $builder->add('sort');

        if($entity->getId()==NULL){ // so new one
            $builder->add('imgFile', 'file',array(
                'constraints' => array(
                    new NotBlank(array('message' => 'File should not be blank.')),
                    new Image(array(
                        'maxSize'       => '250K',
                        'maxWidth'=>300,
                        'maxHeight'=>200
                        //'mimeTypes'=>array('image/jpeg')
                    ))

                ),
            ));
        }
        else{
            $builder->add('imgFile', 'file',array(
                'constraints' => array(
                    new Image(array(
                        'maxSize'       => '250K',
                        'maxWidth'=>300,
                        'maxHeight'=>200
                        //'mimeTypes'=>array('image/jpeg')
                    ))

                ),
            ));
        }

        $builder->add('submit', SubmitType::class);
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Tendencia',
        ));
    }
}