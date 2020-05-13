<?php

namespace OfertasBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/ofertas", name="ofertas")
     */
    public function indexAction()
    {
        return $this->render('OfertasBundle:Default:index.html.twig');
    }
}
