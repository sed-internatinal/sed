<?php

namespace CarroiridianBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Envio
 *
 * @ORM\Table(name="envio")
 * @ORM\Entity(repositoryClass="CarroiridianBundle\Repository\EnvioRepository")
 */
class Envio
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
     * @ORM\ManyToOne(targetEntity="CarroiridianBundle\Entity\Departamento")
     * @ORM\JoinColumn(name="departamento_id", referencedColumnName="id")
     */
    private $departamento;

    /**
     * @ORM\ManyToOne(targetEntity="CarroiridianBundle\Entity\Ciudad")
     * @ORM\JoinColumn(name="ciudad_id", referencedColumnName="id")
     * @Assert\NotBlank(message = "La ciudad no puede ser vacia")
     */
    private $ciudad;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;
    
    /**
     * @var string
     *
     * @ORM\Column(name="direccion", type="string", length=255)
     * @Assert\NotBlank(message = "La dirección no puede ser vacia")
     */
    private $direccion;

    /**
     * @var string
     *
     * @ORM\Column(name="adicionales", type="text", nullable=true)
     */
    private $adicionales;



    public function __toString()
    {
        return $this->direccion.' ';
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
     * Set direccion
     *
     * @param string $direccion
     *
     * @return Envio
     */
    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;
    
        return $this;
    }

    /**
     * Get direccion
     *
     * @return string
     */
    public function getDireccion()
    {
        return $this->direccion;
    }

    /**
     * Set ciudad
     *
     * @param \CarroiridianBundle\Entity\Ciudad $ciudad
     *
     * @return Envio
     */
    public function setCiudad(\CarroiridianBundle\Entity\Ciudad $ciudad = null)
    {
        $this->ciudad = $ciudad;
    
        return $this;
    }

    /**
     * Get ciudad
     *
     * @return \CarroiridianBundle\Entity\Ciudad
     */
    public function getCiudad()
    {
        return $this->ciudad;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Envio
     */
    public function setUser(\AppBundle\Entity\User $user = null)
    {
        $this->user = $user;
    
        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set adicionales
     *
     * @param string $adicionales
     *
     * @return Envio
     */
    public function setAdicionales($adicionales)
    {
        $this->adicionales = $adicionales;
    
        return $this;
    }

    /**
     * Get adicionales
     *
     * @return string
     */
    public function getAdicionales()
    {
        return $this->adicionales;
    }

    /**
     * Set departamento
     *
     * @param \CarroiridianBundle\Entity\Departamento $departamento
     *
     * @return Envio
     */
    public function setDepartamento(\CarroiridianBundle\Entity\Departamento $departamento = null)
    {
        $this->departamento = $departamento;
    
        return $this;
    }

    /**
     * Get departamento
     *
     * @return \CarroiridianBundle\Entity\Departamento
     */
    public function getDepartamento()
    {
        return $this->departamento;
    }
}
