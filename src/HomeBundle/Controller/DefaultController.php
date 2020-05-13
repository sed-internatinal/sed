<?php

namespace HomeBundle\Controller;

use CarroiridianBundle\Entity\Producto;
use Doctrine\ORM\EntityManager;
use HomeBundle\Entity\Encuesta;
use HomeBundle\Entity\Resultado;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;



class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        return $this->render('HomeBundle:Default:index.html.twig');
    }
    /**
     * @Route("/mailing", name="mailing")
     */
    public function mailingAction(Request $request)
    {
        $user=$this->getUser();
        return $this->render('FOSUserBundle:Resetting:email.html.twig',array("user"=>$user,'confirmationUrl'=>"http://hola"));
    }

    /**
     * @Route("/terminos", name="terminos")
     */
    public function termninosAction(Request $request)
    {
        return $this->render('HomeBundle:Default:generica.html.twig',array("llave"=>"terminos"));
    }

    /**
     * @Route("/politicas", name="politicas")
     */
    public function politicasAction(Request $request)
    {
        return $this->render('HomeBundle:Default:generica.html.twig',array("llave"=>"politicas"));
    }

    /**
     * @Route("/servicios_info", name="servicios_info")
     */
    public function servicios_infoAction(Request $request)
    {
        return $this->render('HomeBundle:Default:generica.html.twig',array("llave"=>"servicios_info"));
    }

    /**
     * @Route("/selector-servicios", name="selector-servicios")
     */
    public function selector_servicosAction(Request $request)
    {
        return $this->render('HomeBundle:Default:selector_servicios.html.twig');
    }

}
