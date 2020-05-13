<?php
/**
 * Created by PhpStorm.
 * User: Iridian 4
 * Date: 26/07/2016
 * Time: 11:43 AM
 */

namespace AppBundle\Form\Type;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $lc = $options["locale"];
        /* @var $qi \AppBundle\Service\QI */
        $qi = $options["qi"];
        $msg="Tipo de consulta";
        if($qi){
            $msg=$qi->getTextoDB("form-placeholder-tipoconsulta");
        }

        $builder
            ->add('nombre')
            ->add('email', EmailType::class)
            ->add('telefono')
            ->add('ciudad')
            ->add('tipoconsulta',EntityType::class,array(
                'class' => 'ContactoBundle\Entity\Tipoconsulta',

                'required'=>true,
                'choice_label'=>"nombre".$lc,
                'placeholder' =>$msg
            ))
            ->add('mensaje');
        $builder->add('conditions', CheckboxType::class, array('mapped' => false, 'constraints' => new IsTrue(array("message" => $qi->getTextoDB("aceptar_terminos")))));

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\ContactList',
            'locale' => 'en',
            "qi"  => null,
            'allow_extra_fields' => true
        ));
    }
}