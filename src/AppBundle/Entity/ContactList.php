<?php
/**
 * Created by PhpStorm.
 * User: Iridian 4
 * Date: 26/07/2016
 * Time: 12:36 PM
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Contact List
 *
 * @ORM\Table(name="contact_list")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ContactListRepository")
 */
class ContactList
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
     * @ORM\Column(name="nombre", type="string", length=255)
     * @Assert\NotBlank(message = "name.not_blank")
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="apellido", type="string", length=255, nullable=true)
     */
    private $apellido;

    /**
     * @var string
     *
     * @ORM\Column(name="asunto", type="string", length=255, nullable=true)
     */
    private $asunto;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     * @Assert\NotBlank(message = "email.not_blank")
     * @Assert\Email(message = "email.not_valid")
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="telefono", type="string", length=255, nullable=true)
     */
    private $telefono;

    /**
     * @var string
     *
     * @ORM\Column(name="ciudad", type="string", length=255, nullable=true)
     */
    private $ciudad;

    /**
     * @var string
     *
     * @ORM\Column(name="mensaje", type="text", nullable=true)
     * @Assert\NotBlank(message = "mensaje.not_blank")
     */
    private $mensaje;

    /**
     * @ORM\Column(name="fecha",type="datetime")
     */
    private $fecha;

    /**
     * @ORM\ManyToOne(targetEntity="ContactoBundle\Entity\Tipoconsulta")
     * @ORM\JoinColumn(name="tipoconsulta_id", referencedColumnName="id")
     */
    private $tipoconsulta;


    public function __construct()
    {
        $this->fecha = new \DateTime();
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
     * @return ContactList
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
     * Set apellido
     *
     * @param string $apellido
     *
     * @return ContactList
     */
    public function setApellido($apellido)
    {
        $this->apellido = $apellido;

        return $this;
    }

    /**
     * Get apellido
     *
     * @return string
     */
    public function getApellido()
    {
        return $this->apellido;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return ContactList
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set telefono
     *
     * @param string $telefono
     *
     * @return ContactList
     */
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;

        return $this;
    }

    /**
     * Get telefono
     *
     * @return string
     */
    public function getTelefono()
    {
        return $this->telefono;
    }

    /**
     * Set pais
     *
     * @param string $pais
     *
     * @return ContactList
     */
    public function setPais($pais)
    {
        $this->pais = $pais;

        return $this;
    }

    /**
     * Get pais
     *
     * @return string
     */
    public function getPais()
    {
        return $this->pais;
    }

    /**
     * Set ciudad
     *
     * @param string $ciudad
     *
     * @return ContactList
     */
    public function setCiudad($ciudad)
    {
        $this->ciudad = $ciudad;

        return $this;
    }

    /**
     * Get ciudad
     *
     * @return string
     */
    public function getCiudad()
    {
        return $this->ciudad;
    }

    /**
     * Set mensaje
     *
     * @param string $mensaje
     *
     * @return ContactList
     */
    public function setMensaje($mensaje)
    {
        $this->mensaje = $mensaje;

        return $this;
    }

    /**
     * Get mensaje
     *
     * @return string
     */
    public function getMensaje()
    {
        return $this->mensaje;
    }


    /**
     * Set asunto
     *
     * @param string $asunto
     *
     * @return ContactList
     */
    public function setAsunto($asunto)
    {
        $this->asunto = $asunto;
    
        return $this;
    }

    /**
     * Get asunto
     *
     * @return string
     */
    public function getAsunto()
    {
        return $this->asunto;
    }

    /**
     * Set fecha.
     *
     * @param \fecha $fecha
     *
     * @return ContactList
     */
    public function setFecha(\DateTime $fecha)
    {
        $this->fecha = $fecha;
    
        return $this;
    }

    /**
     * Get fecha.
     *
     * @return \fecha
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set tipoconsulta
     *
     * @param \ContactoBundle\Entity\Tipoconsulta $tipoconsulta
     *
     * @return ContactList
     */
    public function setTipoconsulta(\ContactoBundle\Entity\Tipoconsulta $tipoconsulta = null)
    {
        $this->tipoconsulta = $tipoconsulta;
    
        return $this;
    }

    /**
     * Get tipoconsulta
     *
     * @return \ContactoBundle\Entity\Tipoconsulta
     */
    public function getTipoconsulta()
    {
        return $this->tipoconsulta;
    }
}
