<?php

namespace NosotrosBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/quienes_somos", name="nosotros")
     */
    public function nosotrosAction()
    {
        $faqs = $this->getDoctrine()->getRepository('NosotrosBundle:Faq')->findBy(array('visible'=>true),array('orden'=>'asc'));
        return $this->render('NosotrosBundle:Default:nosotros.html.twig',array('faqs'=>$faqs));
    }

    /**
     * @Route("/directorio", name="directorio")
     */
    public function directorioAction(Request $request)
    {
        $query= $request->get("query","");
        $departamentos = $this->getDoctrine()->getRepository("NosotrosBundle:Departamento")->findBy(array("visible"=>true),array("orden"=>"asc"));
        if($query == "")
            $personas = $this->getDoctrine()->getRepository("NosotrosBundle:Persona")->findBy(array("visible"=>true),array("orden"=>"asc"));
        else{
            $personas = $this->getDoctrine()->getRepository("NosotrosBundle:Persona")->createQueryBuilder('p')
                ->where('p.nombre like '."'%".$query."%'")
                ->andWhere("p.visible = 1")
                //->setParameter('query', $query)
                ->orderBy('p.orden', 'ASC')
                ->getQuery()
                ->getResult();
        }

        return $this->render('NosotrosBundle:Default:directorio.html.twig',array("departamentos"=>$departamentos,"personas"=>$personas,"query"=>$query));
    }

    /**
     * @Route("/empresa", name="empresa")
     */
    public function empresaAction()
    {
        return $this->render('NosotrosBundle:Default:empresa.html.twig');
    }

    /**
     * @Route("/programas", name="programas")
     */
    public function programasAction()
    {
        $sess=session_id();
        return $this->render('NosotrosBundle:Default:programas.html.twig',array("session"=>$sess));
    }
}
