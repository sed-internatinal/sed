<?php

namespace PromocionesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/promociones", name="promociones")
     */
    public function indexAction()
    {
        return $this->render('PromocionesBundle:Default:index.html.twig');
    }
}
