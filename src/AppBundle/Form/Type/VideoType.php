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

class VideoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('type', 'Symfony\Component\Form\Extension\Core\Type\ChoiceType',array(
            'choices'  => array(
                'wedding' => 'Wedding',
                'dinner' => 'Dinner',
                'party' => 'Party',
                'kids' => 'Kids',
            ),
        ));
        $builder->add('player', 'Symfony\Component\Form\Extension\Core\Type\ChoiceType',array(
            'choices'  => array(
                'youtube' => 'Youtube',
                'vimeo' => 'Vimeo'
            ),
        ));

        $builder->add('sources', 'Symfony\Component\Form\Extension\Core\Type\CollectionType', array(
            // each entry in the array will be an "source" field
            'entry_type'   => 'AppBundle\Form\Type\SourceVideoType',
            // these options are passed to each "email" type
            'allow_add'=>true,
            'allow_delete'=>true,
            'by_reference' => false
        ));

        $builder->add('url');
        $builder->add('nombre');
        $builder->add('sort');

        $builder->add('submit', 'Symfony\Component\Form\Extension\Core\Type\SubmitType');
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Video',
        ));
    }
}