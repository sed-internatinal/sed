<?php

namespace CarroiridianBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\PropertyAccess\PropertyAccess;

/**
 * unidadnegocio
 *
 * @ORM\Table(name="unidadnegocio")
 * @ORM\Entity(repositoryClass="CarroiridianBundle\Repository\unidadnegocioRepository")
 * @Vich\Uploadable
 */
class unidadnegocio
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
     * @ORM\Column(name="nombreEs", type="string", length=255)
     */
    private $nombreEs;

    /**
     * @var string
     *
     * @ORM\Column(name="nombreEn", type="string", length=255, nullable=true)
     */
    private $nombreEn;

    /**
     * @var string
     *
     * @ORM\Column(name="iconoEs", type="string", length=255, nullable=true)
     */
    private $iconoEs;

    /**
     * @var string
     *
     * @ORM\Column(name="iconoEn", type="string", length=255, nullable=true)
     */
    private $iconoEn;

    /**
     * @Vich\UploadableField(mapping="productos", fileNameProperty="iconoEs")
     * @var File
     */
    private $iconoEsFile;

    /**
     * @Vich\UploadableField(mapping="productos", fileNameProperty="iconoEn")
     * @var File
     */
    private $iconoEnFile;

    /**
     * @var string
     *
     * @ORM\Column(name="imagenEs", type="string", length=255, nullable=true)
     */
    private $imagenEs;

    /**
     * @var string
     *
     * @ORM\Column(name="imagenEn", type="string", length=255, nullable=true)
     */
    private $imagenEn;

    /**
     * @Vich\UploadableField(mapping="productos", fileNameProperty="imagenEs")
     * @var File
     */
    private $imagenEsFile;

    /**
     * @Vich\UploadableField(mapping="productos", fileNameProperty="imagenEn")
     * @var File
     */
    private $imagenEnFile;

    /**
     * @var string
     *
     * @ORM\Column(name="descripcionEs", type="text")
     */
    private $descripcionEs;

    /**
     * @var string
     *
     * @ORM\Column(name="descripcionEn", type="text", nullable=true)
     */
    private $descripcionEn;

    /**
     * @var string
     *
     * @ORM\Column(name="galeria", type="string", length=255)
     */
    private $galeria;

    /**
     * @var Color[]
     * @ORM\ManyToMany(targetEntity="CarroiridianBundle\Entity\Opcionunidad")
     */
    protected $opciones;

    /**
     * @ORM\ManyToMany(targetEntity="CarroiridianBundle\Entity\Marca", mappedBy="unidades")
     */
    private $marcas;

    /**
     * @ORM\Column(type="datetime")
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->opciones = new \Doctrine\Common\Collections\ArrayCollection();
        $this->updatedAt = new \DateTime();
    }

    public function gen($campo,$locale){
        $accessor = PropertyAccess::createPropertyAccessor();
        return $accessor->getValue($this,$campo.'_'.$locale);
    }

    public function __toString()
    {
        return $this->nombreEs;
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
     * Set nombreEs
     *
     * @param string $nombreEs
     *
     * @return unidadnegocio
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
     * @return unidadnegocio
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
     * Set icono
     *
     * @param string $icono
     *
     * @return unidadnegocio
     */
    public function setIconoEs($icono)
    {
        $this->iconoEs = $icono;
    
        return $this;
    }

    /**
     * Get icono
     *
     * @return string
     */
    public function getIconoEs()
    {
        return $this->iconoEs;
    }

    /**
     * Set icono
     *
     * @param string $icono
     *
     * @return unidadnegocio
     */
    public function setIconoEn($icono)
    {
        $this->iconoEn = $icono;

        return $this;
    }

    /**
     * Get icono
     *
     * @return string
     */
    public function getIconoEn()
    {
        return $this->iconoEn;
    }

    /**
     * Set descripcionEs
     *
     * @param string $descripcionEs
     *
     * @return unidadnegocio
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
     * @return unidadnegocio
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
     * Set galeria
     *
     * @param string $galeria
     *
     * @return unidadnegocio
     */
    public function setGaleria($galeria)
    {
        $this->galeria = $galeria;
    
        return $this;
    }

    /**
     * Get galeria
     *
     * @return string
     */
    public function getGaleria()
    {
        return $this->galeria;
    }


    /**
     * Add opcione
     *
     * @param \CarroiridianBundle\Entity\Opcionunidad $opcione
     *
     * @return unidadnegocio
     */
    public function addOpcione(\CarroiridianBundle\Entity\Opcionunidad $opcione)
    {
        $this->opciones[] = $opcione;
    
        return $this;
    }

    /**
     * Remove opcione
     *
     * @param \CarroiridianBundle\Entity\Opcionunidad $opcione
     */
    public function removeOpcione(\CarroiridianBundle\Entity\Opcionunidad $opcione)
    {
        $this->opciones->removeElement($opcione);
    }

    /**
     * Get opciones
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOpciones()
    {
        return $this->opciones;
    }

    /**
     * @param File $image
     */
    public function setIconoEsFile(File $image = null)
    {
        $this->iconoEsFile = $image;
        if ($image) {
            $this->updatedAt = new \DateTime('now');
        }
    }

    /**
     * @return File
     */
    public function getIconoEsFile()
    {
        return $this->iconoEsFile;
    }

    /**
     * @param File $image
     */
    public function setIconoEnFile(File $image = null)
    {
        $this->iconoEnFile = $image;
        if ($image) {
            $this->updatedAt = new \DateTime('now');
        }
    }

    /**
     * @return File
     */
    public function getIconoEnFile()
    {
        return $this->iconoEnFile;
    }

    /**
     * @return File
     */
    public function getImagenEsFile()
    {
        return $this->imagenEsFile;
    }

    /**
     * @param File $image
     */
    public function setImagenEsFile(File $image = null)
    {
        $this->imagenEsFile = $image;
        if ($image) {
            $this->updatedAt = new \DateTime('now');
        }
    }

    /**
     * @return File
     */
    public function getImagenEnFile()
    {
        return $this->imagenEnFile;
    }

    /**
     * @param File $image
     */
    public function setImagenEnFile(File $image = null)
    {
        $this->imagenEnFile = $image;
        if ($image) {
            $this->updatedAt = new \DateTime('now');
        }
    }


    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return unidadnegocio
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

    /**
     * Set imagen
     *
     * @param string $imagen
     *
     * @return unidadnegocio
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
     * Add marca
     *
     * @param \CarroiridianBundle\Entity\Marca $marca
     *
     * @return unidadnegocio
     */
    public function addMarca(\CarroiridianBundle\Entity\Marca $marca)
    {
        $this->marcas[] = $marca;
    
        return $this;
    }

    /**
     * Remove marca
     *
     * @param \CarroiridianBundle\Entity\Marca $marca
     */
    public function removeMarca(\CarroiridianBundle\Entity\Marca $marca)
    {
        $this->marcas->removeElement($marca);
    }

    /**
     * Get marcas
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMarcas()
    {
        return $this->marcas;
    }

    /**
     * Set imagenEs
     *
     * @param string $imagenEs
     *
     * @return unidadnegocio
     */
    public function setImagenEs($imagenEs)
    {
        $this->imagenEs = $imagenEs;
    
        return $this;
    }

    /**
     * Get imagenEs
     *
     * @return string
     */
    public function getImagenEs()
    {
        return $this->imagenEs;
    }

    /**
     * Set imagenEn
     *
     * @param string $imagenEn
     *
     * @return unidadnegocio
     */
    public function setImagenEn($imagenEn)
    {
        $this->imagenEn = $imagenEn;
    
        return $this;
    }

    /**
     * Get imagenEn
     *
     * @return string
     */
    public function getImagenEn()
    {
        return $this->imagenEn;
    }
}
