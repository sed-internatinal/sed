<?php

namespace ServiciosBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/servicios", name="servicios")
     */
    public function indexAction()
    {
        $unidades=$this->getDoctrine()->getRepository("CarroiridianBundle:unidadnegocio")->findAll();
        return $this->render('ServiciosBundle:Default:index.html.twig',array("unidades"=>$unidades));
    }

    /**
     * @Route("/marcas-unidad/{id}", name="unidad-marcas")
     */
    public function unidadMarcasAction($id)
    {
        $unidad = $this->getDoctrine()->getRepository("CarroiridianBundle:unidadnegocio")->find($id);
        $marcas= $unidad->getMarcas();
        return $this->render('ServiciosBundle:Default:unidad_transaccional.html.twig',array("unidad"=>$unidad,"marcas"=>$marcas));
    }

    /**
     * @Route("/unidad-transaccional", name="unidad-transaccional")
     */
    public function unidadTAction()
    {
        return $this->render('ServiciosBundle:Default:unidad_transaccional.html.twig');
    }

    /**
     * @Route("/unidad-valor", name="/unidad-valor")
     */
    public function unidadVAction()
    {
        return $this->render('ServiciosBundle:Default:unidad_valor.html.twig');
    }

    /**
     * @Route("/unidad-soluciones", name="/unidad-soluciones")
     */
    public function unidadSAction()
    {
        return $this->render('ServiciosBundle:Default:unidad_soluciones.html.twig');
    }
}
