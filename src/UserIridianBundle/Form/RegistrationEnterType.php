<?php

namespace UserIridianBundle\Form;


use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\IsTrue;

class RegistrationEnterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('nombre', TextType::class, array('required' => true, 'attr'=>array('class'=>'form-control site-forms box-shadow')));
        $builder->add('nit', TextType::class, array('required' => true, 'attr'=>array('class'=>'form-control site-forms box-shadow')));
        $builder->add('dVerificacion', TextType::class, array('required' => true, 'attr'=>array('class'=>'form-control site-forms box-shadow')));
        $builder->add('email', TextType::class, array('required' => true, 'attr'=>array('class'=>'form-control site-forms box-shadow')));
        $builder->add('telefono', TextType::class, array('required' => false, 'attr'=>array('class'=>'form-control site-forms box-shadow')));
        $builder->add('celular', TextType::class, array('required' => true, 'attr'=>array('class'=>'form-control site-forms box-shadow')));
        $builder->add('ciudad', TextType::class, array('required' => true, 'attr'=>array('class'=>'form-control site-forms box-shadow')));
        $builder->add('certificadoCamara',FileType ::class, array('label' => 'certificado','required' => true));
        $builder->add('estadosFinancieros',FileType ::class, array('label' => 'certificado','required' => true));
        $builder->add('declaracion1',FileType ::class, array('label' => 'certificado','required' => true));
        $builder->add('declaracion2',FileType ::class, array('label' => 'certificado','required' => true));
        $builder->add('cedulaRepresentante',FileType ::class, array('label' => 'certificado','required' => true));
        $builder->add('rut',FileType ::class, array('label' => 'certificado','required' => true));
        //$builder->add('convenio');

        /*$builder->add('direccion', TextType::class, array('required' => true, 'attr'=>array('class'=>'form-control site-forms box-shadow')));
        $builder->add('tipodoc',EntityType::class,array(
            'class' => 'AppBundle\Entity\TipoDoc',
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('d')
                    ->orderBy('d.nombreEs', 'ASC');
            },
            'attr'=>array('class'=>'form-control site-forms box-shadow')
        ));
        $builder->add('documento', TextType::class, array('required' => true, 'attr'=>array('class'=>'form-control site-forms box-shadow')));
        $builder->add('pais', TextType::class, array('required' => true, 'attr'=>array('class'=>'form-control site-forms box-shadow')));
        $builder->add('genero', ChoiceType::class, array(
        'choices'  => array(
            'Femenino' => 'Femenino',
            'Masculino' => 'Masculino',
        ),
        'attr'=>array('class'=>'form-control site-forms box-shadow')
    ));*/
        /*
        $builder->add('departamento',EntityType::class,array(
            'class' => 'CarroiridianBundle\Entity\Departamento',
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('d')
                    ->orderBy('d.nombre', 'ASC');
            }
        ))

        ->add('ciudad')
        ->add('tarjeta', CheckboxType::class, array('required' => false))
        */
        $builder->add('username', HiddenType::class);
        $builder->add('conditions', CheckboxType::class, array('mapped' => false, 'constraints' => new IsTrue(array("message" => "Debes Aceptar términos y condiciones"))));

    }

    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\RegistrationFormType';

        // Or for Symfony < 2.8
        // return 'fos_user_registration';
    }

    public function getBlockPrefix()
    {
        return 'app_user_registration';
    }

    // For Symfony 2.x
    public function getNombre()
    {
        return $this->getBlockPrefix();
    }
}