<?php

namespace MarcasBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/marcas", name="marcas")
     */
    public function indexAction()
    {
        $marcas = $this->getDoctrine()->getRepository("CarroiridianBundle:Marca")->findBy(array("visible"=>1),array("orden"=>"asc"));
        return $this->render('MarcasBundle:Default:index.html.twig',array("marcas"=>$marcas));
    }


}
