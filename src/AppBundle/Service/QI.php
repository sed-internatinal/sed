<?php
/**
 * Created by PhpStorm.
 * User: Iridian 1
 * Date: 1/02/2016
 * Time: 12:29 PM.
 */

namespace AppBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;

class QI
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
    protected $imagenesGen = null;
    protected $pixel = null;
    protected $archivos = null;

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

    //DWARE
    public function getPrecioUserSinDescuento($id, $usera)
    {
        $prod = $this->em->getRepository('CarroiridianBundle:Producto')->find($id);
        $precio = 0;
        if ($prod) {
            if ($usera) {
                if ($usera->getEsEmpresa()) {
                    $precio = $prod->getPrecio();
                } else {
                    $precio = $prod->getPrecioUsuario();
                }
                //dware
                if ($prod->getIva() > 0) {
                    $precio += $precio * ($prod->getIva() / 100);
                }
            } else {
                $precio = '';
            }
        }

        return $precio;
    }

    public function getPrecioUser($id, $usera)
    {
        $prod = $this->em->getRepository('CarroiridianBundle:Producto')->find($id);
        //dware
        $precio = 0;

        if ($prod) {
            if ($usera) {
                if ($usera->getEsEmpresa()) {
                    $precio = $prod->getPrecio();
                } else {
                    $precio = $prod->getPrecioUsuario();
                }
                $user = $this->em->getRepository('AppBundle:User')->find($usera->getId());
                if ($user) {
                    $descuento = $this->em->getRepository('CarroiridianBundle:PrecioUsuario')->findOneBy(array('user' => $user, 'producto' => $prod));
                    if ($descuento && $descuento->getPorcentaje()) {
                        $precio = $precio - ($precio * $descuento->getPorcentaje() / 100);
                    }
                    if ($descuento && $descuento->getPrecio()) {
                        $precio = $descuento->getPrecio();
                    }
                }
                //dware
                if ($prod->getIva() > 0) {
                    $precio += $precio * ($prod->getIva() / 100);
                }
            } else {
                $precio = '';
            }
        }

        return $precio;
    }

    private function getResults($entidad)
    {
        $qb = $this->em->createQueryBuilder()
            ->select('s')
            ->from($entidad, 's', 's.llave')
            ->where('s.llave is not null')
            ->getQuery()
            ->useQueryCache(true)
            ->useResultCache(true)
            ->getArrayResult();

        return $qb;
    }

    /**
     * Retorna la variable con textos populada la primera vez que se llama esta función, los textos se traen de la base de datos.
     *
     * @return multitype: Arreglo con los textos intenacionalizados
     */
    private function getTextosDB()
    {
        if ($this->textosDB == null) {
            $this->textosDB = $this->getResults('AppBundle:Texto');
        }

        return $this->textosDB;
    }

    /**
     * Retorna la variable con el pixel la primera vez que se llama esta función, el pixel se trae de la base de datos.
     *
     * @return multitype: string con el id del pixel
     */
    public function getPixel()
    {
        if ($this->pixel == null) {
            $this->pixel = $this->em->getRepository('AppBundle:Pixel')->find(1);
        }

        return ($this->pixel) ? $this->pixel->getIdentificador() : '';
    }

    /**
     * Obtener uno de los texto fijos internacionalizados según el UserCulture actual y el nombre del texto solicitado. Los textos se traen de la base de datos.
     *
     * @param string $key Nombre (identificador) del texto solicitado
     *
     * @return string El texto solicitado
     */
    public function getTextoDB($key)
    {
        $locale = $this->request_stack->getCurrentRequest()->getLocale();
        $arrTextos = $this->getTextosDB();

        if (isset($arrTextos[$key][$locale]) && $arrTextos[$key][$locale] != '') {
            return $arrTextos[$key][$locale];
        } else {
            return $key;
        }
    }

    private function getTextosBigDB()
    {
        if ($this->textosBigDB == null) {
            $this->textosBigDB = $this->getResults('AppBundle:TextoBig');
        }

        return $this->textosBigDB;
    }

    public function getTextoBigDB($key)
    {
        $locale = $this->request_stack->getCurrentRequest()->getLocale();
        $arrTextos = $this->getTextosBigDB();
        if (isset($arrTextos[$key][$locale])) {
            return $arrTextos[$key][$locale];
        } else {
            return $key;
        }
    }

    private function getImagenes()
    {
        if ($this->imagenes == null) {
            $this->imagenes = $this->getResults('AppBundle:Imagengaleria');
        }

        return $this->imagenes;
    }

    private function getImagenesGeneral()
    {
        if ($this->imagenesGen == null) {
            $this->imagenesGen = $this->getResults('AppBundle:Imagen');
        }

        return $this->imagenesGen;
    }

    public function getImagengen($key)
    {
        $locale = $this->request_stack->getCurrentRequest()->getLocale();
        $locale = ucfirst($locale);
        $imagenes = $this->getImagenesGeneral();
        $path = $this->container->getParameter('app.path.images');
        //exit(\Doctrine\Common\Util\Debug::dump($imagenes));
        if (isset($imagenes[$key])) {
            return $path.'/'.$imagenes[$key]['image'];
        } else {
            return $path.'/';
        }
    }

    public function getImagen($key)
    {
        $locale = $this->request_stack->getCurrentRequest()->getLocale();
        $locale = ucfirst($locale);
        $imagenes = $this->getImagenes();
        $path = $this->container->getParameter('app.path.imagesgal');
        if (isset($imagenes[$key])) {
            return $path.'/'.$imagenes[$key]['image'.$locale];
        } else {
            return $path.'/';
        }
    }

    public function getImagenAlt($key)
    {
        $imagenes = $this->getImagenes();
        if (isset($imagenes[$key])) {
            return $imagenes[$key]['alt'];
        } else {
            return '';
        }
    }

    public function getImagenLink($key)
    {
        $imagenes = $this->getImagenes();
        if (isset($imagenes[$key])) {
            return $imagenes[$key]['link'];
        } else {
            return '';
        }
    }

    public function getImagenTitulo($key)
    {
        $locale = $this->request_stack->getCurrentRequest()->getLocale();
        $locale = ucfirst($locale);
        $imagenes = $this->getImagenes();
        $path = $this->container->getParameter('app.path.imagesgal');
        if (isset($imagenes[$key])) {
            return $imagenes[$key]['titulo'.$locale];
        } else {
            return '';
        }
    }

    public function getImagenResumen($key)
    {
        $locale = $this->request_stack->getCurrentRequest()->getLocale();
        $locale = ucfirst($locale);
        $imagenes = $this->getImagenes();
        if (isset($imagenes[$key])) {
            return $imagenes[$key]['resumen'.$locale];
        } else {
            return '';
        }
    }

    /**
     * Retorna la variable con settings populada la primera vez que se llama esta función, los textos se traen de la base de datos.
     *
     * @return multitype: Arreglo con los settings
     */
    private function getSettingsDB()
    {
        if ($this->settings == null) {
            $this->settings = $this->getResults('AppBundle:Settings');
        }

        return $this->settings;
    }

    /**
     * Obtener uno de los texto fijos internacionalizados según el local actual y el nombre del texto solicitado. Los textos se traen de la base de datos.
     *
     * @param string $key Nombre (identificador) del texto solicitado
     *
     * @return string El setting solicitado
     */
    public function getSettingDB($key)
    {
        $settings = $this->getSettingsDB();
        if (isset($settings[$key]) && $settings[$key]['valor'] != '') {
            return $settings[$key]['valor'];
        } else {
            return $key;
        }
    }

    private function getArchivos()
    {
        if ($this->archivos == null) {
            $this->archivos = $this->getResults('AppBundle:Archivo');
        }

        return $this->archivos;
    }

    public function getArchivo($key)
    {
        $locale = $this->request_stack->getCurrentRequest()->getLocale();
        $lang = ucfirst($locale);
        $arrTextos = $this->getArchivos();
        $col = 'archivo'.$lang;

        if (isset($arrTextos[$key][$col]) && $arrTextos[$key][$col] != '') {
            return $this->container->getParameter('app.path.images').'/'.$arrTextos[$key][$col];
        } else {
            return $key;
        }
    }

    public function getGaleria($llave)
    {
        $locale = $this->request_stack->getCurrentRequest()->getLocale();
        $locale = ucfirst($locale);
        $path = $this->container->getParameter('app.path.imagesgal');
        $path_carta = $this->container->getParameter('app.path.carta');
        $qb = $this->em->createQueryBuilder();
        $qb
            ->select('a.colorHexa as color', 'a.llave', 'a.id', "concat('".$path."/',a.imageEn) as fondo", "concat('".$path."/',a.imageEs) as icono", "concat('".$path."/',a.image".$locale.') as imagen', 'a.alt', 'a.link', "concat('".$path_carta."/',a.documentoEs) as docEs", "concat('".$path_carta."/',a.documentoEn) as docEn", 'a.subtitulo'.$locale.' as subtitulo', 'a.titulo'.$locale.' as titulo', 'a.resumen'.$locale.' as resumen')
            ->from('AppBundle:Imagengaleria', 'a')
            ->leftJoin('a.galeria', 'g')
            ->where('g.llave = :llave')
            ->andWhere('a.visible = 1')
            ->setParameter('llave', $llave)
            ->orderBy('a.orden', 'asc');

        $out = array();
        $results = $qb->getQuery()->getArrayResult();
        foreach ($results as $item) {
            $link = $item['link'];
            if (strpos($link, 'http') !== false) {
                $item['target'] = '_blank';
            } else {
                $item['target'] = '_self';
                //dware
                $item['link'] = $item['link'];
                //$item['link'] = $this->container->get('router')->generate('homepage').$item['link'];
                /*
                if(strpos($link,'#') === false && $link != null)
                    $item['link'] = $this->container->get('router')->generate($link);
                */
            }
            array_push($out, $item);
        }

        return $out;
    }

    public function getGaleriaFull($llave)
    {
        $locale = $this->request_stack->getCurrentRequest()->getLocale();
        $locale = ucfirst($locale);
        $path = $this->container->getParameter('app.path.imagesgal');
        $path_carta = $this->container->getParameter('app.path.carta');
        $qb = $this->em->createQueryBuilder();
        $qb
            ->select('a.id', "concat('".$path."/',a.image".$locale.') as imagen', 'a.alt', 'a.link', "concat('".$path_carta."/',a.documento".$locale.') as doc', 'a.titulo'.$locale.' as titulo', 'a.resumen'.$locale.' as resumen')
            ->from('AppBundle:Imagengaleria', 'a')
            ->leftJoin('a.galeria', 'g')
            ->where('g.llave = :llave')
            ->setParameter('llave', $llave)
            ->orderBy('a.orden', 'asc');

        $out = array();
        $results = $qb->getQuery()->getArrayResult();
        foreach ($results as $item) {
            $link = $item['link'];
            if (strpos($link, 'http') !== false) {
                $item['target'] = '_blank';
            } else {
                $item['target'] = '_self';
                $item['link'] = $this->container->get('router')->generate('homepage').$item['link'];
                /*
                if(strpos($link,'#') === false && $link != null)
                    $item['link'] = $this->container->get('router')->generate($link);
                */
            }
            array_push($out, $item);
        }

        return $out;
    }

    public function getAniosBlog()
    {
        return $this->em->getRepository('BlogiridianBundle:Post')
            ->createQueryBuilder('post')
            ->select('post.id as id, MONTH(post.fecha) as mes, YEAR(post.fecha) as anio, post.fecha as fecha')
            ->where('post.visible = 1')
            ->groupBy('anio, mes')
            ->orderBy('post.fecha', 'asc')
            ->getQuery()
            ->getResult();
    }

    public function getResultadosBlog($term, $limit = null, $year = null, $month = null)
    {
        $locale = $this->request_stack->getCurrentRequest()->getLocale();
        $locale = ucfirst($locale);
        $path = $this->container->getParameter('app.path.blogs');
        $qb = $this->em->createQueryBuilder();
        $qb
            ->select('p.id', 'p.titulo'.$locale.' as titulo', "concat('".$path."/',p.image) as imagen, p.fecha"
            )
            ->from('BlogiridianBundle:Post', 'p')
            ->andWhere('p.visible = 1')
            ->groupBy('p.id')
            ->orderBy('p.fecha', 'asc');
        if ($limit) {
            $qb->setMaxResults($limit);
        }
        if ($year && $month) {
            $qb->andWhere('MONTH(p.fecha) = '.$month)
                ->andWhere('YEAR(p.fecha) = '.$year);
        }
        if ($term) {
            $cad = '';
            $or = '';
            $searches = explode(' ', $term);
            foreach ($searches as $param) {
                $cad .= $or."p.tituloEs like '%".$param."%'";
                $or = ' or ';
                $cad .= $or."p.tituloEn like '%".$param."%'";
                $cad .= $or."p.tags like '%".$param."%'";
            }
            $qb->andWhere($cad);
        }

        $results = $qb->getQuery()->getArrayResult();

        return $results;
    }

    public function getById($tabla, $campo, $id)
    {
        $out = $this->em->getRepository($tabla)->findBy(array($campo => $id), array('orden' => 'asc'));

        return $out;
    }

    public function getNombrecat($id)
    {
        if (!$id) {
            return $this->getTextoDB('productos');
        }
        $out = $this->em->getRepository('CarroiridianBundle:Categoria')->find($id);
        $locale = $this->request_stack->getCurrentRequest()->getLocale();
        $locale = ucfirst($locale);

        return $out->gen('nombre', $locale);
    }

    public function getNombremarca($id)
    {
        if (!$id) {
            return '';
        }
        $out = $this->em->getRepository('CarroiridianBundle:Marca')->find($id);

        return $out->getNombre();
    }

    /**
     * @param $subject
     * @param $from
     * @param $to
     * @param $custom
     * @param $template
     */
    public function sendMail($subject, $from, $to, $custom, $template)
    {
        $headers = 'MIME-Version: 1.0'."\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1'."\r\n";
        $headers .= 'From: '.$from."\r\n".
            'Reply-To: '.$from."\r\n".
            'X-Mailer: PHP/'.phpversion();

        $message = $template;

        try {
            if (mail($to, $subject, $message, $headers)) {
                //echo 'Your mail has been sent successfully.';
            } else {
                //echo 'Unable to send email. Please try again.';
            }
        } catch (\Exception $e) {
        }
    }

    public function getSeo($url, $homepage)
    {
        $url_abs = $url;
        $url = str_replace($homepage, '', $url);
        $locale = $this->request_stack->getCurrentRequest()->getLocale();
        $locale = ucfirst($locale);
        $path = $this->container->getParameter('app.path.images');
        $qb = $this->em->createQueryBuilder();
        $qb2 = $this->em->createQueryBuilder();
        //exit(dump($url));
        if ($url == '') {
            $url = '/';
        }
        $seo = $qb
            ->select('p.id', 'p.titulo'.$locale.' as titulo', "concat('".$path."/',p.imagen) as imagen", 'p.descripcion'.$locale.' as descripcion')
            ->from('AppBundle:Seo', 'p')
            ->where('p.url = :url')
            ->setParameter('url', $url)
            ->orderBy('p.id', 'desc')
            ->getQuery()
            ->getOneOrNullResult();

        if ($seo == null) {
            $seo = $qb2
                ->select('s.id', 's.titulo'.$locale.' as titulo', "concat('".$path."/',s.imagen) as imagen", 's.descripcion'.$locale.' as descripcion')
                ->from('AppBundle:Seo', 's')
                ->orderBy('s.id', 'desc')
                ->setMaxResults(1)
                ->getQuery()
                ->getOneOrNullResult();
        }

        return array(
            'seo' => $seo,
            'homepage' => str_replace(array('app.php/', 'app_dev.php/'), array('', ''), $homepage),
            'url' => $url_abs,
        );
    }

    public function slugify($text)
    {
        // replace non letter or digits by -
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);

        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        // trim
        $text = trim($text, '-');

        // remove duplicate -
        $text = preg_replace('~-+~', '-', $text);

        // lowercase
        $text = strtolower($text);

        if (empty($text)) {
            return 'n-a';
        }

        return $text;
    }
}
