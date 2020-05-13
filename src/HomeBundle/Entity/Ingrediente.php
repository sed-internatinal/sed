<?php

namespace HomeBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * Ingrediente
 *
 * @ORM\Table(name="ingrediente")
 * @ORM\Entity(repositoryClass="HomeBundle\Repository\IngredienteRepository")
 * @Vich\Uploadable
 */
class Ingrediente
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
     * Many Users have Many Groups.
     * @ORM\ManyToMany(targetEntity="CarroiridianBundle\Entity\Producto", inversedBy="ingredientes")
     * @ORM\JoinTable(name="producto_ingrediente")
     */
    private $productos;

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
     * @ORM\Column(name="latin", type="string", length=255, nullable=true)
     */
    private $latin;

    /**
     * @var string
     *
     * @ORM\Column(name="left_es", type="string", length=255)
     */
    private $leftEs;

    /**
     * @var string
     *
     * @ORM\Column(name="left_en", type="string", length=255, nullable=true)
     */
    private $leftEn;

    /**
     * @var string
     *
     * @ORM\Column(name="right_es", type="string", length=255)
     */
    private $rightEs;

    /**
     * @var string
     *
     * @ORM\Column(name="right_en", type="string", length=255, nullable=true)
     */
    private $rightEn;

    /**
     * @var string
     *
     * @ORM\Column(name="image_es", type="string", length=255)
     */
    private $imageEs;

    /**
     * @Vich\UploadableField(mapping="productos", fileNameProperty="imageEs" )
     * @var File
     */
    private $imageEsFile;

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



    public function __construct()
    {
        $this->updatedAt = new \DateTime();
        $this->productos = new ArrayCollection();
    }


    public function __toString()
    {
        return $this->nombreEs.' ';
    }

    /**
     * @param $campo
     * @param $locale
     * @return mixed
     */
    public function gen($campo, $locale){
        $accessor = PropertyAccess::createPropertyAccessor();
        return $accessor->getValue($this,$campo.'_'.$locale);
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set imageEsFile
     *
     * @param string $imageEsFile
     *
     * @return Imagengaleria
     */
    public function setImageEsFile(File $imageEs = null)
    {
        $this->imageEsFile = $imageEs;
        if ($imageEs) {
            $this->updatedAt = new \DateTime('now');
        }
    }

    /**
     * Get imageEsFile
     *
     * @return string
     */
    public function getImageEsFile()
    {
        return $this->imageEsFile;
    }

    /**
     * Set nombreEs
     *
     * @param string $nombreEs
     *
     * @return Ingrediente
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
     * @return Ingrediente
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
     * Set imageEs
     *
     * @param string $imageEs
     *
     * @return Ingrediente
     */
    public function setImageEs($imageEs)
    {
        $this->imageEs = $imageEs;
    
        return $this;
    }

    /**
     * Get imageEs
     *
     * @return string
     */
    public function getImageEs()
    {
        return $this->imageEs;
    }

    /**
     * Set orden
     *
     * @param integer $orden
     *
     * @return Ingrediente
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
     * @return Ingrediente
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
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return Ingrediente
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    
        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    public function firstLetters(){
        $cad = $this->nombreEs[0].' ';
        if($this->nombreEn != null)
            if($this->nombreEs[0] != $this->nombreEn[0])
                $cad .= $this->nombreEn[0];
        return $cad;
    }

    /**
     * Set latin
     *
     * @param string $latin
     *
     * @return Ingrediente
     */
    public function setLatin($latin)
    {
        $this->latin = $latin;
    
        return $this;
    }

    /**
     * Get latin
     *
     * @return string
     */
    public function getLatin()
    {
        return $this->latin;
    }

    /**
     * Set leftEs
     *
     * @param string $leftEs
     *
     * @return Ingrediente
     */
    public function setLeftEs($leftEs)
    {
        $this->leftEs = $leftEs;
    
        return $this;
    }

    /**
     * Get leftEs
     *
     * @return string
     */
    public function getLeftEs()
    {
        return $this->leftEs;
    }

    /**
     * Set leftEn
     *
     * @param string $leftEn
     *
     * @return Ingrediente
     */
    public function setLeftEn($leftEn)
    {
        $this->leftEn = $leftEn;
    
        return $this;
    }

    /**
     * Get leftEn
     *
     * @return string
     */
    public function getLeftEn()
    {
        return $this->leftEn;
    }

    /**
     * Set rightEs
     *
     * @param string $rightEs
     *
     * @return Ingrediente
     */
    public function setRightEs($rightEs)
    {
        $this->rightEs = $rightEs;
    
        return $this;
    }

    /**
     * Get rightEs
     *
     * @return string
     */
    public function getRightEs()
    {
        return $this->rightEs;
    }

    /**
     * Set rightEn
     *
     * @param string $rightEn
     *
     * @return Ingrediente
     */
    public function setRightEn($rightEn)
    {
        $this->rightEn = $rightEn;
    
        return $this;
    }

    /**
     * Get rightEn
     *
     * @return string
     */
    public function getRightEn()
    {
        return $this->rightEn;
    }


    /**
     * Add producto
     *
     * @param \CarroiridianBundle\Entity\Producto $producto
     *
     * @return Ingrediente
     */
    public function addProducto(\CarroiridianBundle\Entity\Producto $producto)
    {
        $this->productos[] = $producto;
    
        return $this;
    }

    /**
     * Remove producto
     *
     * @param \CarroiridianBundle\Entity\Producto $producto
     */
    public function removeProducto(\CarroiridianBundle\Entity\Producto $producto)
    {
        $this->productos->removeElement($producto);
    }

    /**
     * Get productos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProductos()
    {
        return $this->productos;
    }
}
