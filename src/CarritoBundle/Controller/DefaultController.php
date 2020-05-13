<?php

namespace CarritoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/carrito-compras", name="carrito-compras")
     */
    public function carritoAction()
    {
        return $this->render('CarroiridianBundle:Default:index.html.twig');
    }
}
