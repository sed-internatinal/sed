<?php
namespace TranslationBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityManager;
use TranslationBundle\Entity\Campo;
use TranslationBundle\Entity\Traduccion;

class CampoTypeEdit extends AbstractType
{

    protected $em;

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'TranslationBundle\Entity\Campo',
            'idiomas' => array(),
            'em' => null,
            'campo'=>null
        ));
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $idiomas=$options["idiomas"];
        $em = $options["em"];
        $campo= $options["campo"];
        $hash=$campo->getHash();
        //die(dump($hash));
        $builder->add("hash", Type\HiddenType::class,
            array(
                'required' => false,
                'attr' => array(
                    'value' => $hash,
                ),
                'by_reference' => false
            ));
        foreach ($idiomas as $idioma) {
            $traduccion = $em->getRepository("TranslationBundle:Traduccion")->findOneBy(array("idioma"=>$idioma,"campo"=>$campo));
            $valor = "";
            if($traduccion)
                $valor = $traduccion->getValor();
            $builder->add($idioma->getId(), Type\TextType::class,
                array(
                    'label' => $idioma->getIso(),
                    'required' => false,
                    'attr' => array(
                        'class' => 'wrap_select idioma_text',
                        'data-idioma-id' => $idioma->getId(),
                        'data-campo-id' => $hash,
                        'name' => "idiomas[".$idioma->getId()."]",
                        'value'=>$valor
                    ),
                    'by_reference' => false
                ));

        }

        /*$idiomas=$options["idiomas"];
        foreach ($idiomas as $idioma) {
            $builder->add($idioma->getIso(), Type\TextType::class, [
                'required' => $options['required'],
                'label' => $options['label'],
                'attr' => $options['attr'],
                'translation_domain' => $options['translation_domain'],
            ]);
        }*/

    }



}