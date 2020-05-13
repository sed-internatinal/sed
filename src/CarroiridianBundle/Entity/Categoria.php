<?php

namespace CarroiridianBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;

/**
 * Categoria
 *
 * @ORM\Table(name="categoria")
 * @ORM\Entity(repositoryClass="CarroiridianBundle\Repository\CategoriaRepository")
 * @Vich\Uploadable
 */
class Categoria
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
     * @ORM\Column(name="imagen", type="string", length=255,nullable=true)
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
     * @var string
     *
     * @ORM\Column(name="imagenmovil", type="string", length=255, nullable=true)
     */
    private $imagenmovil;

    /**
     * @Vich\UploadableField(mapping="productos", fileNameProperty="imagenmovil")
     * @var File
     */
    private $imagemovilFile;

    /**
     * @var string
     *
     * @ORM\Column(name="imagentextoen", type="string", length=255, nullable=true)
     */
    private $imagentextoen;

    /**
     * @Vich\UploadableField(mapping="productos", fileNameProperty="imagentextoen")
     * @var File
     */
    private $imagetextoenFile;

    /**
     * @var string
     *
     * @ORM\Column(name="imagentextoes", type="string", length=255, nullable=true)
     */
    private $imagentextoes;

    /**
     * @Vich\UploadableField(mapping="productos", fileNameProperty="imagentextoes")
     * @var File
     */
    private $imagetextoesFile;


    /**
     * @var string
     *
     * @ORM\Column(name="alt", type="string", length=255)
     */
    private $alt;

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
    }


    public function gen($campo,$locale){
        $accessor = PropertyAccess::createPropertyAccessor();
        return $accessor->getValue($this,$campo.'_'.$locale);
    }

    public function __toString()
    {
        return $this->nombreEs.' ';

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
     * Set nombreEs
     *
     * @param string $nombreEs
     *
     * @return Categoria
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
     * @return Categoria
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
     * Set orden
     *
     * @param integer $orden
     *
     * @return Categoria
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
     * @return Categoria
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
     * Set imagen
     *
     * @param string $imagen
     *
     * @return Categoria
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
     * Set imagenaux
     *
     * @param string $imagenaux
     *
     * @return Categoria
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
     * Set imagentexto
     *
     * @param string $imagentextoen
     *
     * @return Categoria
     */
    public function setImagentextoen($imagentextoen)
    {
        $this->imagentextoen = $imagentextoen;

        return $this;
    }

    /**
     * Get imagentextoen
     *
     * @return string
     */
    public function getImagentextoen()
    {
        return $this->imagentextoen;
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
     * @param File $image
     */
    public function setImagetextoenFile(File $image = null)
    {
        $this->imagetextoenFile = $image;
        if ($image) {
            $this->updatedAt = new \DateTime('now');
        }
    }

    /**
     * @return File
     */
    public function getImagetextoenFile()
    {
        return $this->imagetextoenFile;
    }

    /**
     * @param File $image
     */
    public function setImagetextoesFile(File $image = null)
    {
        $this->imagetextoesFile = $image;
        if ($image) {
            $this->updatedAt = new \DateTime('now');
        }
    }

    /**
     * @return File
     */
    public function getImagetextoesFile()
    {
        return $this->imagetextoesFile;
    }

    /**
     * Set alt
     *
     * @param string $alt
     *
     * @return Categoria
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
     * Set imagenmovil
     *
     * @param string $imagenmovil
     *
     * @return Categoria
     */
    public function setImagenmovil($imagenmovil)
    {
        $this->imagenmovil = $imagenmovil;

        return $this;
    }

    /**
     * Get imagenmovil
     *
     * @return string
     */
    public function getImagenmovil()
    {
        return $this->imagenmovil;
    }

    /**
     * @param File $image
     */
    public function setImagemovilFile(File $image = null)
    {
        $this->imagemovilFile = $image;
        if ($image) {
            $this->updatedAt = new \DateTime('now');
        }
    }

    /**
     * @return File
     */
    public function getImagemovilFile()
    {
        return $this->imagemovilFile;
    }

    /**
     * Set imagentextoes
     *
     * @param string $imagentextoes
     *
     * @return Categoria
     */
    public function setImagentextoes($imagentextoes)
    {
        $this->imagentextoes = $imagentextoes;

        return $this;
    }

    /**
     * Get imagentextoes
     *
     * @return string
     */
    public function getImagentextoes()
    {
        return $this->imagentextoes;
    }

    /**
     * Set updatedAt.
     *
     * @param \DateTime $updatedAt
     *
     * @return Categoria
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
}
