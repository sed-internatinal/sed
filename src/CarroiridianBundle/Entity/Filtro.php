<?php

namespace CarroiridianBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Filtro
 *
 * @ORM\Table(name="filtro")
 * @ORM\Entity(repositoryClass="CarroiridianBundle\Repository\FiltroRepository")
 */
class Filtro
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
     * @ORM\Column(name="nombre_en", type="string", length=255,nullable=true)
     */
    private $nombreEn;

    /**
     * @ORM\ManyToOne(targetEntity="CarroiridianBundle\Entity\TipoFiltro")
     * @ORM\JoinColumn(name="filtro_id", referencedColumnName="id")
     */
    private $filtro;

    /**
     * @var integer
     *
     * @ORM\Column(name="orden", type="integer")
     */
    private $orden = 1;




    public function __toString()
    {
        if($this->getFiltro() != null)
            return strtoupper($this->getFiltro()->getNombreEs()).' - '.$this->getNombreEs().' ';
        else
            return $this->getNombreEs().' ';
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
     * Set nombre
     *
     * @param string $nombre
     *
     * @return Filtro
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    
        return $this;
    }

    /**
     * Get nombre
     *
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set filtro
     *
     * @param \CarroiridianBundle\Entity\TipoFiltro $filtro
     *
     * @return Filtro
     */
    public function setFiltro(\CarroiridianBundle\Entity\TipoFiltro $filtro = null)
    {
        $this->filtro = $filtro;
    
        return $this;
    }

    /**
     * Get filtro
     *
     * @return \CarroiridianBundle\Entity\TipoFiltro
     */
    public function getFiltro()
    {
        return $this->filtro;
    }

    /**
     * Set orden
     *
     * @param integer $orden
     *
     * @return Filtro
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
     * Set nombreEs
     *
     * @param string $nombreEs
     *
     * @return Filtro
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
     * @return Filtro
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
}
