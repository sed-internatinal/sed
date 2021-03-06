<?php

namespace CarroiridianBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Inventario
 *
 * @ORM\Table(name="inventario")
 * @ORM\Entity(repositoryClass="CarroiridianBundle\Repository\InventarioRepository")
 */
class Inventario
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
     * @ORM\ManyToOne(targetEntity="CarroiridianBundle\Entity\Talla")
     * @ORM\JoinColumn(name="talla_id", referencedColumnName="id")
     */
    private $talla;



    /**
     * @var int
     *
     * @ORM\Column(name="cantidad", type="integer")
     */
    private $cantidad;

    public function __toString()
    {
        return $this->cantidad.' | '.$this->getProducto()->getNombreEs().' - '.$this->getTalla();
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
     * Set cantidad
     *
     * @param integer $cantidad
     *
     * @return Inventario
     */
    public function setCantidad($cantidad)
    {
        $this->cantidad = $cantidad;

        return $this;
    }

    /**
     * Get cantidad
     *
     * @return int
     */
    public function getCantidad()
    {
        return $this->cantidad;
    }


    /**
     * Set producto
     *
     * @param \CarroiridianBundle\Entity\Producto $producto
     *
     * @return Inventario
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
     * Set talla
     *
     * @param \CarroiridianBundle\Entity\Talla $talla
     *
     * @return Inventario
     */
    public function setTalla(\CarroiridianBundle\Entity\Talla $talla = null)
    {
        $this->talla = $talla;
    
        return $this;
    }

    /**
     * Get talla
     *
     * @return \CarroiridianBundle\Entity\Talla
     */
    public function getTalla()
    {
        return $this->talla;
    }
}
