<?php
/**
 * Created by PhpStorm.
 * User: Iridian 4
 * Date: 26/07/2016
 * Time: 11:43 AM
 */

namespace BlogiridianBundle\Form\Type;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;

class ComentarioType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre')
            ->add('email')
            ->add('mensaje', TextareaType::class, array('label' => 'Comentario'))
            ->add('acepta', CheckboxType::class, array(
                'label' => 'Deseo recibir notificaciones sobre nuevos comentarios en esta entrada por correo electrónico.',
                'invalid_message' => 'Debes aceptar los terminos y condiciones',
                'attr'=>array('class'=>'terms')
            ))
            ->add('save', SubmitType::class, array('label' => 'Enviar','attr'=>array('class'=>'btnGreen elemIzq')));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BlogiridianBundle\Entity\Comentario',
            'locale' => 'en'
        ));
    }
}