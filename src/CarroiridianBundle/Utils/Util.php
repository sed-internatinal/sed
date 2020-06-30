<?php

namespace CarroiridianBundle\Utils;

use Symfony\Component\HttpFoundation\Session\Session;

class Util
{
    public static $TCC_URL_LIQUIDACION = 'http://clientes.tcc.com.co/servicios/liquidacionacuerdos.asmx?wsdl';
    //public static $TCC_URL_REMESAS = 'http://preclientes.tcc.com.co:1080/api/clientes/remesasws?wsdl'; // TEST
    public static $TCC_URL_REMESAS = 'http://clientes.tcc.com.co:4080/api/clientes/remesasws?wsdl';

    public static function calcularUnidades($carrito, $productos, $settings, $user)
    {
        $total_kilo_volumen = 0.0;
        $total_kilo = 0.0;
        $valor_total = 0.0;
        $unidades_list = array();

        $iva = 0.0;
        foreach ($productos as &$producto) {
            if ($producto->getIva() > $iva){
                $iva = $producto->getIva();
            }
        }
        $iva = $iva / 100;
        foreach ($productos as &$producto) {
            $catidad = $carrito[$producto->getId()][1]['cantidad'];
            $kilo_volumen = $producto->getKiloVolumen();
            if ($kilo_volumen >= (float)$settings['TCC_UNIDAD_LOGISTICA']) {
                $valor_total += $producto->getPriceByUser($user->getEsEmpresa()) * $catidad;
                for ($x = 0; $x < (int)$catidad; $x++) {
                    array_push($unidades_list, [
                        'tipoempaque' => $settings['TCC_TIPO_EMPAQUE'],
                        'numerounidades' => 1,
                        'pesoreal' => $producto->getPeso(),
                        'pesovolumen' => $kilo_volumen,
                    ]);
                }
            } else {
                $total_kilo_volumen += $kilo_volumen * $catidad;
                $total_kilo += $producto->getPeso() * $catidad;
                $valor_total += $producto->getPriceByUser($user->getEsEmpresa()) * $catidad;
            }
        }
        $unidades = $total_kilo_volumen / (float)$settings['TCC_UNIDAD_LOGISTICA'];
        $numero_unidades = ceil($unidades);

        if ($total_kilo_volumen > 0.0) {
            array_push($unidades_list, [
                'tipoempaque' => $settings['TCC_TIPO_EMPAQUE'],
                'numerounidades' => $numero_unidades,
                'pesoreal' => $total_kilo,
                'pesovolumen' => $total_kilo_volumen,
            ]);
        }

        return [
            'unidades' => $unidades,
            'total_kilo_volumen' => $total_kilo_volumen,
            'numero_unidades' => $numero_unidades,
            'valor_total' => $valor_total,
            'unidades_list' => $unidades_list,
            'IVA' => $iva,
        ];
    }

    public static function calcularUnidadesCompra($compra, $settings)
    {
        $unidades_list = array();
        $total_kilo_volumen = 0.0;
        $valor_total = 0.0;
        $peso_total = 0.0;
        $items = $compra->getCompraitems();
        foreach ($items as &$item) {
            $catidad = $item->getCantidad();
            $kilo_volumen = $item->getProducto()->getKiloVolumen();
            if ($kilo_volumen >= (float)$settings['TCC_UNIDAD_LOGISTICA']) {
                for ($x = 0; $x < (int)$catidad; $x++) {
                    array_push($unidades_list, [
                        'tipounidad' => $settings['TCC_TIPO_UNIDAD'],
                        'tipoempaque' => $settings['TCC_TIPO_EMPAQUE'],
                        'claseempaque' => $settings['TCC_CLASE_EMPAQUE'],
                        'dicecontener' => '',
                        'kilosreales' => $item->getProducto()->getPeso(),
                        'pesovolumen' => $kilo_volumen,
                        'valormercancia' => $item->getPrecio() / $catidad,
                        'codigobarras' => '',
                        'numerobolsa' => '',
                        'referencias' => '',
                        'unidadesinternas' => '',
                    ]);
                }
            } else {
                $total_kilo_volumen += $kilo_volumen * $catidad;
                $peso_total += $item->getProducto()->getPeso() * $catidad;
                $valor_total += $item->getPrecio();
            }
        }
        $unidades = $total_kilo_volumen / (float)$settings['TCC_UNIDAD_LOGISTICA'];
        $numero_unidades = ceil($unidades);
        if ($total_kilo_volumen > 0.0) {
            for ($x = 0; $x < (int)$numero_unidades; $x++) {
                array_push($unidades_list, [
                    'tipounidad' => $settings['TCC_TIPO_UNIDAD'],
                    'tipoempaque' => $settings['TCC_TIPO_EMPAQUE'],
                    'claseempaque' => $settings['TCC_CLASE_EMPAQUE'],
                    'dicecontener' => '',
                    'kilosreales' => $peso_total / $numero_unidades,
                    'pesovolumen' => $total_kilo_volumen / $numero_unidades,
                    'valormercancia' => $valor_total / $numero_unidades,
                    'codigobarras' => '',
                    'numerobolsa' => '',
                    'referencias' => '',
                    'unidadesinternas' => '',
                ]);
            }
        }
        return [
            'unidades' => $unidades,
            'total_kilo_volumen' => '',
            'numero_unidades' => $numero_unidades,
            'valor_total' => $valor_total,
            'peso_total' => '',
            'unidades_list' => $unidades_list,
        ];
    }

    public static function getSettings($doctrine, $llaves)
    {
        $settings_orm = $doctrine->getRepository('AppBundle:Settings');
        $list_llaves = $settings_orm->findBy(['llave' => $llaves]);
        $new_list = [];
        foreach ($list_llaves as &$item) {
            $new_list[$item->getLlave()] = $item->getValor();
        }
        return $new_list;
    }

    public static function getLastLog($doctrine, $compra)
    {
        $tcc_orm = $doctrine->getRepository('CarroiridianBundle:TCCLog');
        $tcc_log = $tcc_orm->findOneBy(
            ['compra' => $compra->getId()],
            ['id' => 'DESC']
        );
        return $tcc_log;
    }

    public static function getCostoEnvio($carrito, $ciudad, $user, $doctrine, $compra=null)
    {
        $settings = Util::getSettings($doctrine, [
            'TCC_CLAVE',
            'TCC_CIUDAD_ORIGEN_DANE',
            'TCC_TIPO_ENVIO',
            'TCC_TIPO_EMPAQUE',
            'TCC_CUENTA',
            'TCC_ID_UNIDAD_ESTRATEGICA_NEGOCIO',
            'TCC_UNIDAD_LOGISTICA',
        ]);

        if ($ciudad->getCodigoDane() == $settings['TCC_CIUDAD_ORIGEN_DANE'] && $user->getEsEmpresa()){
            $session = new Session();
            $session->set('consulta_request_xml', '');
            $session->set('consulta_response_xml', '');
            return [
                'costo_envio' => 0,
                'costo_envio_mas_iva' => 0,
                'valor_iva' => 0,
            ];
        }

        $client = new \SoapClient(Util::$TCC_URL_LIQUIDACION, array("trace" => 1));

        $producto_orm = $doctrine->getRepository('CarroiridianBundle:Producto');

        $productos = $producto_orm->findBy(['id' => array_keys($carrito), 'flete' => true]);

        if (count($productos) == 0) {
            return [
                'costo_envio' => 0.0,
                'costo_envio_mas_iva' => 0.0,
                'valor_iva' => 0.0,
            ];
        }

        $data_cal = Util::calcularUnidades($carrito, $productos, $settings, $user);

        $parameters = [
            'consultarliquidacion2' => [
                'Clave' => $settings['TCC_CLAVE'],
                'Liquidacion' => [
                    'tipoenvio' => $settings['TCC_TIPO_ENVIO'],
                    'idciudadorigen' => $settings['TCC_CIUDAD_ORIGEN_DANE'],
                    'idciudaddestino' => $ciudad->getCodigoDane(),
                    'valormercancia' => $data_cal['valor_total'],
                    'boomerang' => 0,
                    'cuenta' => $settings['TCC_CUENTA'],
                    'fecharemesa' => date("y-m-d"),
                    'idunidadestrategicanegocio' => $settings['TCC_ID_UNIDAD_ESTRATEGICA_NEGOCIO'],
                    'unidades' => $data_cal['unidades_list'],
                ],
            ]
        ];
        $response = $client->__soapCall('consultarliquidacion2', $parameters);

        $xml_request =  $client->__getLastRequest();
        $xml_response =  $client->__getLastResponse();

        if ($compra){
            Util::SaveLogConsulta($doctrine, $xml_response, $xml_request, $compra);
        }

        $costo_envio_tcc = (float) $response->consultarliquidacion2Result->total->totaldespacho;

        $costo_envio_tcc_mas_iva = $costo_envio_tcc * (1 + $data_cal['IVA']);

        return [
            'costo_envio' => $costo_envio_tcc,
            'costo_envio_mas_iva' => $costo_envio_tcc_mas_iva,
            'valor_iva' => $costo_envio_tcc_mas_iva - $costo_envio_tcc,
            'consulta_request_xml' => $client->__getLastRequest(),
            'consulta_response_xml' => $client->__getLastResponse(),
        ];
    }

    public static function grabarEnvio($doctrine, $compra)
    {
        $client = new \SoapClient(Util::$TCC_URL_REMESAS, array("trace" => 1));

        $settings = Util::getSettings($doctrine, [
            'TCC_CLAVE',
            'TCC_UNIDAD_NEGOCIO',
            'TCC_CUENTA',
            'TCC_TIPO_IDENTIFICACION_REMITENTE',
            'TCC_IDENTIFICACION_REMITENTE',
            'TCC_PRIMER_NOMBRE_REMITENTE',
            'TCC_RAZON_SOCIAL_REMITENTE',
            'TCC_NATURALEZA_REMITENTE',
            'TCC_DIRECCION_REMITENTE',
            'TCC_CONTACTO_REMITENTE',
            'TCC_EMAIL_REMITENTE',
            'TCC_TELEFONO_REMITENTE',
            'TCC_CIUDAD_ORIGEN_DANE',
            'TCC_TIPO_UNIDAD',
            'TCC_TIPO_EMPAQUE',
            'TCC_CLASE_EMPAQUE',
            'TCC_TIPO_DOCUMENTO',
            'TCC_NUMERO_DOCUMENTO',
            'TCC_UNIDAD_LOGISTICA',
        ]);

        $user = $compra->getDireccion()->getUser();

        if ($compra->getDireccion()->getCiudad()->getCodigoDane() == $settings['TCC_CIUDAD_ORIGEN_DANE'] && $user->getEsEmpresa()){
            return Util::SaveLog($doctrine, 'Bogota', 'Bogota', 'Bogota', $compra);
        }

        $hoy_date = new \DateTime(date("Y-m-d h:i:s"));
        $manana_date = new \DateTime(date("Y-m-d h:i:s", strtotime('+1 day')));
        $data_cal = Util::calcularUnidadesCompra($compra, $settings);

         $settings['TCC_CLAVE'] = 'CLIENTETCC608W3A61CJ'; // uncomment this line for testing
         $settings['TCC_CUENTA'] = '1573800'; // uncomment this line for testing

        $parameters = [
            'grabardespacho7' => [
                'despacho' => [
                    'clave' => $settings['TCC_CLAVE'],
                    'numerorelacion' => '',
                    'fechahorarelacion' => '',

                    'solicitudrecogida' => [
                        'numero' => '',
                        'fecha' => $manana_date->format('Y-m-d'),
                        'ventanainicio' => $manana_date->format('Y-m-d') . 'T10:00:00',
                        'ventanafin' => $manana_date->format('Y-m-d') . 'T12:00:00',
                    ],
                    // REMITENTE
                    'unidadnegocio' => $settings['TCC_UNIDAD_NEGOCIO'],
                    'numeroremesa' => '',
                    'fechadespacho' => $hoy_date->format('Y-m-d'),
                    'cuentaremitente' => $settings['TCC_CUENTA'],
                    'tipoidentificacionremitente' => $settings['TCC_TIPO_IDENTIFICACION_REMITENTE'],
                    'identificacionremitente' => $settings['TCC_IDENTIFICACION_REMITENTE'],
                    'sederemitente' => '',
                    'primernombreremitente' => $settings['TCC_PRIMER_NOMBRE_REMITENTE'],
                    'segundonombreremitente' => '',
                    'primerapellidoremitente' => '',
                    'segundoapellidoremitente' => '',
                    'razonsocialremitente' => $settings['TCC_RAZON_SOCIAL_REMITENTE'],
                    'naturalezaremitente' => $settings['TCC_NATURALEZA_REMITENTE'],
                    'direccionremitente' => $settings['TCC_DIRECCION_REMITENTE'],
                    'contactoremitente' => $settings['TCC_CONTACTO_REMITENTE'],
                    'emailremitente' => $settings['TCC_EMAIL_REMITENTE'],
                    'telefonoremitente' => $settings['TCC_TELEFONO_REMITENTE'],
                    'ciudadorigen' => $settings['TCC_CIUDAD_ORIGEN_DANE'],
                    // DESTINATARIO
                    'tipoidentificaciondestinatario' => $user->getTipoDocumento(),
                    'identificaciondestinatario' => $user->getDocumento(),
                    'sededestinatario' => '',
                    'primernombredestinatario' => $user->getNombre(),
                    'segundonombredestinatario' => '',
                    'primerapellidodestinatario' => $user->getApellidos(),
                    'segundoapellidodestinatario' => '',
                    'razonsocialdestinatario' => $user->getNombre() . ' ' . $user->getApellidos(),
                    'naturalezadestinatario' => 'N', // new field
                    'direcciondestinatario' => $compra->getDireccion()->getDireccion() . ', ' . $compra->getDireccion()->getAdicionales(),
                    'contactodestinatario' => '',
                    'emaildestinatario' => $user->getEmail(),
                    'telefonodestinatario' => $user->getTelefono(),
                    'ciudaddestinatario' => $compra->getDireccion()->getCiudad()->getCodigoDane(),
                    'barriodestinatario' => '',
                    // CONTENIDO
                    'totalpeso' => $data_cal['peso_total'],
                    'totalpesovolumen' => '', // $data_cal['total_kilo_volumen'], TODO: calculation for this value
                    'totalvalormercancia' => '', // $compra->getPrecio() When this value is use the TCC api raise a error
                    'formapago' => '',
                    'observaciones' => '',
                    'llevabodega' => '',
                    'recogebodega' => '',
                    'centrocostos' => '',
                    'totalvalorproducto' => '', //$compra->getPrecio(), // When this value is use the TCC api raise a error
                    'tiposervicio' => '',
                    // UNIDADES
                    'unidad' => $data_cal['unidades_list'],
                    'documentoreferencia' => [
                        'tipodocumento' => $settings['TCC_TIPO_DOCUMENTO'],
                        'numerodocumento' => $settings['TCC_NUMERO_DOCUMENTO'],
                        'fechadocumento' => $hoy_date->format('Y-m-d'),
                    ],
                    'generardocumentos' => 'TRUE',
                    'numeroreferenciacliente' => '',
                    'fuente' => ''
                ]
            ]
        ];
        try {
            $response = $client->__soapCall('grabardespacho7', $parameters);
        } catch (\Exception $e) {
            $response = null;
        }
        $last_request = $client->__getLastRequest();
        $last_response = $client->__getLastResponse();
        return Util::SaveLog($doctrine, $last_response, $last_request, $response, $compra);
    }

    public static function SaveLog($doctrine, $xml_response, $xml_request, $response, $compra)
    {
        $em  = $doctrine->getManager();

        $tcc_log = Util::getLastLog($doctrine, $compra);
        if (!$tcc_log) {
            $tcc_log = new \CarroiridianBundle\Entity\TCCLog();
            $tcc_log->setCompra($compra);
        }

        $tcc_log->setXmlRequest($xml_request);
        $tcc_log->setXmlResponse($xml_response);

        try {
            $tcc_log->setRemesa($response->remesa);
        } catch (\Exception $e) {
        }

        try {
            $tcc_log->setNumeroRecogida($response->numerorecogida);
        } catch (\Exception $e) {
        }

        try {
            $tcc_log->setUrlRelacionEnvio($response->urlrelacionenvio);
        } catch (\Exception $e) {
        }

        try {
            $tcc_log->setUrlRemesa($response->urlremesa);
        } catch (\Exception $e) {
        }

        try {
            $tcc_log->setUrlRotulos($response->urlrotulos);
        } catch (\Exception $e) {
        }

        try {
            $tcc_log->setRespuesta($response->respuesta);
        } catch (\Exception $e) {
        }

        try {
            $tcc_log->setMensaje($response->mensaje);
        } catch (\Exception $e) {
        }

        $em->persist($tcc_log);
        $em->flush();
        return $tcc_log;
    }

    public static function SaveLogConsulta($doctrine, $xml_response, $xml_request, $compra)
    {
        $em = $doctrine->getManager();
        $tcc_log = Util::getLastLog($doctrine, $compra);
        if (!$tcc_log) {
            $tcc_log = new \CarroiridianBundle\Entity\TCCLog();
            $tcc_log->setCompra($compra);
        }

        $tcc_log->setXmlRequestConsulta($xml_request);
        $tcc_log->setXmlResponseConsulta($xml_response);

        $em->persist($tcc_log);
        $em->flush();
        return $tcc_log;
    }
}