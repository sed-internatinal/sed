<?php

namespace ReportesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\Query\ResultSetMapping;
use CarroiridianBundle\Entity\Factura;



class DefaultController extends Controller
{
    /**
     * @Route("/reporte-total-vendido", name="reporte_total_vendido")
     */
    public function reporteTotalAction(Request $request)
    {
        $reporte = $this->reporteNativo('SELECT SUM(c.precio) as precio, " " as fecha FROM compra c WHERE c.eatadocarrito_id = 2  order by c.created_at;');
        return $this->render('ReportesBundle:Default:reporte.html.twig', array(
            'reporte' => $reporte,
            'titulo'=>'Total vendido'
        ));
    }

    /**
     * @Route("/reporte-completo", name="reporte_completo")
     */
    public function reporteCompletoAction(Request $request)
    {
        $reporte = $this->reporteNativo('SELECT c.precio, c.created_at as fecha FROM compra c  WHERE c.eatadocarrito_id = 2 order by c.created_at;');
        return $this->render('ReportesBundle:Default:reporte.html.twig', array(
            'reporte' => $reporte,
            'titulo'=>'Reporte completo'
        ));
    }

    /**
     * @Route("/reporte-diario", name="reporte_diario")
     */
    public function reporteDirarioAction(Request $request)
    {
        $start = $request->query->get('start');
        $end = $request->query->get('end');
        $rango = '';
        if(strlen($start) > 4 && strlen($end) > 4)
            $rango = 'and c.created_at BETWEEN "'.$start.' 00:00:00" AND "'.$end.' 23:59:59"';
        $reporte = $this->reporteNativo('SELECT SUM(c.precio) as precio, concat(YEAR(c.created_at),"-",MONTH(c.created_at),"-", DAY(c.created_at)) as fecha FROM compra c  WHERE c.eatadocarrito_id = 2  '.$rango.' group by concat(YEAR(c.created_at),MONTH(c.created_at), DAY(c.created_at)) order by c.created_at');
        return $this->render('ReportesBundle:Default:reporte.html.twig', array(
            'reporte' => $reporte,
            'titulo'=>'Reporte diario'
        ));
    }

    /**
     * @Route("/reporte-mensual", name="reporte_mensual")
     */
    public function reporteMensualAction(Request $request)
    {
        $start = $request->query->get('start');
        $end = $request->query->get('end');
        $rango = '';
        if(strlen($start) > 4 && strlen($end) > 4)
            $rango = 'and c.created_at BETWEEN "'.$start.' 00:00:00" AND "'.$end.' 23:59:59"';
        $reporte = $this->reporteNativo('SELECT SUM(c.precio) as precio, concat(YEAR(c.created_at),"-",MONTH(c.created_at)) as fecha FROM compra c  WHERE c.eatadocarrito_id = 2  '.$rango.' group by concat(YEAR(c.created_at),MONTH(c.created_at)) order by c.created_at');
        return $this->render('ReportesBundle:Default:reporte.html.twig', array(
            'reporte' => $reporte,
            'titulo'=>'Reporte mensual'
        ));
    }

    /**
     * @Route("/reporte-producto", name="reporte_producto")
     */
    public function reporteProductcAction(Request $request)
    {
        $start = $request->query->get('start');
        $end = $request->query->get('end');
        $rango = '';
        if(strlen($start) > 4 && strlen($end) > 4)
            $rango = 'and c.created_at BETWEEN "'.$start.' 00:00:00" AND "'.$end.' 23:59:59"';
        $cols = array('sku','nombre','cantidad');
        $reporte = $this->reporteNativoGen($cols,'select p.sku, p.nombre_es as nombre, count(p.id*ci.cantidad) as cantidad from producto p left join compraitem ci on ci.producto_id = p.id left join compra c on c.id = ci.compra_id WHERE c.eatadocarrito_id = 2  '.$rango.' group by p.id order by cantidad desc');
        return $this->render('ReportesBundle:Default:reporte.html.twig', array(
            'reporte' => $reporte,
            'titulo'=>'Reporte por producto',
            'cols'=>$cols
        ));
    }

    /**
     * @Route("/reporte-categoria", name="reporte_categoria")
     */
    public function reporteCategoriacAction(Request $request)
    {
        $start = $request->query->get('start');
        $end = $request->query->get('end');
        $rango = '';
        if(strlen($start) > 4 && strlen($end) > 4)
            $rango = 'and c.created_at BETWEEN "'.$start.' 00:00:00" AND "'.$end.' 23:59:59"';
        $cols = array('id','nombre','cantidad');
        $reporte = $this->reporteNativoGen($cols,'select ca.id, ca.nombre_es as nombre, count(ca.id*ci.cantidad) as cantidad from categoria ca left join producto p on p.categoria_id = ca.id left join compraitem ci on ci.producto_id = p.id left join compra c on c.id = ci.compra_id WHERE c.eatadocarrito_id = 2 '.$rango.' group by ca.id order by cantidad desc');
        return $this->render('ReportesBundle:Default:reporte.html.twig', array(
            'reporte' => $reporte,
            'titulo'=>'Reporte por categoría',
            'cols'=>$cols
        ));
    }

    /**
     * @Route("/reporte-dia", name="reporte_dia")
     */
    public function reporteDiaAction(Request $request)
    {
        $start = $request->query->get('start');
        $end = $request->query->get('end');
        $rango = '';
        if(strlen($start) > 4 && strlen($end) > 4)
            $rango = 'and c.created_at BETWEEN "'.$start.' 00:00:00" AND "'.$end.' 23:59:59"';
        $cols = array('dia','cantidad','fecha','precio');
        $reporte = $this->reporteNativoGen($cols,'SELECT c.precio, c.created_at as fecha, if(weekday(c.created_at) = 0, \'Lunes\', 	if(weekday(c.created_at) = 1,\'Martes\', 		if(weekday(c.created_at) = 2,\'Miercoles\', 			if(weekday(c.created_at) = 3,\'Jueves\', 				if(weekday(c.created_at) = 4,\'Viernes\', 					if(weekday(c.created_at) = 5,\'Sabado\',\'Domingo\')                 )             ) 		)     ) ) as dia, weekday(c.created_at) as dia_num , count(c.id) as cantidad FROM compra c  WHERE c.eatadocarrito_id = 2 '.$rango.' group by dia order by dia_num asc');
        return $this->render('ReportesBundle:Default:reporte.html.twig', array(
            'reporte' => $reporte,
            'titulo'=>'Reporte por día',
            'cols'=>$cols
        ));
    }

    /**
     * @Route("/reporte-estados", name="reporte_estado")
     */
    public function reporteEstadoAction(Request $request)
    {
        $start = $request->query->get('start');
        $end = $request->query->get('end');
        $rango = '';
        if(strlen($start) > 4 && strlen($end) > 4)
            $rango = 'and c.created_at BETWEEN "'.$start.' 00:00:00" AND "'.$end.' 23:59:59"';
        $cols = array('nombre','cantidad');
        $reporte = $this->reporteNativoGen($cols,'SELECT count(c.id) as cantidad, e.nombre as nombre from compra c  
          left join estado_carrito e on c.eatadocarrito_id = e.id 
          where 
            e.id is not null 
            and e.id != 5 
            '.$rango.'
        group by c.eatadocarrito_id 
        order by c.id asc');
        return $this->render('ReportesBundle:Default:reporte_producto.html.twig', array(
            'reporte' => $reporte,
            'titulo'=>'Reporte por estados',
            'cols'=>$cols
        ));
    }

    private function reporteNativo($cadena){
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('precio', 'precio', 'string');
        $rsm->addScalarResult('fecha', 'fecha', 'string');
        $reporte = $this->getDoctrine()->getManager()->createNativeQuery($cadena, $rsm)->getArrayResult();
        return $reporte;
    }

    private function reporteNativoGen($cols,$cadena){
        $rsm = new ResultSetMapping();
        foreach ($cols as $col){
            $rsm->addScalarResult($col, $col, 'string');
        }
        $reporte = $this->getDoctrine()->getManager()->createNativeQuery($cadena, $rsm)->getArrayResult();
        return $reporte;
    }
}
