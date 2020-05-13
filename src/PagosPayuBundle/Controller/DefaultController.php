<?php

namespace PagosPayuBundle\Controller;

use CarroiridianBundle\Entity\Compra;
use CarroiridianBundle\Entity\Compraitem;
use CarroiridianBundle\Entity\Entrada;
use CarroiridianBundle\Entity\Envio;
use CarroiridianBundle\Entity\PagoLogger;
use JMS\Serializer\SerializerBuilder;
use PagosPayuBundle\Entity\Logger;
use PagosPayuBundle\Entity\RepuestaPago;
use PagosPayuBundle\Entity\TokenPayu;
use PagosPayuBundle\Form\Type\TokenPayuType;
use PhpParser\Node\Scalar\MagicConst\Method;
use Proxies\__CG__\CarroiridianBundle\Entity\Inventario;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class DefaultController extends Controller
{
    /**
     * @Route("/pagar-payu",name="pagar_payu")
     */
    public function indexAction()
    {
       $session = new Session();
        $direccion_id = $session->get('direccion_id');
        $direccion = $this->getDoctrine()->getRepository('CarroiridianBundle:Envio')->find($direccion_id);
        $carrito = $session->get('carrito', null);
        $bonos = $session->get("bonos", null);
        $descuento = $session->get("descuento", null);

        $estado = $this->getDoctrine()->getRepository('CarroiridianBundle:EstadoCarrito')->findOneBy(array('ref'=>'INICIADA_EN_WEB'));
        $datos_payu = $this->getDoctrine()->getRepository('PagosPayuBundle:DatosPayu')->find(1);
        $ci = $this->get('ci');
        $qi=$this->get("qi");

        $user = $this->getUser();
        $compra = new Compra();
        $compra->setComprador($user);
        $compra->setDireccion($direccion);
        $compra->setEstado($estado);
        $compra->setPrueba($datos_payu->getTest());
        $em = $this->getDoctrine()->getManager();
        $repo_p = $this->getDoctrine()->getRepository('CarroiridianBundle:Producto');
        $repo_t = $this->getDoctrine()->getRepository('CarroiridianBundle:Talla');
        $repo_b = $this->getDoctrine()->getRepository('CarroiridianBundle:Bono');
        $em->persist($compra);
        $em->flush();
        if(isset($descuento['id'])){
            $bono_temp = $repo_b->find($descuento['id']);
            $bono_temp->setReclama(1);
            $em->persist($bono_temp);
            $em->flush();
            unset($bono_temp);
        }
        $ciudad = $this->getDoctrine()->getRepository('CarroiridianBundle:Ciudad')->find($compra->getDireccion()->getCiudad()->getId());
        $nombreCiudad = $ciudad->getNombre();

        /** @var  $envio Envio */
        $envio = $compra->getDireccion();
        $costo_envio = $envio->getCiudad()->getCosto();
        $total = 0;
        $descripcion_text = '';
        $descripcion = '<table align="center" border="0" cellpadding="5px" cellspacing="0" class="mcnTextContentContainer" style="color: #999;font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif;max-width: 100%;min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;" width="100%">';
        $descripcion .= '<thead>
                            <tr>
                                <th></th>
                                <th>Producto</th>
                                <th>Cantidad</th>
                                <th>Precio</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>';
        $root = $this->generateUrl('homepage', array(), UrlGeneratorInterface::ABSOLUTE_URL);
        str_replace('en','',$root);
        foreach ($carrito as $id=>$tallas){
            foreach ($tallas as $id_talla=>$item){
                if($item['cantidad'] > 0){
                    $producto = $repo_p->find($id);
                    $talla = $repo_t->find($id_talla);
                    $compraitem = new Compraitem();
                    $compraitem->setCantidad($item['cantidad']);
                    $compraitem->setProducto($producto);
                    $compraitem->setTalla($talla);
                    $compraitem->setCompra($compra);
                    $em->persist($compraitem);
                    $em->flush();
                    $subtotal = $item['cantidad'] * $qi->getPrecioUser($producto->getId(),$this->getUser());
                    $total += $subtotal;
                    $descripcion .= '<tr style="border-bottom: 1px solid #eae9e9;">';
                        $descripcion .= '<td>
                                            <img style="width: 70px;display:inline-block;vertical-align:middle" alt="Omnitural" src="'.$root.'uploads/productos/'.$producto->getImagen().'" class="CToWUd">
                                        </td>';
                        $descripcion .= '<td style="text-align:center;"><p style="display:inline-block;vertical-align:middle">
                                            <span style="width:100%;display:inline-block;text-align: center;">'.$producto.'</span>
                                        </p></td>';
                        $descripcion .= '<td style="text-align:center;">'.$item['cantidad'].'</td>';
                        $descripcion .= '<td style="text-align:center;"> $'.number_format($qi->getPrecioUser($producto->getId(),$this->getUser())).'</td>';
                        $descripcion .= '<td style="text-align:center;"> $'.number_format($subtotal).'</td>';
                    $descripcion .= '</tr>';

                    $descripcion_text .= '|'.$producto.' - ';
                    $descripcion_text .= $talla.' - ';
                    $descripcion_text .= '$'.number_format($qi->getPrecioUser($producto->getId(),$this->getUser())).' - ';
                    $descripcion_text .= 'X'.$item['cantidad'].' - ';
                    $descripcion_text .= '$'.number_format($subtotal).' | ';
                }
            }
        }
        if(count($bonos) > 0 && $bonos != null){
            foreach ($bonos as $id=>$bono){
                $bono_src = $repo_b->find($bono['id']);
                $bono_src->setCompra($compra);
                $em->persist($bono_src);
                $em->flush();
                $subtotal = $bono['valor'];
                $total += $subtotal;
                $descripcion .= '<tr>';
                $descripcion .= '<td>'.$bono['de'].'</td>';
                $descripcion .= '<td>'.$bono['para'].'</td>';
                $descripcion .= '<td> $'.number_format($bono['valor']).'</td>';
                $descripcion .= '<td>1</td>';
                $descripcion .= '<td> $'.$bono['valor'].'</td>';
                $descripcion .= '</tr>';
                $descripcion_text .= '|Bono de regalo, de '.$bono["de"].' para ' . $bono["para"] . ' - ';
                $descripcion_text .= '$'.number_format($bono['valor']).' - ';
                $descripcion_text .= 'X1 - ';
                $descripcion_text .= '$'.number_format($bono['valor']).' | ';
            }
        }
        $descripcion .= '<tr>
                            <td colspan="3"></td>
                            <td style="text-align: right;"><strong>Subtotal</strong></td>
                            <td style="text-align:center;">$'.number_format($total).'</td>
                        </tr>
                        <tr>
                            <td colspan="3"></td>
                            <td style="text-align: right;"><strong>Envio</strong></td>
                            <td style="text-align:center;">$'.number_format($costo_envio).'</td>
                        </tr>
                        <tr>
                            <td colspan="3"></td>
                            <td style="text-align: right;"><strong>Total</strong></td>
                            <td style="text-align:center;">$'.number_format($total + $costo_envio).'</td>
                        </tr>';

        $descripcion .= '</table>';

        $descripcion .= '<br>
                        <table align="center" border="0" cellpadding="5px" cellspacing="0" class="mcnTextContentContainer" style="text-align: center;border: 1px solid #eae9e9;color: #999;font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif;max-width: 100%;min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;" width="100%">
                            <tbody>
                                <tr style="border-bottom: 1px solid #eae9e9;">
                                    <td style="    background-color: #A9D4EF; color:#fff;">
                                        <strong>Departamento</strong>
                                    </td>
                                    <td>
                                        '.$envio->getDepartamento().'
                                    </td>
                                </tr>
                                <tr style="border-bottom: 1px solid #eae9e9;">
                                    <td style="    background-color: #A9D4EF; color:#fff;">
                                        <strong>Ciudad</strong>
                                    </td>
                                    <td>
                                        '.$envio->getCiudad().'
                                    </td>
                                </tr>
                                <tr style="border-bottom: 1px solid #eae9e9;">
                                    <td style="    background-color: #A9D4EF; color:#fff;">
                                        <strong>Dirección</strong>
                                    </td>
                                    <td>
                                        '.$envio->getDireccion().'
                                    </td>
                                </tr>
                                <tr style="border-bottom: 1px solid #eae9e9;">
                                    <td style="    background-color: #A9D4EF; color:#fff;">
                                        <strong>Datos adicionales</strong>
                                    </td>
                                    <td>
                                        '.nl2br($envio->getAdicionales()).'
                                    </td>
                                </tr>
                            </tbody>
                        </table>';

        $compra->setDescripcion($descripcion);
        $total = $total + $costo_envio;
        $compra->setPrecio($total);
        $em->persist($compra);
        $em->flush();
        $descripcion_text .= 'Costo envio, $' . number_format($direccion->getCiudad()->getCosto()) . ' |';
        $descripcion_text .= 'TOTAL: $'.number_format($total);
        $tax = round($total*0.19/1.19,2);
        $taxReturnBase = round($total/1.19,2);
        $referenceCode = 'Test_BS_'.$compra->getId();
        $firma = md5($datos_payu->getApiKey().'~'.$datos_payu->getMerchantId().'~'.$referenceCode.'~'.$total.'~'.$datos_payu->getCurrency());
        //$responseUrl = $this->generateUrl('pagar_payu_respuesta',null,UrlGeneratorInterface::ABSOLUTE_URL);
        //$confirmationUrl = $this->generateUrl('pagar_payu_confirmacion',array(),UrlGeneratorInterface::ABSOLUTE_URL);

        /*
            $session->set('carrito', array());
            $session->set('bonos', array());
            $session->set('descuento', array());
        */
        return $this->render('PagosPayuBundle:Default:index.html.twig',
            array(
            'compra'=>$compra,
            'tax'=>$tax,
            'taxReturnBase'=>$taxReturnBase,
            'firma'=>$firma,
            'referenceCode'=>$referenceCode,
            'descripcion_text'=>substr($descripcion_text,0.250),
            'datos_payu'=>$datos_payu,
            'nombreCiudad'=>$nombreCiudad
        ));
    }


    /**
     * @Route("/pagar-payu/respuesta",name="pagar_payu_respuesta")
     */
    public function respuestaAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $full_url = serialize($request->query->all());
        $logger = new PagoLogger();
        $logger->setRespuesta($full_url);
        $em->persist($logger);

        $datos_payu = $this->getDoctrine()->getRepository('PagosPayuBundle:DatosPayu')->find(1);
        $ApiKey = $datos_payu->getApiKey();
        $merchant_id = $datos_payu->getMerchantId();
        $referenceCode = $request->query->get('referenceCode');
        $TX_VALUE = $request->query->get('TX_VALUE');
        $New_value = number_format($TX_VALUE, 1, '.', '');
        $currency = $request->query->get('currency');
        $transactionState = $request->query->get('transactionState');

        $firma_cadena = "$ApiKey~$merchant_id~$referenceCode~$New_value~$currency~$transactionState";
        $firmacreada = md5($firma_cadena);
        $firma = $request->query->get('signature');

        $reference_pol = $request->query->get('reference_pol');
        $cus = $request->query->get('cus');
        $description = $request->query->get('description');
        $pseBank = $request->query->get('pseBank');
        $lapPaymentMethod = $request->query->get('lapPaymentMethod');
        $transactionId = $request->query->get('transactionId');
        $processingDate = $request->query->get('processingDate');
        $TX_ADMINISTRATIVE_FEE = $request->query->get('TX_ADMINISTRATIVE_FEE');

        $arr_ref = explode('_',$referenceCode);
        $id_compra = end($arr_ref);
        $compra = $this->getDoctrine()->getRepository('CarroiridianBundle:Compra')->find($id_compra);
        $respuesta = new RepuestaPago();
        $respuesta->setReferenceCode($referenceCode);
        $respuesta->setTXVALUE($TX_VALUE);
        $respuesta->setTransactionState($transactionState);
        $respuesta->setSignature($firma);
        $respuesta->setReferencePol($reference_pol);
        $respuesta->setCus($cus);
        $respuesta->setPseBank($pseBank);
        $respuesta->setLapPaymentMethod($lapPaymentMethod);
        $respuesta->setTransactionId($transactionId);
        $respuesta->setProcessingDate($processingDate);
        $respuesta->setTXADMINISTRATIVEFEE($TX_ADMINISTRATIVE_FEE);
        $respuesta->setDescription($description);
        $respuesta->setCompra($compra);
        $respuesta->setTipo('RESPUESTA');
        $em->persist($respuesta);
        $em->flush();



        if (strtoupper($firma) == strtoupper($firmacreada)) {

            if ($transactionState == 4 ) {
                $estadoTx = "APROBADA";
            }

            else if ($transactionState == 6 ) {
                $estadoTx = "RECHZADA";
            }

            else if ($transactionState == 104 ) {
                $estadoTx = "PENDIENTE";
            }

            else if ($transactionState == 7 ) {
                $estadoTx = "ERROR";
            }

            else {
                $estadoTx=$_REQUEST['mensaje'];
            }

            $estado = $this->getDoctrine()->getRepository('CarroiridianBundle:EstadoCarrito')->findOneBy(array('ref'=>$estadoTx));
            $compra->setEstado($estado);
            $em->flush();

            return $this->render('PagosPayuBundle:Default:respuesta.html.twig',array(
                'estadoTx'=>$estadoTx,'transactionId'=>$transactionId,'reference_pol'=>$reference_pol,'referenceCode'=>$referenceCode,
                'pseBank'=>$pseBank,'cus'=>$cus,'TX_VALUE'=>$TX_VALUE,
                'currency'=>$currency,'lapPaymentMethod'=>$lapPaymentMethod));
        }else{
            return $this->render('HomeBundle:Default:index.html.twig');
        }
    }

    public function SendMail($subject, $from, $to, $custom, $template){
        try {
            $message = \Swift_Message::newInstance()
                ->setSubject($subject)
                ->setFrom($from)
                ->setTo($to)
                ->setBody(
                    $this->renderView(
                        $template,
                        $custom
                    ),
                    'text/html'
                );
            $this->get('mailer')->send($message);
        }catch (\Exception $e){

        }
    }


    /**
     * @Route("/pagar-payu/confirmacion",name="pagar_payu_confirmacion", methods={"POST"})
     */
    public function confirmacionAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $full_url = serialize($request->request->all());
        $logger = new PagoLogger();
        $logger->setRespuesta($full_url);
        $em->persist($logger);


        $datos_payu = $this->getDoctrine()->getRepository('PagosPayuBundle:DatosPayu')->find(1);
        $ApiKey = $datos_payu->getApiKey();
        $merchant_id = $datos_payu->getMerchantId();

        $reference_sale = $request->request->get('reference_sale');
        $value = $request->request->get('value');
        $new_value = number_format($value, 1, '.', '');
        $currency = $request->request->get('currency');
        $state_pol = $request->request->get('state_pol');
        $firma_cadena = "$ApiKey~$merchant_id~$reference_sale~$new_value~$currency~$state_pol";

        $firmacreada = md5($firma_cadena);
        $firma = $request->request->get('sign');
        $reference_pol = $request->query->get('reference_pol');
        $cus = $request->request->get('cus');
        $description = $request->query->get('description');
        $pseBank = $request->request->get('pse_bank');
        $lapPaymentMethod = $request->query->get('payment_method_name');
        $transactionId = $request->request->get('transaction_id');
        $processingDate = $request->request->get('transaction_date');
        $TX_ADMINISTRATIVE_FEE = $request->request->get('administrative_fee');
        $risk = $request->request->get('risk');

        $confirma_compra  = $this->getDoctrine()->getRepository('AppBundle:Mailing')->findBy(["llave" => "confirma_compra"]);
        $entrega_bono  = $this->getDoctrine()->getRepository('AppBundle:Mailing')->findBy(["llave" => "entrega_bono"]);
        $confirma_compra_bono  = $this->getDoctrine()->getRepository('AppBundle:Mailing')->findBy(["llave" => "confirma_compra_bono"]);


        $arr_ref = explode('_',$reference_sale);
        $id_compra = end($arr_ref);
        $compra = $this->getDoctrine()->getRepository('CarroiridianBundle:Compra')->find($id_compra);
        $respuesta = new RepuestaPago();
        $respuesta->setReferenceCode($reference_sale);
        $respuesta->setTXVALUE($value);
        $respuesta->setTransactionState($state_pol);
        $respuesta->setSignature($firma);
        $respuesta->setReferencePol($reference_pol);
        $respuesta->setCus($cus);
        $respuesta->setPseBank($pseBank);
        $respuesta->setLapPaymentMethod($lapPaymentMethod);
        $respuesta->setTransactionId($transactionId);
        $respuesta->setProcessingDate($processingDate);
        $respuesta->setTXADMINISTRATIVEFEE($TX_ADMINISTRATIVE_FEE);
        $respuesta->setDescription($description);
        $respuesta->setCompra($compra);
        $respuesta->setRisk($risk);
        $respuesta->setTipo('CONFIRMACION');
        $em->persist($respuesta);
        $em->flush();

        if($state_pol == 4){
            $compraitems = $compra->getCompraitems();
            $to = $compra->getEmail();
            $name = $compra->getUsuario();
            $product_sumary = [];
            foreach ($compraitems as $item){
                if(0){$item = new Compraitem();}

                $repo = $this->getDoctrine()->getRepository('CarroiridianBundle:Inventario');
                $inventario = $repo->findOneBy(array(
                    'producto'=>$item->getProducto(),
                    'talla'=>$item->getTalla()
                ));
                $salida = new Entrada();
                $salida->setCantidad(-1 * $item->getCantidad());
                $salida->setInventario($inventario);
                $salida->setFecha(new \DateTime());
                $em->persist($salida);
                $em->flush();

                $inventario = new Inventario();

                $inventario->setCantidad($inventario->getCantidad() - $item->getCantidad());
                $em->persist($inventario);
                $em->flush();
                //public function SendMail($subject, $from, $to, $custom, $template)
            }

            $bonos  = $this->getDoctrine()->getRepository('CarroiridianBundle:Bono')->findBy(array('compra'=>$id_compra));
            $admin  = $this->getDoctrine()->getRepository('AppBundle:Settings')->findBy(array('llave'=>"admin_mail"));
            $sender  = $this->getDoctrine()->getRepository('AppBundle:Settings')->findBy(array('llave'=>"sender_mail"));
            //exit(\Doctrine\Common\Util\Debug::dump());

            $lc = $this->get('translator')->getLocale();
            if(count($bonos) > 0){
                $this->SendMail($confirma_compra_bono[0]->getAsunto()->getLocalName($lc), $sender[0]->getValor(), $to, array("name" => $name, "data" => $confirma_compra_bono[0]), "PagosPayuBundle:Default:confirma_compra_bono.html.twig");
                foreach ($bonos as $bono){
                    $this->SendMail($entrega_bono[0]->getAsunto()->getLocalName($lc), $sender[0]->getValor(), $to, array("code" => $bonos[0]->getCodigo(), "from" => $bonos[0]->getDe(), "to" => $bonos[0]->getPara(), "data" => $entrega_bono[0]), "PagosPayuBundle:Default:entrega_bono.html.twig");
                }
            }
            $qi = $this->get('qi');
            $from = $qi->getSettingDB('mail_envio');
            $message = $qi->getTextoBigDB('mail_compra_aprobada');
            $message = str_replace('%contenido%',$compra->getDescripcion(),$message);
            $qi->sendMail($qi->getTextoDB('mail_compra'), $from, $compra->getEmail(), array(), $message);

            //mail interno de confirmacion
            $qi->sendMail( $qi->getTextoDB('asunto_compra_aprobada'), $from, $admin, array(), $compra->getDescripcion());

            //$this->SendMail($confirma_compra[0]->getAsunto()->getLocalName($lc), $sender[0]->getValor(), $to, array("summary" => $compraitems, "data" => $confirma_compra[0]), "PagosPayuBundle:Default:confirma_compra.html.twig");
        }

        $em->flush();

        if (strtoupper($firma) == strtoupper($firmacreada) || 1) {
            if ($state_pol == 4 ) {
                $estadoTx = "APROBADA";
            }

            else if ($state_pol == 6 ) {
                $estadoTx = "RECHZADA";
            }

            else if ($state_pol == 5 ) {
                $estadoTx = "EXPIRADA";
            }
            else {
                $estadoTx = "PENDIENTE";
            }
            $estado = $this->getDoctrine()->getRepository('CarroiridianBundle:EstadoCarrito')->findOneBy(array('ref'=>$estadoTx));
            $compra->setEstado($estado);
            $em->flush();

            return new Response('Hello PAYU', Response::HTTP_OK);
        }else{
            return new Response('Hello PAYU', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }

    //API PAYU !!!

    /**
     * @Route("/tarjetas", name="tarjetas")
     */
    public function tarjetasAction(Request $request)
    {
        $securityContext = $this->container->get('security.authorization_checker');
        if (!$securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirectToRoute('registro_login');
        }
       $session = new Session();
        $direccion_id = $session->get('direccion_id', null);
        $carrito = $session->get('carrito', array());
        if(count($carrito) == 0){
            return $this->redirectToRoute("aviso");
        }
        $direccion = null;
        if($direccion_id){
            $direccion = $this->getDoctrine()->getRepository('CarroiridianBundle:Envio')->find($direccion_id);
        }
        $total = 0;
        $qi=$this->get("qi");
        $repo_p = $this->getDoctrine()->getRepository('CarroiridianBundle:Producto');
        foreach ($carrito as $id=>$tallas){
            foreach ($tallas as $id_talla=>$item){
                if($item['cantidad'] > 0){
                    $producto = $repo_p->find($id);
                    $subtotal = $item['cantidad'] * $qi->getPrecioUser($producto->getId(),$this->getUser());
                    $total += $subtotal;
                }
            }
        }

        $costo_city = 0;
        if($direccion){
            $costo_city = $direccion->getCiudad()->getCosto();
        }
        $total = $total + $costo_city;

        $tax = round($total*0.19/1.19,2);

        $total += $tax;


        return $this->render('PagosPayuBundle:Default:tarjetas.html.twig', compact("total"));
    }

    public function validatecard($number)
    {
        global $type;

        $cardtype = array(
            "visa"       => "/^4[0-9]{12}(?:[0-9]{3})?$/",
            "mastercard" => "/^5[1-5][0-9]{14}$/",
            "amex"       => "/^3[47][0-9]{13}$/",
            "discover"   => "/^6(?:011|5[0-9]{2})[0-9]{12}$/",
        );

        if (preg_match($cardtype['visa'],$number))
        {
            $type= "VISA";
            return 'VISA';

        }
        else if (preg_match($cardtype['mastercard'],$number))
        {
            $type= "MASTERCARD";
            return 'MASTERCARD';
        }
        else if (preg_match($cardtype['amex'],$number))
        {
            $type= "AMEX";
            return 'AMEX';

        }
        else if (preg_match($cardtype['discover'],$number))
        {
            $type= "DISCOVER";
            return 'DISCOVER';
        }
        else
        {
            return false;
        }
    }

    /**
     * @Route("/add_tarjeta", name="add_tarjeta")
     */
    public function add_tarjetaAction(Request $request)
    {
       $session = new Session();
        $token = new TokenPayu();

        $form = $this->createForm(TokenPayuType::class, $token);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($token);
            $em->flush();
            //exit(dump($form->get("card_number")->getData()));
            //exit(dump($this->validatecard($form->get("card_number")->getData())));
            $session->set("card_data", array(
                    "token_id" => $token->getId(),
                    "number" => $form->get("card_number")->getData(),
                    "type" => $this->validatecard($form->get("card_number")->getData()),
                    "date" => $form->get("ano")->getData() . "/" . $form->get("mes")->getData()
                )
            );
            //return new JsonResponse($session->get("card_data", array()));
            return $this->redirectToRoute("tokenize");
        }
        return $this->render('PagosPayuBundle:Default:add_tarjeta.html.twig', array("form" => $form->createView()));
    }

    /**
     * @Route("/untokenize/{id}", name="untokenize")
     */
    public function unTokenCardAction(Request $request, $id)
    {
        $user = $this->getUser();
        $token = $this->getDoctrine()->getRepository('PagosPayuBundle:TokenPayu')->find($id);
        //extract data from the post
        $url = 'https://api.payulatam.com/payments-api/4.0/service.cgi';
        $fields = array(
            'language' => "es",
            'command' => "REMOVE_TOKEN",
            'merchant' => [
                "apiLogin" => "tI7P45k3FawXHpG",
                "apiKey" => "23115978"
            ],
            'removeCreditCardToken' => [
                "payerId" => $user->getId(),
                "creditCardTokenId" => $token->getCreditCardTokenId()
            ]
        );
        $log = new Logger();
        $log->setRespuesta(json_encode($fields));
        $em = $this->getDoctrine()->getManager();
        $em->persist($log);
        $em->flush();
        $data_string = json_encode($fields);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data_string))
        );
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; MSIE 9.0; WIndows NT 9.0; en-US)');
        //curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        //curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        $result = curl_exec($ch);
        curl_close($ch);
        $xml = new \SimpleXMLElement($result);
        if($xml->code->__toString() == "SUCCESS"){
            $em = $this->getDoctrine()->getManager();
            $em->remove($token);
            $em->flush();
        }
        //dump($xml);
        //return new JsonResponse("1");
        return $this->redirectToRoute("tarjetas");
    }

    /**
     * @Route("/tokenize", name="tokenize")
     */
    public function tokenCardAction(Request $request)
    {
       $session = new Session();
        $card_data = $session->get("card_data", array());
        $user = $this->getUser();
        $token = $this->getDoctrine()->getRepository('PagosPayuBundle:TokenPayu')->find($card_data["token_id"]);
        //extract data from the post
//set POST variables
        /*
         * "apiLogin" => "tI7P45k3FawXHpG",
                "apiKey" => "PKjG37gY42S896r72X40hv7d9M"
         *
         */
        $url = 'https://api.payulatam.com/payments-api/4.0/service.cgi';
        $fields = array(
            'language' => "es",
            'command' => "CREATE_TOKEN",
            'merchant' => [
                "apiLogin" => "tI7P45k3FawXHpG",
                "apiKey" => "23115978"
            ],
            'creditCardToken' => [
                "payerId" => $user->getId(),
                "name" => $token->getName() . " " . $token->getLastname(),
                "identificationNumber" => $token->getDni(),
                "paymentMethod" => $card_data["type"],
                "number" => $card_data["number"],
                "expirationDate" => $card_data["date"]
            ]
        );
        $log = new Logger();
        $log->setRespuesta(json_encode($fields));
        $em = $this->getDoctrine()->getManager();
        $em->persist($log);
        $em->flush();
        $fields_string = "";
        $data_string = json_encode($fields);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data_string))
        );
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; MSIE 9.0; WIndows NT 9.0; en-US)');
        //curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        //curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        $result = curl_exec($ch);
        curl_close($ch);
        $serializer = SerializerBuilder::create()->build();
        $xml = new \SimpleXMLElement($result);
        if($xml->error){
            $session->set("error", $xml->error->__toString());
            return $this->redirectToRoute("aviso");
        }
        $user = $this->getUser();
        $token->setCode($xml->code->__toString());
        $token->setCreditCardTokenId($xml->creditCardToken->creditCardTokenId->__toString());
        $token->setName($xml->creditCardToken->name->__toString());
        $token->setPayerId($xml->creditCardToken->payerId->__toString());
        $token->setIdentificationNumber($xml->creditCardToken->identificationNumber->__toString());
        $token->setPaymentMethod($xml->creditCardToken->paymentMethod->__toString());
        $token->setMaskedNumber($xml->creditCardToken->maskedNumber->__toString());
        $token->setUsuario($user);
        $em = $this->getDoctrine()->getManager();
        $em->persist($token);
        $em->flush();
        $session->set("card_data", array());
        return $this->redirectToRoute("tarjetas");
    }

    /**
     * @Route("/pay/{id_token}", name="pay")
     */
    public function PayAction(Request $request, $id_token)
    {
        $em = $this->getDoctrine()->getManager();
       $session = new Session();
        $direccion_id = $session->get('direccion_id', null);
        $carrito = $session->get('carrito', array());
        if(count($carrito) == 0){
            return $this->redirectToRoute("aviso");
        }
        $cant = "";
        $direccion = null;
        if($direccion_id){
            $direccion = $this->getDoctrine()->getRepository('CarroiridianBundle:Envio')->find($direccion_id);
        }
        $total = 0;
        $compra = new Compra();
        $compra->setDireccion($direccion);
        $em->persist($compra);
        $em->flush();

        $log = new Logger();

        $descripcion_text = '';
        $descripcion = '<table>';

        $repo_p = $this->getDoctrine()->getRepository('CarroiridianBundle:Producto');
        $repo_t = $this->getDoctrine()->getRepository('CarroiridianBundle:Talla');
        $qi=$this->get("qi");
        foreach ($carrito as $id=>$tallas){
            foreach ($tallas as $id_talla=>$item){
                if($item['cantidad'] > 0){
                    $producto = $repo_p->find($id);
                    $talla = $repo_t->find($id_talla);
                    $subtotal = $item['cantidad'] * $qi->getPrecioUser($producto->getId(),$this->getUser());
                    $compraitem = new Compraitem();
                    $compraitem->setCantidad($item['cantidad']);
                    $compraitem->setProducto($producto);
                    $compraitem->setTalla($talla);
                    $compraitem->setCompra($compra);
                    $compraitem->setPrecio($subtotal);
                    $em->persist($compraitem);
                    $em->flush();

                    $total += $subtotal;
                    $descripcion .= '<tr>';
                    $descripcion .= '<td>'.$producto.'</td>';
                    $descripcion .= '<td>'.$talla.'</td>';
                    $descripcion .= '<td> $'.number_format($qi->getPrecioUser($producto->getId(),$this->getUser())).'</td>';
                    $descripcion .= '<td>'.$item['cantidad'].'</td>';
                    $descripcion .= '<td> $'.number_format($subtotal).'</td>';
                    $descripcion .= '</tr>';

                    $descripcion_text .= '|'.$producto.' - ';
                    $descripcion_text .= $talla.' - ';
                    $descripcion_text .= '$'.number_format($qi->getPrecioUser($producto->getId(),$this->getUser())).' - ';
                    $descripcion_text .= 'X'.$item['cantidad'].' - ';
                    $descripcion_text .= '$'.number_format($subtotal).' | ';
                }
            }
        }
        $descripcion .= '</table>';
        $compra->setDescripcion($descripcion);
        $costo_city = 0;
        if($direccion){
            $costo_city = $compra->getDireccion()->getCiudad()->getCosto();
        }
        $total = $total + $costo_city;
        if($direccion){
            $descripcion_text .= 'Costo envio, $' . number_format($direccion->getCiudad()->getCosto()) . ' |';
        }

        $tax = round($total*0.19/1.19,2);
        $cover = $this->getDoctrine()->getRepository('CarroiridianBundle:Compra')->findAll();
        $referenceCode = 'Test_BS_'.(count($cover) + 1);
        $total += $tax;
        $descripcion_text .= 'TOTAL: $'.number_format($total);
        $compra->setPrecio($total);
        $compra->setDescText($descripcion_text);
        $em->persist($compra);
        $em->flush();
        $user = $this->getUser();
        $token = $this->getDoctrine()->getRepository('PagosPayuBundle:TokenPayu')->find($id_token);

        //extract data from the post
        /*
         * Cuenta Id=12430
         *
         * */
        $payu = $this->getDoctrine()->getRepository('PagosPayuBundle:DatosPayu')->find($id_token);
        $sign = md5($payu->getApiKey() . "~" . $payu->getMerchantId() . "~" . $referenceCode . "~" . $total . "~" . $payu->getCurrency());
        $url = 'https://api.payulatam.com/payments-api/4.0/service.cgi';
        if($payu->getTest()){
            $url = 'https://sandbox.api.payulatam.com/payments-api/4.0/service.cgi';
        }

        $fields = array(
            'language' => "es",
            'command' => "SUBMIT_TRANSACTION",
            'merchant' => [
                "apiLogin" => $payu->getApiLogin(),
                "apiKey" => $payu->getApiKey()
            ],
            'transaction' => [
                "order" => [
                    "accountId" => $payu->getAccountId(),
                    "referenceCode" => $referenceCode,
                    "description" => $descripcion_text,
                    "language" => "es",
                    "signature" => $sign,
                    "notifyUrl" => "http://www.sed.com/confirmation",
                    "additionalValues" => [
                        "TX_VALUE" => [
                            "value" => $total,
                            "currency" => $payu->getCurrency()
                        ]
                    ],
                    "buyer"=> [
                        "merchantBuyerId" => (string)$user->getId(),
                        "fullName" => $user->getNombre() . " " . $user->getApellidos(),
                        "emailAddress" => $user->getEmail(),
                        "contactPhone" => $user->getTelefono(),
                        "dniNumber" => $user->getCedula(),
                        "shippingAddress" => [
                            "street1" => ($user->getDir() == null ? "" : $user->getDir()),
                            "street2" => ($user->getDir() == null ? "" : $user->getDir()),
                            "city" => "Bogota",
                            "state" => "Bogota DC",
                            "country" => "CO",
                            "postalCode" => "000000",
                            "phone" => $user->getTelefono()
                        ]
                    ],
                    "shippingAddress" => [
                        "street1" => ($user->getDir() == null ? "" : $user->getDir()),
                        "street2" => ($user->getDir() == null ? "" : $user->getDir()),
                        "city" => "Bogota",
                        "state" => "Bogota DC",
                        "country" => "CO",
                        "postalCode" => "000000",
                        "phone" => $user->getTelefono()
                    ]
                ],
                "payer" => [
                    "merchantPayerId" => (string)$token->getId(),
                    "fullName" => $token->getName() . " " . $token->getLastname(),
                    "emailAddress" => $token->getEmail(),
                    "contactPhone" => $token->getPhone(),
                    "dniNumber" => $token->getDni(),
                    "billingAddress" => [
                        "street1" => $token->getDir(),
                        "street2" => $token->getDir(),
                        "city" => $token->getCity(),
                        "state" => $token->getState(),
                        "country" => substr($token->getCountry(), 0, 2),
                        "postalCode" => $token->getPostalcode(),
                        "phone" => $token->getPhone()
                    ]
                ],
                "creditCardTokenId" => $token->getCreditCardTokenId(),
                "extraParameters" => [
                    "INSTALLMENTS_NUMBER" => 1
                ],
                "type" => "AUTHORIZATION_AND_CAPTURE",
                "paymentMethod" => $token->getPaymentMethod(),
                "paymentCountry" => "CO",
                "deviceSessionId" => uniqid(),
                "ipAddress" => "127.0.0.1",
                "cookie" => uniqid(),
                "userAgent" => "Mozilla/5.0 (Windows NT 5.1; rv:18.0) Gecko/20100101 Firefox/18.0"
            ],
            "test" => false
        );
        //exit(dump($fields));
        $session->set('carrito', array());
        try {
            $log->setRespuesta(json_encode($fields));
            $em->persist($log);
            $em->flush();
            $log = new Logger();
            $data_string = json_encode($fields);

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($data_string))
            );
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; MSIE 9.0; WIndows NT 9.0; en-US)');
            //curl_setopt($ch, CURLOPT_SSLVERSION,1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
            //curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);

            $result = curl_exec($ch);
            if (FALSE === $result)
                throw new \Exception(curl_error($ch), curl_errno($ch));

            curl_close($ch);

            $log->setRespuesta($result);
            $em->persist($log);
            $em->flush();

            $xml = new \SimpleXMLElement($result);
        } catch(\Exception $e) {
            $log->setRespuesta("Curl failed with error " .  $e->getCode() . " " . $e->getMessage());
            $em->persist($log);
            $em->flush();
            $session->set('error', "Error Code: " . $log->getId());
            return $this->redirectToRoute("aviso");
        }



        if($xml->code->__toString() == "SUCCESS"){
            $user = $this->getUser();
            $compra->setComprador($user);
            $compra->setCode($xml->code->__toString());
            $compra->setCantidad($cant);
            $compra->setTotal($total);
            $compra->setOrderId($xml->transactionResponse->orderId->__toString());
            $compra->setTransactionId($xml->transactionResponse->transactionId->__toString());
            $compra->setState($xml->transactionResponse->state->__toString());
            $compra->setPaymentNetworkResponseCode($xml->transactionResponse->paymentNetworkResponseCode->__toString());
            $compra->setTrazabilityCode($xml->transactionResponse->trazabilityCode->__toString());
            $compra->setAuthorizationCode($xml->transactionResponse->authorizationCode->__toString());
            $compra->setResponseCode($xml->transactionResponse->responseCode->__toString());
            $compra->setOperationDate($xml->transactionResponse->operationDate->__toString());
            $em->persist($compra);
            $em->flush();
            $sender  = $this->getDoctrine()->getRepository('AppBundle:Settings')->findBy(array('llave'=>"sender_mail"));
            $datos_envio  = $this->getDoctrine()->getRepository('AppBundle:Mailing')->findBy(["llave" => "compra"]);
            $email = $this->getUser()->getEmail();
            $this->SendMail($datos_envio[0]->getAsunto()->getEs(),$sender[0]->getValor(),$email,array("compraitems" => $compra->getCompraitems(), "datos" => $datos_envio[0], "transactionId" => $xml->transactionResponse->transactionId->__toString()), "UserIridianBundle:Default:email_compra.html.twig");
            return $this->redirectToRoute("aviso");
        }else{
            $session->set('error', "Error Code: " . $log->getId());
            return $this->redirectToRoute("aviso");
        }


    }


    /**
     * @Route("/aviso", name="aviso")
     */
    public function avisoAction(Request $request)
    {
       $session = new Session();
        $error = $session->get("error", null);
        $session->set("error", null);
        return $this->render('PagosPayuBundle:Default:aviso.html.twig', compact("error"));
    }
}
