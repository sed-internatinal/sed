<?php

namespace CarroiridianBundle\Controller;

use AppBundle\Entity\User;
use CarroiridianBundle\Entity\Envio;
use CarroiridianBundle\Entity\Bono;
use CarroiridianBundle\Form\Type\BonoType;
use CarroiridianBundle\Form\Type\EnvioType;
use CarroiridianBundle\Utils\Util;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/carrito-de-compras", name="carrito")
     */
    public function indexAction(Request $request)
    {
        $productos = $this->getDoctrine()->getRepository('CarroiridianBundle:Producto')->findBy(array('visible' => true));
        shuffle($productos);
        $productos = array_slice($productos, 0, 4);
        $session = new Session();
        $carrito = $session->get('carrito', array());
        $bonos = $session->get('bonos', array());
        $descuento = $session->get('descuento', array());
        if (count($carrito) == 0) {
            return $this->redirectToRoute('homepage');
        }

        /*
          if($request->get("discount") && count($descuento) == 0){
          $bono = $this->getDoctrine()->getRepository('CarroiridianBundle:Bono')->findBy(array('codigo'=>$request->get("discount"), "reclama" => 0));
          if(count($bono) > 0){
          $session->set('descuento', array("id" => $bono[0]->getId() , "codigo" => $bono[0]->getCodigo(), "de" => $bono[0]->getDe(), "valor" => $bono[0]->getValorbono()->getValor()));
          }
          }
          $descuento = $session->get('descuento', array());
         */

        $securityContext = $this->container->get('security.authorization_checker');
        if (!$securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirectToRoute('registro_login');
        }

        /** @var $user User */
        $user = $this->getUser();
        $direcciones = $user->getDirecciones();
        $envio = new Envio();
        $user_dir_check = '';

        if ($request->isMethod('GET')) {
            $user_dir_check = 'checked';
            if ($user->getDepartamento()) {
                $envio->setDepartamento($user->getDepartamento());
                $envio->setCiudad($user->getCiudad());
                $envio->setDireccion($user->getDireccion());
            } else {
                $departamento = $this->getDoctrine()->getRepository('CarroiridianBundle:Departamento')->find(2);
                $envio->setDepartamento($departamento);
            }
            $envio->setUser($user);
        }

        $form = $this->createForm(EnvioType::class, $envio);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $envio = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($envio);
            $em->flush();
            $session = new Session();
            $session->set('direccion_id', $envio->getId());

            return $this->redirectToRoute('pagar_zona_virtual');

            /* if($this->container->getParameter("pagos_payu.api_payu")){
              return $this->redirectToRoute('tarjetas');
              }else{
              return $this->redirectToRoute('pagar_payu');
              } */
        } else {
            $checked = $request->request->get('direccion');
            if ($checked)
                ;
            $user_dir_check = 'checked';
        }

        return $this->render('CarroiridianBundle:Default:index.html.twig', array('carrito' => $carrito, 'bonos' => $bonos, 'descuento' => $descuento,
            'direcciones' => $direcciones,
            'form' => $form->createView(),
            'user_dir_check' => $user_dir_check,
            'productos' => $productos,
        ));
    }

    /**
     * @Route("/the_store", name="home")
     */
    public function homeAction()
    {
        return $this->render('CarroiridianBundle:Default:home.html.twig');
    }

    /**
     * @Route("/datos", name="datos")
     */
    public function datosAction()
    {
        return $this->render('CarroiridianBundle:Default:datos.html.twig');
    }

    /**
     * @Route("/direccion", name="direccion")
     */
    public function direccionAction(Request $request)
    {
        return $this->redirectToRoute('homepage');
        $securityContext = $this->container->get('security.authorization_checker');
        if (!$securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $url = $this->generateUrl('homepage');
            $response = new RedirectResponse($url);

            return $response;
        }
        $user = $this->getUser();
        $direcciones = $user->getDirecciones();
        $envio = new Envio();
        if ($request->isMethod('GET')) {
            $departamento = $this->getDoctrine()->getRepository('CarroiridianBundle:Departamento')->find(2);
            $envio->setUser($user);
            $envio->setDepartamento($departamento);
        }
        $form = $this->createForm(EnvioType::class, $envio);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $envio = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($envio);
            $em->flush();

            $session = new Session();
            $session->set('direccion_id', $envio->getId());

            return $this->redirectToRoute('pagar_payu');
        }

        return $this->render('CarroiridianBundle:Default:direccion.html.twig', array('form' => $form->createView(), 'direcciones' => $direcciones));
    }

    /**
     * @Route("/direccion-sesion/{id}", name="direccion_sesion")
     */
    public function direccionSesionAction(Request $request, $id)
    {
        $envio = $this->getDoctrine()->getRepository('CarroiridianBundle:Envio')->find($id);
        $session = new Session();
        $session->set('direccion_id', $envio->getId());

        return $this->redirectToRoute('pagar_payu');
    }

    /**
     * @Route("/mensaje", name="mensaje")
     */
    public function mensajeAction()
    {
        return $this->render('CarroiridianBundle:Default:mensaje.html.twig');
    }

    /**
     * @Route("/add-carrito/{id}/{cant}", name="add_carrito")
     */
    public function addCarritoAction($id, $cant)
    {
        $session = new Session();

        $carrito = $session->get('carrito', array());

        $repo = $this->getDoctrine()->getRepository('CarritoBundle:Producto');
        $producto = $repo->find($id);

        try {
            if (array_key_exists($producto->getId(), $carrito)) {
                $prod_carrito = $carrito[$producto->getId()]['cantidad'];
            } else {
                $prod_carrito = 0;
            }
        } catch (Exception $e) {
            $prod_carrito = 0;
        }
        $qi = $this->get('qi');
        if (!is_numeric($prod_carrito)) {
            $prod_carrito = 0;
        }
        $prod_carrito += $cant;
        if ($prod_carrito < 0) {
            $prod_carrito = 0;
        }
        $carrito[$producto->getId()] = array('cantidad' => $prod_carrito, 'nombre' => $producto->getNombreEs(), 'precio' => $qi->getPrecioUser($producto->getId(), $this->getUser()));
        if ($prod_carrito < 1) {
            unset($carrito[$producto->getId()]);
        }

        $session->set('carrito', $carrito);

        return new JsonResponse(array('cantidad' => $prod_carrito));
    }

    /**
     * @Route("/add-carrito-talla/{id}/{cant}/{id_talla}", name="add_carrito_talla")
     */
    public function addCarritoTallaAction($id, $cant, $id_talla)
    {
        $session = new Session();

        $carrito = $session->get('carrito', array());

        $repo = $this->getDoctrine()->getRepository('CarroiridianBundle:Producto');
        $producto = $repo->find($id);

        try {
            if (array_key_exists($producto->getId(), $carrito)) {
                if (array_key_exists($id_talla, $carrito[$producto->getId()])) {
                    $prod_carrito = $carrito[$producto->getId()][$id_talla]['cantidad'];
                } else {
                    $prod_carrito = 0;
                }
            } else {
                $prod_carrito = 0;
            }
        } catch (Exception $e) {
            $prod_carrito = 0;
        }

        if (!is_numeric($prod_carrito)) {
            $prod_carrito = 0;
        }
        $prod_carrito += $cant;
        if ($prod_carrito < 0) {
            $prod_carrito = 0;
        }
        $carrito[$producto->getId()][$id_talla] = array('cantidad' => $prod_carrito, 'nombre' => $producto->getNombreEs(), 'precio' => $producto->getPrecio());
        if ($prod_carrito < 1) {
            unset($carrito[$producto->getId()][$id_talla]);
        }

        $session->set('carrito', $carrito);

        return new JsonResponse(array('cantidad' => $prod_carrito));
    }

    /**
     * @Route("/remove-carrito-bono/{id}", name="remove_carrito_bono")
     */
    public function removeCarritoBonoAction($id)
    {
        $session = new Session();

        $bonos = $session->get('bonos', array());
        if (array_key_exists($id, $bonos)) {
            unset($bonos[$id]);
        }
        $session->set('bonos', $bonos);

        return new JsonResponse(array('cantidad' => 0));
    }

    /**
     * @Route("/set-carrito-talla/{id}/{cant}/{id_talla}", name="set_carrito_talla")
     */
    public function setCarritoTallaAction($id, $cant, $id_talla)
    {
        $session = new Session();

        $carrito = $session->get('carrito', array());

        $repo = $this->getDoctrine()->getRepository('CarroiridianBundle:Producto');
        $producto = $repo->find($id);

        try {
            if (array_key_exists($producto->getId(), $carrito)) {
                if (array_key_exists($id_talla, $carrito[$producto->getId()])) {
                    $prod_carrito = $carrito[$producto->getId()][$id_talla]['cantidad'];
                } else {
                    $prod_carrito = 0;
                }
            } else {
                $prod_carrito = 0;
            }
        } catch (Exception $e) {
            $prod_carrito = 0;
        }

        if (!is_numeric($prod_carrito)) {
            $prod_carrito = 0;
        }
        $prod_carrito = $cant;
        if ($prod_carrito < 0) {
            $prod_carrito = 0;
        }
        $carrito[$producto->getId()][$id_talla] = array('cantidad' => $prod_carrito, 'nombre' => $producto->getNombreEs(), 'precio' => $producto->getPrecio());
        if ($prod_carrito < 1) {
            unset($carrito[$producto->getId()][$id_talla]);
        }

        $session->set('carrito', $carrito);

        return new JsonResponse(array('cantidad' => $prod_carrito));
    }

    /**
     * @Route("/ciudades-dept/{id}/{id_ciudad}", name="ciudadesByDept", defaults={"id_ciudad":"a"})
     */
    public function ciudadesByDeptAction($id, $id_ciudad)
    {
        $ciudad_id = 0;
        if (is_numeric($id_ciudad)) {
            $ciudad_id = $id_ciudad;
        }
        $ciudades = $this->getDoctrine()->getRepository('CarroiridianBundle:Ciudad')->findBy(array('departamento' => $id, 'visible' => true), array('nombre' => 'asc'));

        return $this->render('CarroiridianBundle:Default:ciudades.html.twig', array('ciudades' => $ciudades, 'ciudad_id' => $ciudad_id));
    }

    // START LEO

    /**
     * @Route("/get-costo-envio-tcc/{ciudad_id}/", name="getCostoEnvioTCC")
     */
    public function getCostoEnvioTCC(Request $request, $ciudad_id)
    {
        $ciudad_orm = $this->getDoctrine()->getRepository('CarroiridianBundle:Ciudad');
        $ciudad = $ciudad_orm->findOneBy(['id' => $ciudad_id]);
        if ($ciudad == null) {
            return new JsonResponse(['detail' => 'City not found'], 404);
        }
        $session = new Session();
        $carrito = $session->get('carrito', array());
        $user = $this->getUser();
        $data_envio = Util::getCostoEnvio($carrito, $ciudad, $user, $this->getDoctrine());
        return new JsonResponse([
            'total_despacho' => $data_envio['costo_envio'],
            'total_despacho_mas_iva' => $data_envio['costo_envio_mas_iva'],
        ]);
    }

    // TEST VIEWS

    /**
     * @Route("/test-grabar-envio/{compra_id}/", name="testGrabarEnvio")
     */
    public function testGrabarEnvio(Request $request, $compra_id)
    {
        $compra_orm = $this->getDoctrine()->getRepository('CarroiridianBundle:Compra');
        $compra = $compra_orm->findOneBy(['id' => $compra_id]);
        if ($compra == null) {
            return new JsonResponse(['detail' => 'City not found'], 404);
        }
//        $tcc_log = Util::grabarEnvio($this->getDoctrine(), $compra);

//        $settings = Util::getSettings($this->getDoctrine(), ['EMAILS_CONFIRMACION_ENVIO']);
//
//        $cabeceras = 'From: no-replay@sed.com' . "\r\n" .
//            'MIME-Version: 1.0' . "\r\n" .
//            'Content-type: text/html; charset=utf-8' . "\r\n" .
//            'X-Mailer: PHP/' . phpversion();
//
//        if ($tcc_log) {
//            $titulo = '| TCC CODIGO: ' . $tcc_log->getRemesa();
//        } else {
//            $titulo = '| TCC CODIGO: ';
//        }

        $compra = $this->getDoctrine()->getRepository('CarroiridianBundle:Compra')->find($compra_id);
        $compraitems = $compra->getCompraitems();
        $costo_envio_mas_iva = $compra->getCostoEnvioMasIva();
        $valor_iva_envio = $costo_envio_mas_iva - $compra->getCostoEnvio();

        $data = [
            'summary' => $compraitems,
            'qi' => $this->get('qi'),
            'compra' => $compra,
            'costo_envio_mas_iva' => $costo_envio_mas_iva,
            'valor_iva_envio' => $valor_iva_envio,
        ];

//        mail($settings['EMAILS_CONFIRMACION_ENVIO'], $titulo, $titulo, $cabeceras);

//        return new JsonResponse([
//            'test' => $tcc_log->getMensaje(),
//        ]);
        return $this->render('PagosPayuBundle:Default:confirma_compra_all_info.html.twig', $data);
    }

    /**
     * @Route("/test-zona-virtual-transaccion/{transaccion_id}",name="test_zona_virtual_transaccion")
     */
    public function TestZonaVirtualTransaccionAction($transaccion_id = null)
    {
        $compra = $this->getDoctrine()->getRepository('CarroiridianBundle:Compra')->findOneBy([
            'transactionId' => $transaccion_id,
        ]);
        $pago = [
            4 => 1,
            25 => 1,
            24 => 1,
            8 => 1,
            5 => 1,
            19 => 1,
        ];
        $items = $compra->getCompraitems();

        $valor_iva_envio = $compra->getCostoEnvioMasIva() - $compra->getCostoEnvio();

        return $this->render('PagosPayuBundle:Default:respuesta_zona_pagos.html.twig', [
            'items' => $items,
            'compra' => $compra,
            'pago' => $pago,
            'valor_iva_envio' => $valor_iva_envio,
        ]);
    }

    // END LEO

    /**
     * @Route("/productos", name="productos")
     */
    public function productosAction()
    {
        return $this->render('CarroiridianBundle:Default:productos.html.twig', array('productos' => array()));
    }

    /**
     * @Route("/wishlist", name="wishlist")
     */
    public function wishlistAction()
    {
        $session = new Session();
        $productos = $session->get('wish', array());

        return $this->render('CarroiridianBundle:Default:wishlist.html.twig', array('productos' => $productos));
    }

    /**
     * @Route("/add-wish/{id}", name="add_wish")
     */
    public function wishAction($id)
    {
        $session = new Session();
        $productos = $session->get('wish', array());
        $ci = $this->get('ci');
        $producto = $ci->getProductoById($id);
        if ($producto != null) {
            $productos[$id] = $producto[0];
            $session->set('wish', $productos);
        }

        return $this->redirectToRoute('wishlist');
    }

    /**
     * @Route("/productos_por_categoria/{categoria}/{nombre}", name="productos_por_categoria")
     */
    public function productosCategoriaAction($categoria)
    {
        $ci = $this->get('ci');
        $productos = $ci->getProductos($categoria);

        return $this->render('CarroiridianBundle:Default:productos.html.twig', array('productos' => $productos, 'categoria' => $categoria));
    }

    /**
     * @Route("/productos_por_genero/{genero}", name="productos_por_genero")
     */
    public function productosGenAction($genero)
    {
        $ci = $this->get('ci');
        $productos = $ci->getProductos(null, $genero);

        return $this->render('CarroiridianBundle:Default:productos.html.twig', array('productos' => $productos, 'genero' => $genero));
    }

    /**
     * @Route("/productos_por_gen_cat/{genero}/{categoria}", name="productos_por_gen_cat")
     */
    public function productosCatGenAction($genero, $categoria)
    {
        $ci = $this->get('ci');
        $productos = $ci->getProductos($categoria, $genero);

        return $this->render('CarroiridianBundle:Default:productos.html.twig', array('productos' => $productos, 'genero' => $genero));
    }

    public function generateRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; ++$i) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }

    /**
     * @Route("/product/{id}/{nombre}", name="product")
     */
    public function productAction($id)
    {
        $path = $this->container->getParameter('app.path.productos');
        $producto = $this->getDoctrine()->getRepository('CarroiridianBundle:Producto')->find($id);
        $imagenes = array();
        array_push($imagenes, $path . '/' . $producto->getImagen());
        foreach ($producto->getGalerias() as $galeria) {
            array_push($imagenes, $path . '/' . $galeria->getImagen());
        }
        $session = new Session();
        $wish_list = $session->get('wish', array());

        return $this->render('CarroiridianBundle:Default:producto.html.twig', array('producto' => $producto, 'imagenes' => $imagenes, 'in_wish' => isset($wish_list[$id])));
    }

    /**
     * @Route("/gift", name="gift")
     */
    public function giftAction(Request $request)
    {
        $bono = new Bono();

        $form = $this->createForm(BonoType::class, $bono);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $bono = $form->getData();
            $bono->setCodigo($this->generateRandomString());
            $em = $this->getDoctrine()->getManager();
            $em->persist($bono);
            $em->flush();

            $session = new Session();
            $bonos = $session->get('bonos', array());
            //$bono = new Bono();
            $bono_s = array('id' => $bono->getId(), 'de' => $bono->getDe(), 'para' => $bono->getPara(), 'valor' => $bono->getValorbono()->getValor(), 'mensaje' => $bono->getMensaje());
            array_push($bonos, $bono_s);
            $session->set('bonos', $bonos);

            return $this->redirectToRoute('carrito');
        }

        return $this->render('HomeBundle:Default:gift.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/buscador-productos", name="busca_productos")
     */
    public function buscadorAction(Request $request)
    {
        return $this->render('CarroiridianBundle:Default:productos.html.twig', array('search' => $request->query->get('search')));
    }

    /**
     * @Route("/tarjeta-omnitural", name="tarjeta-omnitural")
     */
    public function tarjetaAction()
    {
        return $this->render('CarroiridianBundle:Default:tarjeta.html.twig');
    }

    /**
     * @Route("/carrito-completo", name="carrito-completo")
     */
    public function cartAction()
    {
        return $this->render('CarroiridianBundle:Default:carrito-completo.html.twig');
    }
}
