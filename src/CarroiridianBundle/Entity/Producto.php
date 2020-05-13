<?php

namespace CarroiridianBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use HomeBundle\Entity\Ingrediente;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * Producto
 *
 * @ORM\Table(name="producto")
 * @ORM\Entity(repositoryClass="CarroiridianBundle\Repository\ProductoRepository")
 * @Vich\Uploadable
 */
class Producto
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="CarroiridianBundle\Entity\Galeriaproducto", mappedBy="producto")
     */
    private $galerias;

    /**
     * @ORM\OneToMany(targetEntity="CarroiridianBundle\Entity\Inventario", mappedBy="producto")
     */
    private $inventarios;

    /**
     * @var Genero[]
     * @ORM\ManyToMany(targetEntity="CarroiridianBundle\Entity\Genero")
     */
    protected $generos;

    /**
     * @var Color[]
     * @ORM\ManyToMany(targetEntity="CarroiridianBundle\Entity\Color")
     */
    protected $colores;


    /**
     * @var Producto[]
     * @ORM\ManyToMany(targetEntity="CarroiridianBundle\Entity\Producto")
     */
    protected $relacionados;

    /**
     * @var Uso[]
     * @ORM\ManyToMany(targetEntity="CarroiridianBundle\Entity\Uso")
     */
    protected $usos;

    /**
     * @ORM\ManyToOne(targetEntity="CarroiridianBundle\Entity\Categoria")
     * @ORM\JoinColumn(name="categoria_id", referencedColumnName="id")
     */
    private $categoria;

    /**
     * @ORM\ManyToOne(targetEntity="CarroiridianBundle\Entity\Marca")
     * @ORM\JoinColumn(name="marca_id", referencedColumnName="id")
     */
    private $marca;

    /**
     * @ORM\ManyToOne(targetEntity="CarroiridianBundle\Entity\SubCategoria")
     * @ORM\JoinColumn(name="subcategoria_id", referencedColumnName="id")
     */
    private $subcategoria;

    /**
     * @ORM\ManyToOne(targetEntity="CarroiridianBundle\Entity\Modelo")
     * @ORM\JoinColumn(name="modelo_id", referencedColumnName="id")
     */
    private $modelo;

    /**
     * @var Color[]
     * @ORM\ManyToMany(targetEntity="CarroiridianBundle\Entity\Filtro")
     */
    protected $filtros;

    /**
     * @ORM\ManyToOne(targetEntity="CarroiridianBundle\Entity\Color")
     * @ORM\JoinColumn(name="color_id", referencedColumnName="id")
     */
    private $color;

    /**
     * @var string
     *
     * @ORM\Column(name="sku", type="string", length=255)
     */
    private $sku;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre_es", type="string", length=255)
     */
    private $nombreEs;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre_en", type="string", length=255, nullable=true)
     */
    private $nombreEn;

    /**
     * @var string
     *
     * @ORM\Column(name="imagen", type="string", length=255, nullable=true)
     */
    private $imagen;

    /**
     * @Vich\UploadableField(mapping="productos", fileNameProperty="imagen")
     * @var File
     */
    private $imageFile;

    /**
     * @var string
     *
     * @ORM\Column(name="imagenaux", type="string", length=255, nullable=true)
     */
    private $imagenaux;

    /**
     * @Vich\UploadableField(mapping="productos", fileNameProperty="imagenaux")
     * @var File
     */
    private $imageauxFile;

    /**
     * @var float
     *
     * @ORM\Column(name="precio", type="float")
     */
    private $precio;

    /**
     * @var \float
     *
     * @ORM\Column(name="precio_fecha", type="float", nullable=true)
     */
    private $precioFecha;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_ini", type="datetime", nullable=true)
     */
    private $fechaIni;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_fin", type="datetime", nullable=true)
     */
    private $fechaFin;

    /**
     * @var bool
     *
     * @ORM\Column(name="destacado", type="boolean", nullable=true)
     */
    private $destacado;

    /**
     * @var bool
     *
     * @ORM\Column(name="vendido", type="boolean", nullable=true)
     */
    private $vendido;

    /**
     * @var int
     *
     * @ORM\Column(name="unidades", type="integer", nullable=true)
     */
    private $unidades;

    /**
     * @var int
     *
     * @ORM\Column(name="min_unidades", type="integer", nullable=true)
     */
    private $minUnidades;

    /**
     * @var string
     *
     * @ORM\Column(name="descripcion_es", type="text", nullable=true)
     */
    private $descripcionEs;

    /**
     * @var string
     *
     * @ORM\Column(name="descripcion_en", type="text", nullable=true)
     */
    private $descripcionEn;

    /**
     * @var string
     *
     * @ORM\Column(name="caracteristicas_es", type="text", nullable=true)
     */
    private $caracteristicasEs;

    /**
     * @var string
     *
     * @ORM\Column(name="caracteristicas_en", type="text", nullable=true)
     */
    private $caracteristicasEn;

    /**
     * @var string
     *
     * @ORM\Column(name="garantia_es", type="text", nullable=true)
     */
    private $garantiaEs;

    /**
     * @var string
     *
     * @ORM\Column(name="garantia_en", type="text", nullable=true)
     */
    private $garantiaEn;

    /**
     * @var string
     *
     * @ORM\Column(name="resumen_es", type="text", nullable=true)
     */
    private $resumenEs;

    /**
     * @var string
     *
     * @ORM\Column(name="resumen_en", type="text", nullable=true)
     */
    private $resumenEn;

    /**
     * @var Estilo[]
     * @ORM\ManyToMany(targetEntity="CarroiridianBundle\Entity\Estilo")
     */
    protected $estilos;

    /**
     *
     * @var array
     * @ORM\Column(name="caracteristicas",type="array")
     */
    private $caracteristicas = array();

    /**
     * List of tags associated to the product.
     *
     * @var array
     * @ORM\Column(name="tags", type="array")
     */
    private $tags = array();


    /**
     * @var string
     *
     * @ORM\Column(name="alt", type="string", length=255)
     */
    private $alt;

    /**
     * @var boolean
     *
     * @ORM\Column(name="nuevo", type="boolean")
     */
    private $nuevo = false;


    /**
     * @var integer
     *
     * @ORM\Column(name="orden", type="integer")
     */
    private $orden = 1;

    /**
     * @var boolean
     *
     * @ORM\Column(name="visible", type="boolean")
     */
    private $visible = true;

    /**
     * @ORM\Column(type="datetime")
     * @var \DateTime
     */
    private $updatedAt;



    public function __toString()
    {
        return $this->nombreEs.' ';
    }

    public function gen($campo,$locale){
        $accessor = PropertyAccess::createPropertyAccessor();
        return $accessor->getValue($this,$campo.'_'.$locale);
    }


    public function __construct()
    {
        $this->estilos = new ArrayCollection();
        $this->galerias = new ArrayCollection();
        $this->ingredientes = new ArrayCollection();
        $this->updatedAt = new \DateTime();
        $this->relacionados = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set sku
     *
     * @param string $sku
     *
     * @return Producto
     */
    public function setSku($sku)
    {
        $this->sku = $sku;

        return $this;
    }

    /**
     * Get sku
     *
     * @return string
     */
    public function getSku()
    {
        return $this->sku;
    }

    /**
     * Set nombreEs
     *
     * @param string $nombreEs
     *
     * @return Producto
     */
    public function setNombreEs($nombreEs)
    {
        $this->nombreEs = $nombreEs;

        return $this;
    }

    /**
     * Get nombreEs
     *
     * @return string
     */
    public function getNombreEs()
    {
        return $this->nombreEs;
    }

    /**
     * Set nombreEn
     *
     * @param string $nombreEn
     *
     * @return Producto
     */
    public function setNombreEn($nombreEn)
    {
        $this->nombreEn = $nombreEn;

        return $this;
    }

    /**
     * Get nombreEn
     *
     * @return string
     */
    public function getNombreEn()
    {
        return $this->nombreEn;
    }

    /**
     * Set imagen
     *
     * @param string $imagen
     *
     * @return Producto
     */
    public function setImagen($imagen)
    {
        $this->imagen = $imagen;

        return $this;
    }

    /**
     * Get imagen
     *
     * @return string
     */
    public function getImagen()
    {
        return $this->imagen;
    }

    /**
     * Set precio
     *
     * @param float $precio
     *
     * @return Producto
     */
    public function setPrecio($precio)
    {
        $this->precio = $precio;

        return $this;
    }

    /**
     * Get precio
     *
     * @return float
     */
    public function getPrecio()
    {
        return $this->precio;
    }


    /**
     * Set fechaIni
     *
     * @param \DateTime $fechaIni
     *
     * @return Producto
     */
    public function setFechaIni($fechaIni)
    {
        $this->fechaIni = $fechaIni;

        return $this;
    }

    /**
     * Get fechaIni
     *
     * @return \DateTime
     */
    public function getFechaIni()
    {
        return $this->fechaIni;
    }

    /**
     * Set fechaFin
     *
     * @param \DateTime $fechaFin
     *
     * @return Producto
     */
    public function setFechaFin($fechaFin)
    {
        $this->fechaFin = $fechaFin;

        return $this;
    }

    /**
     * Get fechaFin
     *
     * @return \DateTime
     */
    public function getFechaFin()
    {
        return $this->fechaFin;
    }

    /**
     * Set destacado
     *
     * @param boolean $destacado
     *
     * @return Producto
     */
    public function setDestacado($destacado)
    {
        $this->destacado = $destacado;

        return $this;
    }

    /**
     * Get destacado
     *
     * @return bool
     */
    public function getDestacado()
    {
        return $this->destacado;
    }

    /**
     * Set unidades
     *
     * @param integer $unidades
     *
     * @return Producto
     */
    public function setUnidades($unidades)
    {
        $this->unidades = $unidades;

        return $this;
    }

    /**
     * Get unidades
     *
     * @return int
     */
    public function getUnidades()
    {
        return $this->unidades;
    }

    /**
     * Set minUnidades
     *
     * @param integer $minUnidades
     *
     * @return Producto
     */
    public function setMinUnidades($minUnidades)
    {
        $this->minUnidades = $minUnidades;

        return $this;
    }

    /**
     * Get minUnidades
     *
     * @return int
     */
    public function getMinUnidades()
    {
        return $this->minUnidades;
    }

    /**
     * Set descripcionEs
     *
     * @param string $descripcionEs
     *
     * @return Producto
     */
    public function setDescripcionEs($descripcionEs)
    {
        $this->descripcionEs = $descripcionEs;

        return $this;
    }

    /**
     * Get descripcionEs
     *
     * @return string
     */
    public function getDescripcionEs()
    {
        return $this->descripcionEs;
    }

    /**
     * Set descripcionEn
     *
     * @param string $descripcionEn
     *
     * @return Producto
     */
    public function setDescripcionEn($descripcionEn)
    {
        $this->descripcionEn = $descripcionEn;

        return $this;
    }

    /**
     * Get descripcionEn
     *
     * @return string
     */
    public function getDescripcionEn()
    {
        return $this->descripcionEn;
    }

    /**
     * Set precioFecha
     *
     * @param float $precioFecha
     *
     * @return Producto
     */
    public function setPrecioFecha($precioFecha)
    {
        $this->precioFecha = $precioFecha;

        return $this;
    }

    /**
     * Get precioFecha
     *
     * @return float
     */
    public function getPrecioFecha()
    {
        return $this->precioFecha;
    }

    /**
     * Add estilo
     *
     * @param \CarroiridianBundle\Entity\Estilo $estilo
     *
     * @return Producto
     */
    public function addEstilo(\CarroiridianBundle\Entity\Estilo $estilo)
    {
        $this->estilos[] = $estilo;

        return $this;
    }

    /**
     * Remove estilo
     *
     * @param \CarroiridianBundle\Entity\Estilo $estilo
     */
    public function removeEstilo(\CarroiridianBundle\Entity\Estilo $estilo)
    {
        $this->estilos->removeElement($estilo);
    }

    /**
     * Get estilos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEstilos()
    {
        return $this->estilos;
    }


    /**
     * Set alt
     *
     * @param string $alt
     *
     * @return Producto
     */
    public function setAlt($alt)
    {
        $this->alt = $alt;
    
        return $this;
    }

    /**
     * Get alt
     *
     * @return string
     */
    public function getAlt()
    {
        return $this->alt;
    }

    /**
     * Set orden
     *
     * @param integer $orden
     *
     * @return Producto
     */
    public function setOrden($orden)
    {
        $this->orden = $orden;
    
        return $this;
    }

    /**
     * Get orden
     *
     * @return integer
     */
    public function getOrden()
    {
        return $this->orden;
    }

    /**
     * Set visible
     *
     * @param boolean $visible
     *
     * @return Producto
     */
    public function setVisible($visible)
    {
        $this->visible = $visible;
    
        return $this;
    }

    /**
     * Get visible
     *
     * @return boolean
     */
    public function getVisible()
    {
        return $this->visible;
    }

    /**
     * Add galeria
     *
     * @param \CarroiridianBundle\Entity\Galeriaproducto $galeria
     *
     * @return Producto
     */
    public function addGaleria(\CarroiridianBundle\Entity\Galeriaproducto $galeria)
    {
        $this->galerias[] = $galeria;
    
        return $this;
    }

    /**
     * Remove galeria
     *
     * @param \CarroiridianBundle\Entity\Galeriaproducto $galeria
     */
    public function removeGaleria(\CarroiridianBundle\Entity\Galeriaproducto $galeria)
    {
        $this->galerias->removeElement($galeria);
    }

    /**
     * Get galerias
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getGalerias()
    {
        return $this->galerias;
    }

    /**
     * Set categoria
     *
     * @param \CarroiridianBundle\Entity\Categoria $categoria
     *
     * @return Producto
     */
    public function setCategoria(\CarroiridianBundle\Entity\Categoria $categoria = null)
    {
        $this->categoria = $categoria;
    
        return $this;
    }

    /**
     * Get categoria
     *
     * @return \CarroiridianBundle\Entity\Categoria
     */
    public function getCategoria()
    {
        return $this->categoria;
    }

    /**
     * @param File $image
     */
    public function setImageFile(File $image = null)
    {
        $this->imageFile = $image;
        if ($image) {
            $this->updatedAt = new \DateTime('now');
        }
    }

    /**
     * @return File
     */
    public function getImageFile()
    {
        return $this->imageFile;
    }

    /**
     * Add inventario
     *
     * @param \CarroiridianBundle\Entity\Inventario $inventario
     *
     * @return Producto
     */
    public function addInventario(\CarroiridianBundle\Entity\Inventario $inventario)
    {
        $this->inventarios[] = $inventario;
    
        return $this;
    }

    /**
     * Remove inventario
     *
     * @param \CarroiridianBundle\Entity\Inventario $inventario
     */
    public function removeInventario(\CarroiridianBundle\Entity\Inventario $inventario)
    {
        $this->inventarios->removeElement($inventario);
    }

    /**
     * Get inventarios
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getInventarios()
    {
        return $this->inventarios;
    }

    /**
     * Set color
     *
     * @param \CarroiridianBundle\Entity\Color $color
     *
     * @return Producto
     */
    public function setColor(\CarroiridianBundle\Entity\Color $color = null)
    {
        $this->color = $color;
    
        return $this;
    }

    /**
     * Get color
     *
     * @return \CarroiridianBundle\Entity\Color
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * Set caracteristicas
     *
     * @param array $caracteristicas
     *
     * @return Producto
     */
    public function setCaracteristicas($caracteristicas)
    {
        $this->caracteristicas = $caracteristicas;
    
        return $this;
    }

    /**
     * Get caracteristicas
     *
     * @return array
     */
    public function getCaracteristicas()
    {
        return $this->caracteristicas;
    }

    /**
     * Set tags
     *
     * @param array $tags
     *
     * @return Producto
     */
    public function setTags($tags)
    {
        $this->tags = $tags;
    
        return $this;
    }

    /**
     * Get tags
     *
     * @return array
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Add colore
     *
     * @param \CarroiridianBundle\Entity\Color $colore
     *
     * @return Producto
     */
    public function addColore(\CarroiridianBundle\Entity\Color $colore)
    {
        $this->colores[] = $colore;
    
        return $this;
    }

    /**
     * Remove colore
     *
     * @param \CarroiridianBundle\Entity\Color $colore
     */
    public function removeColore(\CarroiridianBundle\Entity\Color $colore)
    {
        $this->colores->removeElement($colore);
    }

    /**
     * Get colores
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getColores()
    {
        return $this->colores;
    }

    /**
     * Set nuevo
     *
     * @param boolean $nuevo
     *
     * @return Producto
     */
    public function setNuevo($nuevo)
    {
        $this->nuevo = $nuevo;
    
        return $this;
    }

    /**
     * Get nuevo
     *
     * @return boolean
     */
    public function getNuevo()
    {
        return $this->nuevo;
    }



    /**
     * Set imagenaux
     *
     * @param string $imagenaux
     *
     * @return Producto
     */
    public function setImagenaux($imagenaux)
    {
        $this->imagenaux = $imagenaux;
    
        return $this;
    }

    /**
     * Get imagenaux
     *
     * @return string
     */
    public function getImagenaux()
    {
        return $this->imagenaux;
    }

    /**
     * @param File $imageaux
     */
    public function setImageauxFile(File $imageaux = null)
    {
        $this->imageauxFile = $imageaux;
        if ($imageaux) {
            $this->updatedAt = new \DateTime('now');
        }
    }

    /**
     * @return File
     */
    public function getImageauxFile()
    {
        return $this->imageauxFile;
    }

    

    /**
     * Set modelo
     *
     * @param \CarroiridianBundle\Entity\Modelo $modelo
     *
     * @return Producto
     */
    public function setModelo(\CarroiridianBundle\Entity\Modelo $modelo = null)
    {
        $this->modelo = $modelo;

        return $this;
    }

    /**
     * Get modelo
     *
     * @return \CarroiridianBundle\Entity\Modelo
     */
    public function getModelo()
    {
        return $this->modelo;
    }


    /**
     * Add genero
     *
     * @param \CarroiridianBundle\Entity\Genero $genero
     *
     * @return Producto
     */
    public function addGenero(\CarroiridianBundle\Entity\Genero $genero)
    {
        $this->generos[] = $genero;

        return $this;
    }

    /**
     * Remove genero
     *
     * @param \CarroiridianBundle\Entity\Genero $genero
     */
    public function removeGenero(\CarroiridianBundle\Entity\Genero $genero)
    {
        $this->generos->removeElement($genero);
    }

    /**
     * Get generos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getGeneros()
    {
        return $this->generos;
    }

    /**
     * Set updatedAt.
     *
     * @param \DateTime $updatedAt
     *
     * @return Producto
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    
        return $this;
    }

    /**
     * Get updatedAt.
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set resumenEs
     *
     * @param string $resumenEs
     *
     * @return Producto
     */
    public function setResumenEs($resumenEs)
    {
        $this->resumenEs = $resumenEs;
    
        return $this;
    }

    /**
     * Get resumenEs
     *
     * @return string
     */
    public function getResumenEs()
    {
        return $this->resumenEs;
    }

    /**
     * Set resumenEn
     *
     * @param string $resumenEn
     *
     * @return Producto
     */
    public function setResumenEn($resumenEn)
    {
        $this->resumenEn = $resumenEn;
    
        return $this;
    }

    /**
     * Get resumenEn
     *
     * @return string
     */
    public function getResumenEn()
    {
        return $this->resumenEn;
    }

    /**
     * Set ingredientesEs
     *
     * @param string $ingredientesEs
     *
     * @return Producto
     */
    public function setIngredientesEs($ingredientesEs)
    {
        $this->ingredientesEs = $ingredientesEs;
    
        return $this;
    }

    /**
     * Get ingredientesEs
     *
     * @return string
     */
    public function getIngredientesEs()
    {
        return $this->ingredientesEs;
    }

    /**
     * Set ingredientesEn
     *
     * @param string $ingredientesEn
     *
     * @return Producto
     */
    public function setIngredientesEn($ingredientesEn)
    {
        $this->ingredientesEn = $ingredientesEn;
    
        return $this;
    }

    /**
     * Get ingredientesEn
     *
     * @return string
     */
    public function getIngredientesEn()
    {
        return $this->ingredientesEn;
    }

    /**
     * Set beneficiosEs
     *
     * @param string $beneficiosEs
     *
     * @return Producto
     */
    public function setBeneficiosEs($beneficiosEs)
    {
        $this->beneficiosEs = $beneficiosEs;
    
        return $this;
    }

    /**
     * Get beneficiosEs
     *
     * @return string
     */
    public function getBeneficiosEs()
    {
        return $this->beneficiosEs;
    }

    /**
     * Set beneficiosEn
     *
     * @param string $beneficiosEn
     *
     * @return Producto
     */
    public function setBeneficiosEn($beneficiosEn)
    {
        $this->beneficiosEn = $beneficiosEn;
    
        return $this;
    }

    /**
     * Get beneficiosEn
     *
     * @return string
     */
    public function getBeneficiosEn()
    {
        return $this->beneficiosEn;
    }

    /**
     * Set modoEs
     *
     * @param string $modoEs
     *
     * @return Producto
     */
    public function setModoEs($modoEs)
    {
        $this->modoEs = $modoEs;
    
        return $this;
    }

    /**
     * Get modoEs
     *
     * @return string
     */
    public function getModoEs()
    {
        return $this->modoEs;
    }

    /**
     * Set modoEn
     *
     * @param string $modoEn
     *
     * @return Producto
     */
    public function setModoEn($modoEn)
    {
        $this->modoEn = $modoEn;
    
        return $this;
    }

    /**
     * Get modoEn
     *
     * @return string
     */
    public function getModoEn()
    {
        return $this->modoEn;
    }

    /**
     * Add ingrediente
     *
     * @param \HomeBundle\Entity\Ingrediente $ingrediente
     *
     * @return Producto
     */
    public function addIngrediente(\HomeBundle\Entity\Ingrediente $ingrediente)
    {
        $this->ingredientes[] = $ingrediente;
    
        return $this;
    }

    /**
     * Remove ingrediente
     *
     * @param \HomeBundle\Entity\Ingrediente $ingrediente
     */
    public function removeIngrediente(\HomeBundle\Entity\Ingrediente $ingrediente)
    {
        $this->ingredientes->removeElement($ingrediente);
    }

    /**
     * Get ingredientes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getIngredientes()
    {
        return $this->ingredientes;
    }

    /**
     * Set vendido
     *
     * @param boolean $vendido
     *
     * @return Producto
     */
    public function setVendido($vendido)
    {
        $this->vendido = $vendido;
    
        return $this;
    }

    /**
     * Get vendido
     *
     * @return boolean
     */
    public function getVendido()
    {
        return $this->vendido;
    }

    /**
     * Set subcategoria
     *
     * @param \CarroiridianBundle\Entity\SubCategoria $subcategoria
     *
     * @return Producto
     */
    public function setSubcategoria(\CarroiridianBundle\Entity\SubCategoria $subcategoria = null)
    {
        $this->subcategoria = $subcategoria;
    
        return $this;
    }

    /**
     * Get subcategoria
     *
     * @return \CarroiridianBundle\Entity\SubCategoria
     */
    public function getSubcategoria()
    {
        return $this->subcategoria;
    }

    /**
     * Set caracteristicasEs
     *
     * @param string $caracteristicasEs
     *
     * @return Producto
     */
    public function setCaracteristicasEs($caracteristicasEs)
    {
        $this->caracteristicasEs = $caracteristicasEs;
    
        return $this;
    }

    /**
     * Get caracteristicasEs
     *
     * @return string
     */
    public function getCaracteristicasEs()
    {
        return $this->caracteristicasEs;
    }

    /**
     * Set caracteristicasEn
     *
     * @param string $caracteristicasEn
     *
     * @return Producto
     */
    public function setCaracteristicasEn($caracteristicasEn)
    {
        $this->caracteristicasEn = $caracteristicasEn;
    
        return $this;
    }

    /**
     * Get caracteristicasEn
     *
     * @return string
     */
    public function getCaracteristicasEn()
    {
        return $this->caracteristicasEn;
    }

    /**
     * Add uso
     *
     * @param \CarroiridianBundle\Entity\Uso $uso
     *
     * @return Producto
     */
    public function addUso(\CarroiridianBundle\Entity\Uso $uso)
    {
        $this->usos[] = $uso;
    
        return $this;
    }

    /**
     * Remove uso
     *
     * @param \CarroiridianBundle\Entity\Uso $uso
     */
    public function removeUso(\CarroiridianBundle\Entity\Uso $uso)
    {
        $this->usos->removeElement($uso);
    }

    /**
     * Get usos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsos()
    {
        return $this->usos;
    }

    /**
     * Add filtro
     *
     * @param \CarroiridianBundle\Entity\Filtro $filtro
     *
     * @return Producto
     */
    public function addFiltro(\CarroiridianBundle\Entity\Filtro $filtro)
    {
        $this->filtros[] = $filtro;
    
        return $this;
    }

    /**
     * Remove filtro
     *
     * @param \CarroiridianBundle\Entity\Filtro $filtro
     */
    public function removeFiltro(\CarroiridianBundle\Entity\Filtro $filtro)
    {
        $this->filtros->removeElement($filtro);
    }

    /**
     * Get filtros
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFiltros()
    {
        return $this->filtros;
    }

    /**
     * Set marca
     *
     * @param \CarroiridianBundle\Entity\Marca $marca
     *
     * @return Producto
     */
    public function setMarca(\CarroiridianBundle\Entity\Marca $marca = null)
    {
        $this->marca = $marca;
    
        return $this;
    }

    /**
     * Get marca
     *
     * @return \CarroiridianBundle\Entity\Marca
     */
    public function getMarca()
    {
        return $this->marca;
    }

    public function getCantidad()
    {
        return $this->marca;
    }

    /**
     * Add relacionado
     *
     * @param \CarroiridianBundle\Entity\Producto $relacionado
     *
     * @return Producto
     */
    public function addRelacionado(\CarroiridianBundle\Entity\Producto $relacionado)
    {
        $this->relacionados[] = $relacionado;
    
        return $this;
    }

    /**
     * Remove relacionado
     *
     * @param \CarroiridianBundle\Entity\Producto $relacionado
     */
    public function removeRelacionado(\CarroiridianBundle\Entity\Producto $relacionado)
    {
        $this->relacionados->removeElement($relacionado);
    }

    /**
     * Get relacionados
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRelacionados()
    {
        return $this->relacionados;
    }

    /**
     * Set garantiaEs
     *
     * @param string $garantiaEs
     *
     * @return Producto
     */
    public function setGarantiaEs($garantiaEs)
    {
        $this->garantiaEs = $garantiaEs;
    
        return $this;
    }

    /**
     * Get garantiaEs
     *
     * @return string
     */
    public function getGarantiaEs()
    {
        return $this->garantiaEs;
    }

    /**
     * Set garantiaEn
     *
     * @param string $garantiaEn
     *
     * @return Producto
     */
    public function setGarantiaEn($garantiaEn)
    {
        $this->garantiaEn = $garantiaEn;
    
        return $this;
    }

    /**
     * Get garantiaEn
     *
     * @return string
     */
    public function getGarantiaEn()
    {
        return $this->garantiaEn;
    }
}
