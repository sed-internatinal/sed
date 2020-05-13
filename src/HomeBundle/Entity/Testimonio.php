<?php

namespace HomeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\PropertyAccess\PropertyAccess;

/**
 * Testimonio
 *
 * @ORM\Table(name="testimonio")
 * @ORM\Entity(repositoryClass="HomeBundle\Repository\TestimonioRepository")
 */
class Testimonio
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
     * @ORM\ManyToOne(targetEntity="CarroiridianBundle\Entity\Producto")
     * @ORM\JoinColumn(name="producto_id", referencedColumnName="id")
     */
    private $producto;

    /**
     * @var string
     *
     * @ORM\Column(name="resumen_es", type="text")
     */
    private $resumenEs;

    /**
     * @var string
     *
     * @ORM\Column(name="resumen_en", type="text", nullable=true)
     */
    private $resumenEn;

    /**
     * @var string
     *
     * @ORM\Column(name="profesion_es", type="string", length=255)
     */
    private $profesionEs;

    /**
     * @var string
     *
     * @ORM\Column(name="profesion_en", type="string", length=255, nullable=true)
     */
    private $profesionEn;

    /**
     * @var boolean
     *
     * @ORM\Column(name="visible", type="boolean")
     */
    private $visible = true;


    /**
     * @var integer
     *
     * @ORM\Column(name="orden", type="integer")
     */
    private $orden = 1;



    public function gen($campo,$locale){
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
     * Set resumenEs
     *
     * @param string $resumenEs
     *
     * @return Testimonio
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
     * @return Testimonio
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
     * Set profesionEs
     *
     * @param string $profesionEs
     *
     * @return Testimonio
     */
    public function setProfesionEs($profesionEs)
    {
        $this->profesionEs = $profesionEs;
    
        return $this;
    }

    /**
     * Get profesionEs
     *
     * @return string
     */
    public function getProfesionEs()
    {
        return $this->profesionEs;
    }

    /**
     * Set profesionEn
     *
     * @param string $profesionEn
     *
     * @return Testimonio
     */
    public function setProfesionEn($profesionEn)
    {
        $this->profesionEn = $profesionEn;
    
        return $this;
    }

    /**
     * Get profesionEn
     *
     * @return string
     */
    public function getProfesionEn()
    {
        return $this->profesionEn;
    }

    /**
     * Set visible
     *
     * @param boolean $visible
     *
     * @return Testimonio
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
     * @return Testimonio
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
     * Set producto
     *
     * @param \CarroiridianBundle\Entity\Producto $producto
     *
     * @return Testimonio
     */
    public function setProducto(\CarroiridianBundle\Entity\Producto $producto = null)
    {
        $this->producto = $producto;
    
        return $this;
    }

    /**
     * Get producto
     *
     * @return \CarroiridianBundle\Entity\Producto
     */
    public function getProducto()
    {
        return $this->producto;
    }

    /**
     * Set orden
     *
     * @param integer $orden
     *
     * @return Testimonio
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
}
