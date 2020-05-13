<?php
/**
 * Created by PhpStorm.
 * User: iridian_dev5
 * Date: 12/05/2017
 * Time: 3:55 PM
 */

namespace PagosPayuBundle\Form\Type;


use EWZ\Bundle\RecaptchaBundle\Form\Type\EWZRecaptchaType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use ContactoBundle\Entity\Contacto;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\NotBlank;
class TokenPayuType  extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $istrue = new IsTrue();
        $istrue->message = "Debe aceptar términos y condiciones";
        $builder
            ->add('name')
            ->add("lastname")
            ->add("email")
            ->add("phone")
            ->add("dni")
            ->add("dir")
            ->add("city")
            ->add("state")
            ->add("country")
            ->add("postalcode")
            ->add('terminos', CheckboxType::class, array(
                'mapped' => false,
                'constraints' => $istrue,
                'invalid_message' => 'You must accept terms and conditions'))
            ->add("card_number", TextType::class, array(
                'mapped' => false,
                'constraints' => array(
                    new NotBlank()
                ),
            ))
            ->add("mes", TextType::class, array(
                'mapped' => false,
                'constraints' => array(
                    new NotBlank()
                ),
            ))
            ->add("ano", TextType::class, array(
                'mapped' => false,
                'constraints' => array(
                    new NotBlank()
                ),
            ))
            //->add('save', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PagosPayuBundle\Entity\TokenPayu',
        ));
    }

}