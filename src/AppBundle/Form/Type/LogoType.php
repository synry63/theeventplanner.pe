<?php
/**
 * Created by PhpStorm.
 * User: pmary-game
 * Date: 5/11/16
 * Time: 7:48 PM
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

class LogoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('logoFile', 'file',array(
            'constraints' => array(
                new NotBlank(),
                new Image(array(
                    'maxSize'       => '50K',
                    /*'maxWidth'=>250,
                    'maxHeight'=>250,*/
                    'minWidth'=>200,
                    'minHeight'=>200,
                    'allowLandscape'=>false,
                    'allowSquare'=>true,
                    'allowPortrait'=>false,
                    'mimeTypes'=>array('image/jpeg')
                ))

            ),
        ));
        $builder->add('submit', SubmitType::class);
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Logo',
        ));
    }
}