<?php

namespace ProductosBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/productos-main", name="productos-main")
     */
    public function indexAction()
    {
        return $this->render('ProductosBundle:Default:index.html.twig');
    }

    /**
     * @Route("/productos-detail/{id}/{nombre}", name="productos-detail")
     */
    public function prodDetAction($id,$nombre)
    {
        $producto = $this->getDoctrine()->getRepository("CarroiridianBundle:Producto")->find($id);
        return $this->render('ProductosBundle:Default:prod_detail.html.twig',array("producto"=>$producto));
    }

    /**
     * @Route("/en-reparacion", name="en-reparacion")
     */
    public function prodRepAction()
    {
        return $this->render('ProductosBundle:Default:en_proceso.html.twig');
    }
}
