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

class CampoType extends AbstractType
{

    protected $em;

    /*public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }*/

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'TranslationBundle\Entity\Campo',
            'idiomas' => array(),
            'em' => null
        ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'TranslationBundle\Entity\Campo',
        ));
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $idiomas=$options["idiomas"];
        $em = $options["em"];
        $hash= rand ( 1000 , 9999 );
        $hash=$hash.date("YmdHis");
        $builder->add("hash", Type\HiddenType::class,
            array(
                'required' => false,
                'attr' => array(
                    'value' => $hash,
                ),
                'by_reference' => false
            ));
        foreach ($idiomas as $idioma) {

            $builder->add($idioma->getId(), Type\TextType::class,
                array(
                    'label' => $idioma->getIso(),
                    'required' => false,
                    'attr' => array(
                        'class' => 'wrap_select idioma_text',
                        'data-idioma-id' => $idioma->getId(),
                        'data-campo-id' => $hash,
                        'name' => "idiomas[".$idioma->getId()."]"
                    ),
                    'by_reference' => false
                ));

            $builder->addEventListener(FormEvents::PRE_SUBMIT, function($event) use ($em) {
                $this->onPreSubmit($event,$em);
            });
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

    public function onPreSubmit(FormEvent $event, EntityManager $em)
    {
        die("presubmit");
        $data = $event->getData();
        $form = $event->getForm();

        if(!array_key_exists("hash",$data))
            return true;
        $hash= $data["hash"];
        //die(dump($data));
        $campo=$em->getRepository("TranslationBundle:Campo")->findOneBy(array("hash"=>$hash));

        if(!$campo){
            $campo = new Campo();
            $campo->setHash($hash);
        }
        foreach ($data as $label=>$valor){
            if($label != "hash" && array_key_exists($label,$data)) {
                $idioma=$em->getRepository("TranslationBundle:Idioma")->find($label);
                $traduccion = $em->getRepository("TranslationBundle:Traduccion")->findOneBy(array("idioma"=>$idioma,"campo"=>$campo));
                if(!$traduccion)
                    $traduccion = new Traduccion();
                $traduccion->setIdioma($idioma);
                $traduccion->setValor($valor);
                $traduccion->setCampo($campo);

                $em->persist($traduccion);

            }
            unset($data[$label]);
        }

        $em->flush();
        $event->setData($data);
        //die(dump($data));
    }

}