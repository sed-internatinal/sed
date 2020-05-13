<?php
/**
 * Created by PhpStorm.
 * User: Iridian 1
 * Date: 9/06/2016
 * Time: 11:30 AM
 */

namespace CarroiridianBundle\Form\Type;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Doctrine\ORM\EntityRepository;

class EnvioType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('departamento',EntityType::class,array(
                'class' => 'CarroiridianBundle\Entity\Departamento',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('d')
                        ->orderBy('d.nombre', 'ASC');
                }
            ))
            /*
            ->add('ciudad',EntityType::class,array(
                'class' => 'CarroiridianBundle\Entity\Ciudad',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->leftjoin('c.departamento','d')
                        ->where('d.id = 0')
                        ->orderBy('d.nombre', 'ASC');
                }
            ))
            */
            ->add('ciudad')
            ->add('user')
            ->add('direccion')
            ->add('adicionales', TextType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CarroiridianBundle\Entity\Envio',
        ));
    }
}