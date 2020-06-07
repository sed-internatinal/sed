<?php
/**
 * Created by PhpStorm.
 * User: Iridian 1
 * Date: 1/02/2016
 * Time: 12:29 PM.
 */

namespace CarroiridianBundle\Service;

use CarroiridianBundle\Entity\Producto;
use CarroiridianBundle\Entity\Talla;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Symfony\Component\HttpFoundation\Session\Session;

class CI
{
    protected $em;
    protected $request_stack;
    protected $locale;
    protected $container;

    protected $textos = null;
    protected $textosDB = null;
    protected $textosBigDB = null;

    protected $settings = null;
    protected $imagenes = null;
    protected $productosId = null;
    protected $tallasId = null;

    public function __construct(EntityManager $em, RequestStack $request_stack, Container $container)
    {
        $this->em = $em;
        $this->request_stack = $request_stack;
        $this->container = $container;
        //$this->locale = $request_stack->getCurrentRequest()->getLocale();
    }

    public function qs($clase)
    {
        return $this->em->getRepository('AppBundle:Texto')->findAll();
    }

    private function getResultsId($entidad)
    {
        $qb = $this->em->createQueryBuilder()
            ->select('s')
            ->from($entidad, 's', 's.id')
            ->getQuery()
            ->useQueryCache(true)
            ->useResultCache(true)
            ->getArrayResult();

        return $qb;
    }

    private function getProductosId()
    {
        if ($this->productosId == null) {
            $this->productosId = $this->getResultsId('CarroiridianBundle:Producto');
        }

        return $this->productosId;
    }

    /**
     * @param $key
     *
     * @return Producto
     */
    public function getProductoId($key)
    {
        $arrTextos = $this->getProductosId();

        if (isset($arrTextos[$key])) {
            return $arrTextos[$key];
        } else {
            return $key;
        }
    }

    private function getTallasId()
    {
        if ($this->tallasId == null) {
            $this->tallasId = $this->getResultsId('CarroiridianBundle:Talla');
        }

        return $this->tallasId;
    }

    /**
     * @param $key
     *
     * @return Talla
     */
    public function getTallaId($key)
    {
        $arrTextos = $this->getTallasId();

        if (isset($arrTextos[$key])) {
            return $arrTextos[$key];
        } else {
            return $key;
        }
    }

    public function getProductoById($id)
    {
        $locale = $this->request_stack->getCurrentRequest()->getLocale();
        $locale = ucfirst($locale);
        $path = $this->container->getParameter('app.path.productos');
        $qb = $this->em->createQueryBuilder();
        $qb
            ->select('p.id',
                'p.nombre'.$locale.' as nombre',
                'p.sku as sku',
                'p.precio as precio',
                'ca.id as categoria',
                'i.cantidad as cantidad',
                "concat('".$path."/',p.imagen) as imagen",
                "concat('".$path."/',p.imagenaux) as imagenaux",
                "GROUP_CONCAT(c.id ORDER by p.id SEPARATOR ' ') as colores",
                "GROUP_CONCAT(e.id ORDER by p.id SEPARATOR ' ') as estilos"
            )
            ->from('CarroiridianBundle:Producto', 'p')
            ->leftJoin('p.inventarios', 'i')
            ->leftJoin('p.color', 'c')
            ->leftJoin('p.estilos', 'e')
            ->leftJoin('p.categoria', 'ca')
            ->leftJoin('p.generos', 'gen')
            ->andWhere('p.visible = 1')
            ->andWhere('p.id = '.$id)
            ->orderBy('p.orden', 'asc');
        $results = $qb->getQuery()->getArrayResult();

        return $results;
    }

    /**
     * @param null   $categoria_id
     * @param null   $genero_id
     * @param string $search
     * @param null   $destacado
     * @param null   $limit
     * @param null   $vendido
     * @param null   $subcategoria_id
     *
     * @return array
     */
    public function getProductos($categoria_id = null, $genero_id = null, $search = '', $destacado = null, $limit = null, $vendido = null, $marca_id = null, $order = null, $orderTipe = null)
    {
        $locale = $this->request_stack->getCurrentRequest()->getLocale();
        $locale = ucfirst($locale);
        $path = $this->container->getParameter('app.path.productos');
        $qb = $this->em->createQueryBuilder();
        //dware
        $qb
            ->select('p.id',
                'p.nombre'.$locale.' as nombre',
                'p.resumen'.$locale.' as resumen',
                'p.descripcion'.$locale.' as descripcion',
                'p.caracteristicas'.$locale.' as caracteristicas',
                'p.sku as sku',
                'p.precio as precio',
                'p.precioUsuario as precioUsuario',
                'p.precioFecha as precioFecha',
                'p.precioFechaUsuario as precioFechaUsuario',
                'p.iva as iva',
                'ca.id as categoria',
                'i.cantidad as cantidad',
                "concat('".$path."/',p.imagen) as imagen",
                "concat('".$path."/',m.imagen) as imagenMarca",
                "concat('".$path."/',p.imagenaux) as imagenaux",
                "GROUP_CONCAT(DISTINCT f.id ORDER by p.id SEPARATOR ' ') as filtros",
                "GROUP_CONCAT(c.id ORDER by p.id SEPARATOR ' ') as colores"
            )
            ->from('CarroiridianBundle:Producto', 'p')
            ->leftJoin('p.inventarios', 'i')
            ->leftJoin('p.color', 'c')
            ->leftJoin('p.categoria', 'ca')
            ->leftJoin('p.filtros', 'f')
            ->leftJoin('p.marca', 'm')
            ->andWhere('p.visible = 1')
            ->groupBy('p.id');

        if (is_numeric($categoria_id)) {
            $qb->andWhere('p.categoria = '.$categoria_id);
        }
        if (is_numeric($marca_id)) {
            $qb->andWhere('p.marca = '.$marca_id);
        }
        if (is_numeric($genero_id)) {
            $qb->andWhere('gen.id = '.$genero_id);
        }

        if ($search != '') {
            $qb->andWhere('(p.nombre'.$locale." LIKE '%".$search."%' or p.sku LIKE '%".$search."%' or p.descripcion".$locale." LIKE '%".$search."%')");
        }
        if ($destacado) {
            $qb->andWhere('p.destacado = 1');
        }
        if ($vendido) {
            $qb->andWhere('p.vendido = 1');
        }
        if ($limit) {
            $qb->setMaxResults($limit);
        }
        if ($order) {
            $qb->orderBy('p.'.$order, $orderTipe);
        } else {
            $qb->orderBy('p.orden', 'asc');
        }

        $results = $qb->getQuery()->getArrayResult();

        return $results;
    }

    public function getFiltros($categoria_id = null, $genero_id = null, $search = '', $parte_id = null, $accesorios = false)
    {
        $locale = $this->request_stack->getCurrentRequest()->getLocale();
        $locale = ucfirst($locale);
        $path = $this->container->getParameter('app.path.productos');
        $qb = $this->em->createQueryBuilder();
        $qb
            ->select('t.id as tipo_id', 't.nombre'.$locale.' as tipo', 'f.id as filtro_id', 'f.nombre'.$locale.' as filtro'
            )
            ->from('CarroiridianBundle:Producto', 'p')
            ->leftJoin('p.categoria', 'ca')
            ->leftJoin('p.filtros', 'f')
            ->leftJoin('f.filtro', 't')
            ->where('f.id is not null')
            ->andWhere('p.visible = 1')
            ->groupBy('f.id')
            ->orderBy('t.orden', 'asc')
            ->addOrderBy('f.orden', 'asc');

        if (is_numeric($categoria_id)) {
            $qb->andWhere('p.categoria = '.$categoria_id);
        }

        if (is_numeric($genero_id)) {
            $qb->andWhere('gen.id = '.$genero_id);
        }

        if ($search != '') {
            $qb->andWhere('p.nombre'.$locale." LIKE '%".$search."%'");
        }
        if ($accesorios) {
            $qb->andWhere('p.accesorio = 1');
        }
        $results = $qb->getQuery()->getArrayResult();

        return $results;
    }

    public function getProductosRand()
    {
        $productos = $this->getProductos();
        shuffle($productos);
        $productos = array_slice($productos, 0, 4);

        return $productos;
    }

    public function getResultadosProductos($term)
    {
        $locale = $this->request_stack->getCurrentRequest()->getLocale();
        $locale = ucfirst($locale);
        $path = $this->container->getParameter('app.path.productos');
        $qb = $this->em->createQueryBuilder();
        $qb
            ->select('p.id', 'p.nombre'.$locale.' as nombre', 'p.sku as sku', 'p.precio as precio', 'ca.id as categoria',
                "concat('".$path."/',p.imagen) as imagen",
                "concat('".$path."/',p.imagenaux) as imagenaux",
                "GROUP_CONCAT(c.id ORDER by p.id SEPARATOR ' ') as colores",
                "GROUP_CONCAT(e.id ORDER by p.id SEPARATOR ' ') as estilos"
            )
            ->from('CarroiridianBundle:Producto', 'p')
            ->leftJoin('p.inventarios', 'i')
            ->leftJoin('p.color', 'c')
            ->leftJoin('p.estilos', 'e')
            ->leftJoin('p.categoria', 'ca')
            ->andWhere('p.visible = 1')
            ->groupBy('p.id')
            ->orderBy('p.orden', 'asc');

        if ($term) {
            $cad = '';
            $or = '';
            $searches = explode(' ', $term);
            foreach ($searches as $param) {
                $cad .= $or."p.nombreEs like '%".$param."%'";
                $or = ' or ';
                $cad .= $or."p.nombreEn like '%".$param."%'";
                $cad .= $or."p.tags like '%".$param."%'";
                $cad .= $or."ca.nombreEn like '%".$param."%'";
                $cad .= $or."ca.nombreEn like '%".$param."%'";
            }
            $qb->andWhere($cad);
        }

        $results = $qb->getQuery()->getArrayResult();

        return $results;
    }

    public function getProductosNuevos($limit)
    {
        $locale = $this->request_stack->getCurrentRequest()->getLocale();
        $locale = ucfirst($locale);
        $path = $this->container->getParameter('app.path.productos');
        $qb = $this->em->createQueryBuilder();
        $qb
            ->select('p.id', 'p.nombre'.$locale.' as nombre', 'p.sku as sku', 'p.precio as precio', 'ca.id as categoria',
                "concat('".$path."/',p.imagen) as imagen",
                "concat('".$path."/',p.imagenaux) as imagenaux",
                "GROUP_CONCAT(c.id ORDER by p.id SEPARATOR ' ') as colores",
                "GROUP_CONCAT(e.id ORDER by p.id SEPARATOR ' ') as estilos"
            )
            ->from('CarroiridianBundle:Producto', 'p')
            ->leftJoin('p.inventarios', 'i')
            ->leftJoin('p.color', 'c')
            ->leftJoin('p.estilos', 'e')
            ->leftJoin('p.categoria', 'ca')
            ->andWhere('p.visible = 1')
            ->andWhere('p.nuevo = 1')
            ->setMaxResults($limit)
            ->groupBy('p.id')
            ->orderBy('p.orden', 'asc');

        $results = $qb->getQuery()->getArrayResult();

        return $results;
    }

    public function getProductosRecomendados($limit)
    {
        $locale = $this->request_stack->getCurrentRequest()->getLocale();
        $locale = ucfirst($locale);
        $path = $this->container->getParameter('app.path.productos');
        $qb = $this->em->createQueryBuilder();
        $qb
            ->select('p.id', 'p.nombre'.$locale.' as nombre', 'p.sku as sku', 'p.precio as precio', 'ca.id as categoria',
                "concat('".$path."/',p.imagen) as imagen",
                "concat('".$path."/',p.imagenaux) as imagenaux",
                "GROUP_CONCAT(c.id ORDER by p.id SEPARATOR ' ') as colores",
                "GROUP_CONCAT(e.id ORDER by p.id SEPARATOR ' ') as estilos"
            )
            ->from('CarroiridianBundle:Producto', 'p')
            ->leftJoin('p.inventarios', 'i')
            ->leftJoin('p.color', 'c')
            ->leftJoin('p.estilos', 'e')
            ->leftJoin('p.categoria', 'ca')
            ->andWhere('p.visible = 1')
            ->andWhere('p.destacado = 1')
            ->setMaxResults($limit)
            ->groupBy('p.id')
            ->orderBy('p.orden', 'asc');

        $results = $qb->getQuery()->getArrayResult();

        return $results;
    }

    public function getProductosRelacionados($id, $limit)
    {
        $producto = $this->em->getRepository('CarroiridianBundle:Producto')->find($id);
        $locale = $this->request_stack->getCurrentRequest()->getLocale();
        $locale = ucfirst($locale);
        $path = $this->container->getParameter('app.path.productos');

        $qb = $this->em->createQueryBuilder();
        $qb
                ->select('p.id', 'p.nombre'.$locale.' as nombre', 'p.resumen'.$locale.' as resumen', 'p.sku as sku', 'p.precio as precio', 'p.precioUsuario as precioUsuario', 'ca.id as categoria',
                    'p.precioFecha as precioFecha', 'p.precioFechaUsuario as precioFechaUsuario', 'p.iva as iva',
                    "concat('".$path."/',p.imagen) as imagen",
                    "concat('".$path."/',m.imagen) as imagenMarca",
                    "concat('".$path."/',p.imagenaux) as imagenaux",
                    "GROUP_CONCAT(c.id ORDER by p.id SEPARATOR ' ') as colores",
                    "GROUP_CONCAT(e.id ORDER by p.id SEPARATOR ' ') as estilos"
                )
                ->from('CarroiridianBundle:Producto', 'p')
                ->leftJoin('p.inventarios', 'i')
                ->leftJoin('p.color', 'c')
                ->leftJoin('p.estilos', 'e')
                ->leftJoin('p.categoria', 'ca')
                ->leftJoin('p.marca', 'm')
                ->andWhere('p.visible = 1')
                ->setMaxResults($limit)
                ->groupBy('p.id')
                ->orderBy('p.orden', 'asc');

        if (count($producto->getRelacionados()) != 0) {
            $qb
                ->andWhere('p.id in (:productos_id)')
                ->setParameter('productos_id', $producto->getRelacionados());
        } elseif ($producto->getCategoria()) {
            $qb
                ->andWhere('p.categoria = :id_categoria')
                ->setParameter('id_categoria', $producto->getCategoria()->getId());
        }

        $results = $qb->getQuery()->getArrayResult();

        return $results;
    }

    public function getCategorias()
    {
        $locale = $this->request_stack->getCurrentRequest()->getLocale();
        $locale = ucfirst($locale);
        $qb = $this->em->createQueryBuilder();
        $qb
            ->select('p.id', 'p.nombre'.$locale.' as nombre')
            ->from('CarroiridianBundle:Categoria', 'p')
            ->andWhere('p.visible = 1')
            ->orderBy('p.orden', 'asc');

        $results = $qb->getQuery()->getArrayResult();

        return $results;
    }

    /**
     * @param $categoria_id
     *
     * @return array
     */
    public function getSubcategoriasByCat($categoria_id)
    {
        $locale = $this->request_stack->getCurrentRequest()->getLocale();
        $locale = ucfirst($locale);
        $qb = $this->em->createQueryBuilder();
        $qb
            ->select('p.id', 'p.nombre'.$locale.' as nombre')
            ->from('CarroiridianBundle:SubCategoria', 'p')
            ->andWhere('p.visible = 1')
            ->andWhere('p.categoria = '.$categoria_id)
            ->orderBy('p.orden', 'asc');

        $results = $qb->getQuery()->getArrayResult();

        return $results;
    }

    public function getGeneros()
    {
        $locale = $this->request_stack->getCurrentRequest()->getLocale();
        $locale = ucfirst($locale);
        $qb = $this->em->createQueryBuilder();
        $qb
            ->select('p.id', 'p.nombre'.$locale.' as nombre', 'p.imagen')
            ->from('CarroiridianBundle:Genero', 'p')
            ->andWhere('p.visible = 1')
            ->orderBy('p.orden', 'asc');

        $results = $qb->getQuery()->getArrayResult();

        return $results;
    }

    public function getColores()
    {
        $locale = $this->request_stack->getCurrentRequest()->getLocale();
        $locale = ucfirst($locale);
        $qb = $this->em->createQueryBuilder();
        $qb
            ->select('p.id', 'p.nombre'.$locale.' as nombre', 'p.hexa as hexa')
            ->from('CarroiridianBundle:Color', 'p')
            ->andWhere('p.visible = 1')
            ->orderBy('p.orden', 'asc');

        $results = $qb->getQuery()->getArrayResult();

        return $results;
    }

    public function getEstilos()
    {
        $locale = $this->request_stack->getCurrentRequest()->getLocale();
        $locale = ucfirst($locale);
        $qb = $this->em->createQueryBuilder();
        $qb
            ->select('p.id', 'p.nombre'.$locale.' as nombre')
            ->from('CarroiridianBundle:Estilo', 'p')
            ->andWhere('p.visible = 1')
            ->orderBy('p.orden', 'asc');

        $results = $qb->getQuery()->getArrayResult();

        return $results;
    }

    public function getTallasProducto($id)
    {
        $locale = $this->request_stack->getCurrentRequest()->getLocale();
        $locale = ucfirst($locale);
        $qb = $this->em->createQueryBuilder();
        $qb
            ->select('t.id', 't.nombre'.$locale.' as nombre')
            ->from('CarroiridianBundle:Producto', 'p')
            ->leftJoin('p.inventarios', 'i')
            ->leftJoin('i.talla', 't')
            ->andWhere('p.id = :id')
            ->andWhere('t.id is not null')
            ->setParameter('id', $id)
            ->groupBy('t.id')
            ->orderBy('t.orden', 'asc');
        $results = $qb->getQuery()->getArrayResult();

        return $results;
    }

    /**
     * @param $id id del producto
     * @param $id_talla id de la talla
     *
     * @return \CarroiridianBundle\Entity\Inventario
     */
    public function getInventario($id, $id_talla)
    {
        $qb = $this->em->getRepository('CarroiridianBundle:Inventario')
            ->findOneBy(array('producto' => $id, 'talla' => $id_talla));

        return $qb;
    }

    public function getById($tabla, $campo, $id)
    {
        $out = $this->em->getRepository($tabla)->findBy(array($campo => $id), array('orden' => 'asc'));

        return $out;
    }

    public function cantProds(Session $session)
    {
        $carrito = $session->get('carrito', array());
        $cant = 0;
        foreach ($carrito as $p_id => $prod) {
            foreach ($prod as $t_id => $tall) {
                if ($carrito[$p_id][$t_id]['cantidad'] > 0) {
                    ++$cant;
                }
            }
        }

        return $cant;
    }

    public function cantCart()
    {
        $session = new Session();
        $carrito = $session->get('carrito', array());
        $cant = 0;
        foreach ($carrito as $p_id => $prod) {
            foreach ($prod as $t_id => $tall) {
                if ($carrito[$p_id][$t_id]['cantidad'] > 0) {
                    ++$cant;
                }
            }
        }

        return $cant;
    }
}
