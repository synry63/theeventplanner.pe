<?php
/**
 * Created by PhpStorm.
 * User: pmary-game
 * Date: 5/11/16
 * Time: 11:05 AM
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

class FotoListadoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('imgFile', 'file',array(
            'constraints' => array(
                new NotBlank(),
                new Image(array(
                    'maxSize'       => '1M',
                    /*'maxWidth'=>250,
                    'maxHeight'=>250,
                    'minWidth'=>250,
                    'minHeight'=>250,*/
                    'allowLandscape'=>true,
                    'allowSquare'=>true,
                    'allowPortrait'=>true,
                    //'mimeTypes'=>array('image/jpeg')
                ))

            ),
        ));

        $builder->add('submit', 'Symfony\Component\Form\Extension\Core\Type\SubmitType');
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\FotoListado',
        ));
    }
}