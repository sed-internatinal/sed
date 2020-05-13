<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Imagen;
use CarroiridianBundle\Entity\Entrada;
use CarroiridianBundle\Entity\Inventario;
use JavierEguiluz\Bundle\EasyAdminBundle\Controller\AdminController as BaseAdminController;
use JavierEguiluz\Bundle\EasyAdminBundle\Event\EasyAdminEvents;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\Query\ResultSetMapping;
use Ddeboer\DataImport\Reader\ExcelReader;
use Ddeboer\DataImport\Writer\ExcelWriter;
use Ddeboer\DataImport\Workflow;
use Ddeboer\DataImport\Reader;
use Ddeboer\DataImport\Writer;
use Ddeboer\DataImport\Filter;
use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_Style_Border;
use PHPExcel_Style_Alignment;
use Symfony\Component\HttpFoundation\Response;
use TranslationBundle\Entity\Campo;
use TranslationBundle\Entity\Traduccion;
use TranslationBundle\Form\Type\CampoType;
use TranslationBundle\Form\Type\CampoTypeEdit;

class AdminController extends BaseAdminController
{

    /**
     * @Route("/inventarios_add/{producto_id}/{talla_id}/{cantidad}/{precio}", name="update_inventarios")
     */
    public function updateInventarioAction(Request $request,$producto_id,$talla_id,$cantidad,$precio)
    {
        $inventario = $this->getDoctrine()->getRepository('CarroiridianBundle:Inventario')
            ->findOneBy(array('producto'=>$producto_id,"talla"=>$talla_id));
        if($inventario == null){
            $inventario = new Inventario();
        }
        $valido_cant = true;
        $valido_precio = true;
        if(!is_int(intval($cantidad)))
            $valido_cant = false;
        if(!is_float(floatval($precio)))
            $valido_precio = false;
        if($valido_cant && $valido_precio){
            $producto = $this->getDoctrine()->getRepository('CarroiridianBundle:Producto')->find($producto_id);
            if(!$producto)
                $data = array('status'=>2);
            else{
                $talla = $this->getDoctrine()->getRepository('CarroiridianBundle:Talla')->find($talla_id);
                if(!$talla)
                    $data = array('status'=>3);
                else{
                    $inventario->setCantidad($cantidad);
                    $inventario->setPrecio($precio);
                    $inventario->setProducto($producto);
                    $inventario->setTalla($talla);
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($inventario);
                    $em->flush();
                    $data = array('status'=>1);
                }
            }
        }else{
            if(!$valido_cant && !$valido_precio){
                $data = array('status'=>4);
            }else if(!$valido_cant)
                $data = array('status'=>5);
            else
                $data = array('status'=>6);
        }
        return new JsonResponse($data);
    }

    /**
     * @Route("/inventarios_cantidad/{producto_id}/{talla_id}/{cantidad}", name="cantidad_inventarios")
     */
    public function cantidadInventarioAction(Request $request,$producto_id,$talla_id,$cantidad)
    {
        $inventario = $this->getDoctrine()->getRepository('CarroiridianBundle:Inventario')
            ->findOneBy(array('producto'=>$producto_id,"talla"=>$talla_id));
        if($inventario == null){
            $inventario = new Inventario();
        }
        $valido_cant = true;
        if(!is_numeric($cantidad))
            $valido_cant = false;
        if($valido_cant){
            $producto = $this->getDoctrine()->getRepository('CarroiridianBundle:Producto')->find($producto_id);
            if(!$producto)
                $data = array('status'=>2);
            else{
                $talla = $this->getDoctrine()->getRepository('CarroiridianBundle:Talla')->find($talla_id);
                if(!$talla)
                    $data = array('status'=>3);
                else{
                    $inventario->setCantidad($cantidad);
                    $inventario->setProducto($producto);
                    $inventario->setTalla($talla);
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($inventario);
                    $em->flush();
                    $data = array('status'=>1);
                }
            }
        }else{
            $data = array('status'=>4);
        }
        return new JsonResponse($data);
    }

    /**
     * @Route("/inventarios_precio/{producto_id}/{talla_id}/{precio}", name="precio_inventarios")
     */
    public function precioInventarioAction(Request $request,$producto_id,$talla_id,$precio)
    {
        $inventario = $this->getDoctrine()->getRepository('CarroiridianBundle:Inventario')
            ->findOneBy(array('producto'=>$producto_id,"talla"=>$talla_id));
        if($inventario == null){
            $inventario = new Inventario();
        }
        $valido_cant = true;
        if(!is_numeric($precio))
            $valido_cant = false;
        if($valido_cant){
            $producto = $this->getDoctrine()->getRepository('CarroiridianBundle:Producto')->find($producto_id);
            if(!$producto)
                $data = array('status'=>2);
            else{
                $talla = $this->getDoctrine()->getRepository('CarroiridianBundle:Talla')->find($talla_id);
                if(!$talla)
                    $data = array('status'=>3);
                else{
                    $inventario->setPrecio($precio);
                    $inventario->setProducto($producto);
                    $inventario->setTalla($talla);
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($inventario);
                    $em->flush();
                    $data = array('status'=>1);
                }
            }
        }else{
            $data = array('status'=>4);
        }
        return new JsonResponse($data);
    }

    /**
     * @Route("/inventarios", name="admin_inventarios")
     */
    public function inventarioAction(Request $request)
    {
        $qi = $this->get('qi');
        $precio = $qi->getSettingDB('precio_inventario');
        $tallas = $this->getDoctrine()->getRepository('CarroiridianBundle:Talla')->findAll();
        return $this->render('AppBundle::inventarios.html.twig', array(
            'inventarios' => $this->matriz(),
            'tallas' => $tallas,
            'precio' => $precio == 1
        ));
    }

    protected function editAction()
    {
        $this->dispatch(EasyAdminEvents::PRE_EDIT);

        $id = $this->request->query->get('id');
        $easyadmin = $this->request->attributes->get('easyadmin');
        $entity = $easyadmin['item'];

        if ($this->request->isXmlHttpRequest() && $property = $this->request->query->get('property')) {
            $newValue = 'true' === mb_strtolower($this->request->query->get('newValue'));
            $fieldsMetadata = $this->entity['list']['fields'];

            if (!isset($fieldsMetadata[$property]) || 'toggle' !== $fieldsMetadata[$property]['dataType']) {
                throw new \RuntimeException(sprintf('The type of the "%s" property is not "toggle".', $property));
            }

            $this->updateEntityProperty($entity, $property, $newValue);

            return new Response((string) $newValue);
        }

        $fields = $this->entity['edit']['fields'];

        $editForm = $this->executeDynamicMethod('create<EntityName>EditForm', array($entity, $fields));
        $deleteForm = $this->createDeleteForm($this->entity['name'], $id);

        /*FAVG PArche para idiomas administrables*/
        if($this->request->getMethod() == "GET") {
            $idiomas = $this->em->getRepository("TranslationBundle:Idioma")->findAll();
            foreach ($fields as $field => $meta) {
                if ($meta["type"] == "association" && $meta["targetEntity"] == "TranslationBundle\Entity\Campo") {
                    $campo= new Campo();
                    $campo= $entity->{"get".ucfirst($field)}();
                    //$campo=$campo->__load();
                    //die(dump($campo));
                    $editForm->add($field, CampoTypeEdit::class, ["idiomas" => $idiomas,'em'=>$this->em,'campo'=>$campo]);
                }
            }
        }else{
            foreach ($fields as $field => $meta) {
                if ($meta["type"] == "association" && $meta["targetEntity"] == "TranslationBundle\Entity\Campo") {
                    $className = $this->em->getClassMetadata(get_class($entity));
                    $name=$className->getName();
                    $namespace = $className->namespace;
                    //die(dump($this->request->request->get("prueba")));
                    $entityData=$this->request->request->get(strtolower(str_replace($namespace."\\","",$name)));
                    $data = $entityData[$field];
                    /*crear traduccion*/
                    if(is_array($data) && array_key_exists("hash",$data)) {
                        $hash = $data["hash"];
                        $campo = $this->em->getRepository("TranslationBundle:Campo")->findOneBy(array("hash" => $hash));

                        if (!$campo) {
                            $campo = new Campo();
                            $campo->setHash($hash);
                        }
                        foreach ($data as $label => $valor) {
                            if ($label != "hash" && array_key_exists($label, $data)) {
                                $idioma = $this->em->getRepository("TranslationBundle:Idioma")->find($label);
                                $traduccion = $this->em->getRepository("TranslationBundle:Traduccion")->findOneBy(array("idioma" => $idioma, "campo" => $campo));
                                if (!$traduccion)
                                    $traduccion = new Traduccion();
                                $traduccion->setIdioma($idioma);
                                $traduccion->setValor($valor);
                                $traduccion->setCampo($campo);

                                $this->em->persist($traduccion);

                            }
                            unset($data[$label]);
                        }

                        $this->em->flush();
                        $entityData[$field] = $campo->getId();
                        $this->request->request->set(strtolower(str_replace($namespace . "\\", "", $name)), $entityData);
                    }
                    /*crear traduccion*/

                }
            }
        }
        /*FAVG Parche para idiomas administrables*/

        $editForm->handleRequest($this->request);
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->dispatch(EasyAdminEvents::PRE_UPDATE, array('entity' => $entity));

            $this->executeDynamicMethod('preUpdate<EntityName>Entity', array($entity));
            $this->em->flush();

            $this->dispatch(EasyAdminEvents::POST_UPDATE, array('entity' => $entity));

            $refererUrl = $this->request->query->get('referer', '');

            return !empty($refererUrl)
                ? $this->redirect(urldecode($refererUrl))
                : $this->redirect($this->generateUrl('easyadmin', array('action' => 'list', 'entity' => $this->entity['name'])));
        }

        $this->dispatch(EasyAdminEvents::POST_EDIT);

        return $this->render($this->entity['templates']['edit'], array(
            'form' => $editForm->createView(),
            'entity_fields' => $fields,
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }


    protected function newAction()
    {
        $this->dispatch(EasyAdminEvents::PRE_NEW);

        $entity = $this->executeDynamicMethod('createNew<EntityName>Entity');

        $easyadmin = $this->request->attributes->get('easyadmin');
        $easyadmin['item'] = $entity;
        $this->request->attributes->set('easyadmin', $easyadmin);

        $fields = $this->entity['new']['fields'];

        $newForm = $this->executeDynamicMethod('create<EntityName>NewForm', array($entity, $fields));

        /*FAVG PArche para idiomas administrables*/
        if($this->request->getMethod() == "GET") {
            $idiomas = $this->em->getRepository("TranslationBundle:Idioma")->findAll();
            foreach ($fields as $field => $meta) {
                if ($meta["type"] == "association" && $meta["targetEntity"] == "TranslationBundle\Entity\Campo") {
                    $newForm->add($field, CampoType::class, ["idiomas" => $idiomas,'em'=>$this->em]);
                }
            }
        }else{
            foreach ($fields as $field => $meta) {
                if ($meta["type"] == "association" && $meta["targetEntity"] == "TranslationBundle\Entity\Campo") {
                    $className = $this->em->getClassMetadata(get_class($entity));
                    $name=$className->getName();
                    $namespace = $className->namespace;
                    //die(dump($this->request->request->get("prueba")));
                    $entityData=$this->request->request->get(strtolower(str_replace($namespace."\\","",$name)));
                    $data = $entityData[$field];
                    /*crear traduccion*/
                    if(is_array($data) && array_key_exists("hash",$data)) {
                        $hash = $data["hash"];
                        $campo = $this->em->getRepository("TranslationBundle:Campo")->findOneBy(array("hash" => $hash));

                        if (!$campo) {
                            $campo = new Campo();
                            $campo->setHash($hash);
                        }
                        foreach ($data as $label => $valor) {
                            if ($label != "hash" && array_key_exists($label, $data)) {
                                $idioma = $this->em->getRepository("TranslationBundle:Idioma")->find($label);
                                $traduccion = $this->em->getRepository("TranslationBundle:Traduccion")->findOneBy(array("idioma" => $idioma, "campo" => $campo));
                                if (!$traduccion)
                                    $traduccion = new Traduccion();
                                $traduccion->setIdioma($idioma);
                                $traduccion->setValor($valor);
                                $traduccion->setCampo($campo);

                                $this->em->persist($traduccion);

                            }
                            unset($data[$label]);
                        }

                        $this->em->flush();
                        $entityData[$field] = $campo->getId();
                        $this->request->request->set(strtolower(str_replace($namespace . "\\", "", $name)), $entityData);
                    }
                    /*crear traduccion*/

                }
            }
        }
        //die(dump($this->request));
        //die(dump($this->request));
        /*FAVG Parche para idiomas administrables*/
        $newForm->handleRequest($this->request);

        if ($newForm->isSubmitted() && $newForm->isValid()) {

            $this->dispatch(EasyAdminEvents::PRE_PERSIST, array('entity' => $entity));

            $this->executeDynamicMethod('prePersist<EntityName>Entity', array($entity));

            $this->em->persist($entity);
            $this->em->flush();

            $this->dispatch(EasyAdminEvents::POST_PERSIST, array('entity' => $entity));

            $refererUrl = $this->request->query->get('referer', '');

            return !empty($refererUrl)
                ? $this->redirect(urldecode($refererUrl))
                : $this->redirect($this->generateUrl('easyadmin', array('action' => 'list', 'entity' => $this->entity['name'])));
        }



        $this->dispatch(EasyAdminEvents::POST_NEW, array(
            'entity_fields' => $fields,
            'form' => $newForm,
            'entity' => $entity,
        ));

        return $this->render('@EasyAdmin/default/new_prueba.html.twig', array(
            'form' => $newForm->createView(),
            'entity_fields' => $fields,
            'entity' => $entity,

        ));
    }


    private function matriz(){
        $qi = $this->get('qi');
        $precio = $qi->getSettingDB('precio_inventario');
        $tallas = $this->getDoctrine()->getRepository('CarroiridianBundle:Talla')->findAll();
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('inventario_id', 'inventario_id', 'integer');
        $cad = '';
        $cad_in = '';
        foreach ($tallas as $talla){
            $id = $talla->getId();
            $rsm->addScalarResult('cantidad_'.$id,'cantidad_'.$id, 'integer');
            if($precio == 1)
                $rsm->addScalarResult('precio_'.$id, 'precio_'.$id, 'float');
            $cad .= ', SUM( cantidad_'.$id.' ) AS cantidad_'.$id;
            if($precio == 1)
                $cad .= ', SUM( precio_'.$id.' ) AS precio_'.$id;
            $cad_in .= ', IF( talla_id ='.$id.', cantidad, 0 ) AS cantidad_'.$id;
            if($precio == 1)
                $cad_in .= ', IF( talla_id ='.$id.', i.precio, 0 ) AS precio_'.$id;
        }

        $rsm->addScalarResult('id', 'producto_id', 'integer');
        $rsm->addScalarResult('sku', 'sku', 'string');
        $rsm->addScalarResult('nombre_es', 'nombre', 'string');
        $rsm->addScalarResult('talla_id', 'talla_id', 'integer');
        $inventarios = $this->getDoctrine()->getManager()->createNativeQuery('
        SELECT p.id, p.sku, p.nombre_es, talla_id, inventario_id'.$cad.'
            FROM (
            SELECT p.id, p.sku, p.nombre_es, t.id AS talla_id, i.id AS inventario_id
            '.$cad_in.'
            FROM producto p
            LEFT JOIN inventario i ON p.id = i.producto_id
            LEFT JOIN talla t ON t.id = i.talla_id
            WHERE p.id IS NOT NULL
            ) AS p
            GROUP BY p.id ', $rsm)->getArrayResult();
        return $inventarios;
    }

    /**
     * @Route("/inventarios-excel/{id}", name="excel_inventarios")
     */
    public function inventarioExcelAction(Request $request,$id)
    {
        $archivo = $this->getDoctrine()->getRepository('CarroiridianBundle:ArchivoInventario')->find($id);
        $em = $this->getDoctrine()->getManager();
        $qi = $this->get('qi');
        $precio = $qi->getSettingDB('precio_inventario');
        $archivo = $this->get('kernel')->getRootDir().'/../www/uploads/inventarios/'.$archivo->getArchivo();
        $file = new \SplFileObject($archivo);
        $reader = new ExcelReader($file);
        $tallas_ids = array();
        foreach ($reader as $fila=>$row){
            if($fila == 1){
                foreach ($row as $col){
                    if(is_numeric($col)){
                        array_push($tallas_ids,$col);
                    }
                }
            }
            if($fila > 2){
                $sku = $row[0];
                $producto = $this->getDoctrine()->getRepository('CarroiridianBundle:Producto')->findOneBy(array('sku'=>$sku));
                if($producto){
                    foreach ($tallas_ids as $i=>$talla_id){
                        $talla = $this->getDoctrine()->getRepository('CarroiridianBundle:Talla')->find($talla_id);
                        $inventario = $this->getDoctrine()->getRepository('CarroiridianBundle:Inventario')->findOneBy(array('producto'=>$producto->getId(),"talla"=>$talla_id));
                        if($precio == 1){
                            $precio_inv = $row[($i*2) + 2];
                            $cantidad = $row[($i*2) + 3];
                        }else{
                            //$precio_inv = $row[($i) + 2];
                            $cantidad = $row[($i) + 2];
                        }
                        if(!$inventario){
                            $inventario = new Inventario();
                        }
                        $inventario->setCantidad($cantidad);
                        if($precio == 1)
                            $inventario->setPrecio($precio_inv);
                        $inventario->setProducto($producto);
                        $inventario->setTalla($talla);
                        $em->persist($inventario);
                    }
                }
            }
        }
        $em->flush();

        return $this->render('AppBundle::importar.html.twig', array(
        ));
    }

    /**
     * @Route("/inventarios-excel-generador", name="excel_generador_inventarios")
     */
    public function inventarioExcelGeneradorAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $qi = $this->get('qi');
        $precio = $qi->getSettingDB('precio_inventario');
        $archivo = $this->get('kernel')->getRootDir().'/../html/uploads/inventarios/BasePrecios.xlsx';
        $objPHPExcel = new PHPExcel;
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Calibri');
        $objPHPExcel->getDefaultStyle()->getFont()->setSize(8);
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel2007");

        $objSheet = $objPHPExcel->getActiveSheet();
        $objSheet->setTitle('Listado de Precios');

        $tallas = $this->getDoctrine()->getRepository('CarroiridianBundle:Talla')->findAll();
        $tallas_tam = count($tallas);
        $objSheet->getStyle('A1:'.$this->getNameFromNumber($tallas_tam*2 + 1).'3')->getFont()->setBold(true)->setSize(12);
        $escala = 1;
        if($precio == 1)
            $escala = 2;
        foreach ($tallas as $i=>$talla){
            $ini = $this->getNameFromNumber($i*$escala+2);
            if($precio == 1){
                $fin = $this->getNameFromNumber($i*$escala+3);
                $objSheet->mergeCells($ini.'1:'.$fin.'1');
            }
            $objSheet->getCell($ini.'1')->setValue($talla->getNombreEs());
            $objSheet->getColumnDimension($ini)->setAutoSize(true);
        }
        foreach ($tallas as $i=>$talla){
            $ini = $this->getNameFromNumber($i*$escala+2);
            if($precio == 1) {
                $fin = $this->getNameFromNumber($i * $escala + 3);
                $objSheet->mergeCells($ini . '2:' . $fin . '2');
            }
            $objSheet->getCell($ini.'2')->setValue($talla->getId());
            $objSheet->getColumnDimension($ini)->setAutoSize(true);
        }

        $objSheet->getCell('A3')->setValue('SKU');
        $objSheet->getCell('B3')->setValue('PRODUCTO');

        foreach ($tallas as $i=>$talla){
            $ini = $this->getNameFromNumber($i*$escala+2);
            $fin = $this->getNameFromNumber($i*$escala+3);
            $objSheet->getCell($ini.'3')->setValue('CANTIDAD');
            if($precio == 1) {
                $objSheet->getCell($fin . '3')->setValue('PRECIO');
                $objSheet->getColumnDimension($fin)->setAutoSize(true);
            }
            $objSheet->getColumnDimension($ini)->setAutoSize(true);
        }
        foreach ($this->matriz() as $j=>$fila){
            $objSheet->getCell('A'.($j+4))->setValue($fila['sku']);
            $objSheet->getCell('B'.($j+4))->setValue($fila['nombre']);
            foreach ($tallas as $i=>$talla){
                $ini = $this->getNameFromNumber($i*$escala+2);
                $fin = $this->getNameFromNumber($i*$escala+3);
                $objSheet->getCell($ini.($j+4))->setValue($fila['cantidad_'.($i+1)]);
                if($precio == 1) {
                    $objSheet->getCell($fin . ($j + 4))->setValue($fila['precio_' . ($i + 1)]);
                    $objSheet->getColumnDimension($fin)->setAutoSize(true);
                }
                $objSheet->getColumnDimension($ini)->setAutoSize(true);
            }
        }

        $objSheet->getColumnDimension('A')->setAutoSize(true);
        $objSheet->getColumnDimension('B')->setAutoSize(true);

        $objWriter->save($archivo);

        $response = new Response();
        $filename = $archivo;

        $response->headers->set('Cache-Control', 'private');
        $response->headers->set('Content-type', mime_content_type($filename));
        $response->headers->set('Content-Disposition', 'attachment; filename="' . basename($filename) . '";');
        $response->headers->set('Content-length', filesize($filename));
        $response->sendHeaders();

        $response->setContent(file_get_contents($filename));
        return $response;
    }

    function getNameFromNumber($num) {
        $numeric = $num % 26;
        $letter = chr(65 + $numeric);
        $num2 = intval($num / 26);
        if ($num2 > 0) {
            return $this->getNameFromNumber($num2 - 1) . $letter;
        } else {
            return $letter;
        }
    }

    /**
     * @Route("/ordenargen/{tabla}/{campo}/{id}", name="ordenargen")
     */
    public function ordenargenAction(Request $request,$tabla,$campo,$id)
    {
        $em = $this->getDoctrine()->getManager();
        $objetos = $request->request->get('objeto');
        $imgs = $this->getDoctrine()
            ->getRepository($tabla)
            ->findBy(array($campo=>$id));
        $data = array('success'=>1);
        array_push($data,$objetos);
        foreach($imgs as $img){
            $orden = $objetos[$img->getId()];
            $img->setOrden($orden);
            $em->persist($img);
        }
        $em->flush();

        return new JsonResponse($data);
    }

    /**
     * The method that is executed when the user performs a 'new' action on an entity.
     *
     * @return RedirectResponse|Response
     */
    protected function newEntradaAction()
    {
        $this->dispatch(EasyAdminEvents::PRE_NEW);

        $entity = new Entrada();
        $entity->setCantidad(0);

        $easyadmin = $this->request->attributes->get('easyadmin');
        $easyadmin['item'] = $entity;
        $this->request->attributes->set('easyadmin', $easyadmin);

        $fields = $this->entity['new']['fields'];

        $newForm = $this->executeDynamicMethod('create<EntityName>NewForm', array($entity, $fields));

        $newForm->handleRequest($this->request);
        if ($newForm->isValid()) {
            $this->dispatch(EasyAdminEvents::PRE_PERSIST, array('entity' => $entity));

            $this->executeDynamicMethod('prePersist<EntityName>Entity', array($entity));

            $this->em->persist($entity);
            $this->em->flush();

            $this->dispatch(EasyAdminEvents::POST_PERSIST, array('entity' => $entity));

            $refererUrl = $this->request->query->get('referer', '');

            $inventario = $entity->getInventario();
            if($inventario || 1){
                $cantidad_inv = $inventario->getCantidad();
                $cantidad_ent = $entity->getCantidad();
                $inventario->setCantidad($cantidad_inv + $cantidad_ent);
                $this->em->persist($inventario);
                $this->em->flush();
            }

            return !empty($refererUrl)
                ? $this->redirect(urldecode($refererUrl))
                : $this->redirect($this->generateUrl('easyadmin', array('action' => 'list', 'entity' => $this->entity['name'])));
        }

        $this->dispatch(EasyAdminEvents::POST_NEW, array(
            'entity_fields' => $fields,
            'form' => $newForm,
            'entity' => $entity,
        ));

        return $this->render($this->entity['templates']['new'], array(
            'form' => $newForm->createView(),
            'entity_fields' => $fields,
            'entity' => $entity,
        ));
    }

    /**
     * The method that is executed when the user performs a 'show' action on an entity.
     *
     * @return Response
     */
    protected function showGaleriaAction()
    {
        $this->dispatch(EasyAdminEvents::PRE_SHOW);

        $id = $this->request->query->get('id');
        $easyadmin = $this->request->attributes->get('easyadmin');
        $entity = $easyadmin['item'];

        $fields = $this->entity['show']['fields'];
        $deleteForm = $this->createDeleteForm($this->entity['name'], $id);

        $this->dispatch(EasyAdminEvents::POST_SHOW, array(
            'deleteForm' => $deleteForm,
            'fields' => $fields,
            'entity' => $entity,
        ));

        return $this->render('@EasyAdmin/default/show_galeria.html.twig', array(
            'entity' => $entity,
            'fields' => $fields,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * The method that is executed when the user performs a 'show' action on an entity.
     *
     * @return Response
     */
    protected function showProductoAction()
    {
        $this->dispatch(EasyAdminEvents::PRE_SHOW);

        $id = $this->request->query->get('id');
        $easyadmin = $this->request->attributes->get('easyadmin');
        $entity = $easyadmin['item'];

        $fields = $this->entity['show']['fields'];
        $deleteForm = $this->createDeleteForm($this->entity['name'], $id);

        $this->dispatch(EasyAdminEvents::POST_SHOW, array(
            'deleteForm' => $deleteForm,
            'fields' => $fields,
            'entity' => $entity,
        ));

        return $this->render('@EasyAdmin/default/show_gen.html.twig', array(
            'entity' => $entity,
            'fields' => $fields,
            'delete_form' => $deleteForm->createView(),
            'tabla_doctrine' => 'CarroiridianBundle:Galeriaproducto',
            'tabla' => 'Galeriaproducto',
            'campo' => 'producto',
            'ruta' => $this->container->getParameter('app.path.productos')
        ));
    }

    /**
     * The method that is executed when the user performs a 'show' action on an entity.
     *
     * @return Response
     */
    protected function showPostAction()
    {
        $this->dispatch(EasyAdminEvents::PRE_SHOW);

        $id = $this->request->query->get('id');
        $easyadmin = $this->request->attributes->get('easyadmin');
        $entity = $easyadmin['item'];

        $fields = $this->entity['show']['fields'];
        $deleteForm = $this->createDeleteForm($this->entity['name'], $id);

        $this->dispatch(EasyAdminEvents::POST_SHOW, array(
            'deleteForm' => $deleteForm,
            'fields' => $fields,
            'entity' => $entity,
        ));

        return $this->render('@EasyAdmin/default/show_gen.html.twig', array(
            'entity' => $entity,
            'fields' => $fields,
            'delete_form' => $deleteForm->createView(),
            'tabla_doctrine' => 'BlogiridianBundle:GaleriaPost',
            'tabla' => 'GaleriaPost',
            'campo' => 'post',
            'ruta' => $this->container->getParameter('app.path.blogs')
        ));
    }

    /**
     * The method that is executed when the user performs a 'new' action on an entity.
     *
     * @return RedirectResponse|Response
     */
    protected function newImagenAction()
    {
        $this->dispatch(EasyAdminEvents::PRE_NEW);

        $entity = new Imagen();

        $easyadmin = $this->request->attributes->get('easyadmin');
        $easyadmin['item'] = $entity;
        $this->request->attributes->set('easyadmin', $easyadmin);

        $fields = $this->entity['new']['fields'];

        $newForm = $this->executeDynamicMethod('create<EntityName>NewForm', array($entity, $fields));

        $newForm->handleRequest($this->request);
        if ($newForm->isValid()) {
            $this->dispatch(EasyAdminEvents::PRE_PERSIST, array('entity' => $entity));

            //$this->prePersistEntity($entity);

            $this->executeDynamicMethod('prePersist<EntityName>Entity', array($entity));

            $this->em->persist($entity);
            $this->em->flush();

            $this->dispatch(EasyAdminEvents::POST_PERSIST, array('entity' => $entity));

            $refererUrl = $this->request->query->get('referer', '');

            if($refererUrl == 'modal')
                return $this->redirect($this->generateUrl('modalimage', array('id'=>$entity->getId(),'name'=>$entity->getImage())));
            else
                return $this->redirect($this->generateUrl('easyadmin', array('action' => 'list', 'entity' => $this->entity['name'])));
        }

        $this->dispatch(EasyAdminEvents::POST_NEW, array(
            'entity_fields' => $fields,
            'form' => $newForm,
            'entity' => $entity,
        ));

        return $this->render($this->entity['templates']['new'], array(
            'form' => $newForm->createView(),
            'entity_fields' => $fields,
            'entity' => $entity,
        ));
    }

    /**
     * The method that is executed when the user performs a 'edit' action on an entity.
     *
     * @return RedirectResponse|Response
     */
    protected function editImagenAction()
    {
        $this->dispatch(EasyAdminEvents::PRE_EDIT);

        $id = $this->request->query->get('id');
        $easyadmin = $this->request->attributes->get('easyadmin');
        $entity = $easyadmin['item'];

        if ($this->request->isXmlHttpRequest() && $property = $this->request->query->get('property')) {
            $newValue = 'true' === strtolower($this->request->query->get('newValue'));
            $fieldsMetadata = $this->entity['list']['fields'];

            if (!isset($fieldsMetadata[$property]) || 'toggle' != $fieldsMetadata[$property]['dataType']) {
                throw new \Exception(sprintf('The type of the "%s" property is not "toggle".', $property));
            }

            $this->updateEntityProperty($entity, $property, $newValue);

            return new Response((string) $newValue);
        }

        $fields = $this->entity['edit']['fields'];

        $editForm = $this->executeDynamicMethod('create<EntityName>EditForm', array($entity, $fields));
        $deleteForm = $this->createDeleteForm($this->entity['name'], $id);

        $editForm->handleRequest($this->request);
        if ($editForm->isValid()) {
            $this->dispatch(EasyAdminEvents::PRE_UPDATE, array('entity' => $entity));

            $this->executeDynamicMethod('preUpdate<EntityName>Entity', array($entity));
            $this->em->flush();

            $this->dispatch(EasyAdminEvents::POST_UPDATE, array('entity' => $entity));

            $refererUrl = $this->request->query->get('referer', '');


            if($refererUrl == 'modal')
                return $this->redirect($this->generateUrl('modalimage', array('id'=>$entity->getId(),'name'=>$entity->getImage())));
            else
                return !empty($refererUrl)
                    ? $this->redirect(urldecode($refererUrl))
                    : $this->redirect($this->generateUrl('easyadmin', array('action' => 'list', 'entity' => $this->entity['name'])));
        }

        $this->dispatch(EasyAdminEvents::POST_EDIT);

        return $this->render($this->entity['templates']['edit'], array(
            'form' => $editForm->createView(),
            'entity_fields' => $fields,
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    protected function instantiateNewEntity()
    {
        if ($this->entity['name'] === 'YourUserEntityNameInEasyAdmin') {
            return $this->get('fos_user.user_manager')->createUser();
        }
        return parent::instantiateNewEntity();
    }

    public function createNewUsersEntity()
    {
        return $this->container->get('fos_user.user_manager')->createUser();
    }

    public function prePersistUsersEntity(User $user)
    {
        $this->container->get('fos_user.user_manager')->updateUser($user, false);
    }

    public function preUpdateUsersEntity(User $user)
    {
        $this->container->get('fos_user.user_manager')->updateUser($user, false);
    }

    protected function executeDynamicMethod($methodNamePattern, array $arguments = array())
    {
        $methodName = str_replace('<EntityName>', $this->entity['name'], $methodNamePattern);
        if (!is_callable(array($this, $methodName))) {
            $methodName = str_replace('<EntityName>', '', $methodNamePattern);
        }

        return call_user_func_array(array($this, $methodName), $arguments);
    }


    protected function updateEntityProperty($entity, $property, $value)
    {
        $entityConfig = $this->entity;

        // the method_exists() check is needed because Symfony 2.3 doesn't have isWritable() method
        if (method_exists($this->get('property_accessor'), 'isWritable') && !$this->get('property_accessor')->isWritable($entity, $property)) {
            throw new \Exception(sprintf('The "%s" property of the "%s" entity is not writable.', $property, $entityConfig['name']));
        }

        $this->dispatch(EasyAdminEvents::PRE_UPDATE, array('entity' => $entity, 'newValue' => $value));

        $this->get('property_accessor')->setValue($entity, $property, $value);

        $this->em->persist($entity);
        $this->em->flush();
        $this->dispatch(EasyAdminEvents::POST_UPDATE, array('entity' => $entity, 'newValue' => $value));

        $this->dispatch(EasyAdminEvents::POST_EDIT);
    }


}