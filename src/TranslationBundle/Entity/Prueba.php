<?php

namespace TranslationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Validator\Constraints as campoAssert;

/**
 * Prueba
 *
 * @ORM\Table(name="prueba")
 * @ORM\Entity(repositoryClass="TranslationBundle\Repository\PruebaRepository")
 *
 */
class Prueba
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
     * @var int
     *
     * @ORM\Column(name="orden", type="integer")
     */
    private $orden;

    /**
     * @ORM\ManyToOne(targetEntity="TranslationBundle\Entity\Campo", fetch="EAGER",cascade={"remove"})
     * @ORM\JoinColumn(name="nombre_id", referencedColumnName="id")
     *
     */
    private $nombre;

    /**
     * @ORM\ManyToOne(targetEntity="TranslationBundle\Entity\Campo", fetch="EAGER",cascade={"remove"})
     * @ORM\JoinColumn(name="marca_id", referencedColumnName="id", nullable=true)
     */
    private $marca;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set orden.
     *
     * @param int $orden
     *
     * @return Prueba
     */
    public function setOrden($orden)
    {
        $this->orden = $orden;
    
        return $this;
    }

    /**
     * Get orden.
     *
     * @return int
     */
    public function getOrden()
    {
        return $this->orden;
    }

    /**
     * Set nombre.
     *
     * @param \TranslationBundle\Entity\Campo|null $nombre
     *
     * @return Prueba
     */
    public function setNombre(\TranslationBundle\Entity\Campo $nombre = null)
    {
        $this->nombre = $nombre;
    
        return $this;
    }

    /**
     * Get nombre.
     *
     * @return \TranslationBundle\Entity\Campo|null
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set marca.
     *
     * @param \TranslationBundle\Entity\Campo|null $marca
     *
     * @return Prueba
     */
    public function setMarca(\TranslationBundle\Entity\Campo $marca = null)
    {
        $this->marca = $marca;
    
        return $this;
    }

    /**
     * Get marca.
     *
     * @return \TranslationBundle\Entity\Campo|null
     */
    public function getMarca()
    {
        return $this->marca;
    }
}
