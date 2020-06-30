<?php

namespace CarroiridianBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TCCLog
 *
 * @ORM\Table(name="tcc_log")
 * @ORM\Entity(repositoryClass="CarroiridianBundle\Repository\TCCLogRepository")
 */
class TCCLog
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
     * @ORM\ManyToOne(targetEntity="CarroiridianBundle\Entity\Compra")
     * @ORM\JoinColumn(name="compra_id", referencedColumnName="id")
     */
    private $compra;

    /**
     * @var string
     *
     * @ORM\Column(name="remesa", type="string", length=100, nullable=true)
     */
    private $remesa;

    /**
     * @var string
     *
     * @ORM\Column(name="numero_recogida", type="string", length=100, nullable=true)
     */
    private $numeroRecogida;

    /**
     * @var string
     *
     * @ORM\Column(name="url_relacion_envio", type="string", length=1000, nullable=true)
     */
    private $urlRelacionEnvio;

    /**
     * @var string
     *
     * @ORM\Column(name="url_remesa", type="string", length=1000, nullable=true)
     */
    private $urlRemesa;

    /**
     * @var string
     *
     * @ORM\Column(name="url_rotulos", type="text", nullable=true)
     */
    private $urlRotulos;

    /**
     * @var string
     *
     * @ORM\Column(name="respuesta", type="string", length=255, nullable=true)
     */
    private $respuesta;

    /**
     * @var string
     *
     * @ORM\Column(name="mensaje", type="text", nullable=true)
     */
    private $mensaje;

    /**
     * @var string
     *
     * @ORM\Column(name="xml_request", type="text", nullable=true)
     */
    private $xmlRequest;

    /**
     * @var string
     *
     * @ORM\Column(name="xml_response", type="text", nullable=true)
     */
    private $xmlResponse;

    /**
     * @var string
     *
     * @ORM\Column(name="xml_request_consulta", type="text", nullable=true)
     */
    private $xmlRequestConsulta;

    /**
     * @var string
     *
     * @ORM\Column(name="xml_response_consulta", type="text", nullable=true)
     */
    private $xmlResponseConsulta;

    /**
     * @ORM\ManyToOne(targetEntity="CarroiridianBundle\Entity\EstadoEnvio")
     * @ORM\JoinColumn(name="estado_envio_id", referencedColumnName="id", nullable=true)
     */
    private $estadoEnvio;


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
     * Set compra
     *
     * @param \CarroiridianBundle\Entity\Compra $compra
     *
     * @return TCCLog
     */
    public function setCompra(\CarroiridianBundle\Entity\Compra $compra = null)
    {
        $this->compra = $compra;
        return $this;
    }

    /**
     * Get compra
     *
     * @return \CarroiridianBundle\Entity\Compra
     */
    public function getCompra()
    {
        return $this->compra;
    }

    /**
     * Set xmlRequest
     *
     * @param string $xmlRequest
     *
     * @return TCCLog
     */
    public function setXmlRequest($xmlRequest)
    {
        $this->xmlRequest = $xmlRequest;

        return $this;
    }

    /**
     * Get xmlRequest
     *
     * @return string
     */
    public function getXmlRequest()
    {
        return $this->xmlRequest;
    }

    /**
     * Set xmlResponse
     *
     * @param string $xmlResponse
     *
     * @return TCCLog
     */
    public function setXmlResponse($xmlResponse)
    {
        $this->xmlResponse = $xmlResponse;

        return $this;
    }

    /**
     * Get xmlResponse
     *
     * @return string
     */
    public function getXmlResponse()
    {
        return $this->xmlResponse;
    }


    /**
     * Set xmlRequestConsulta
     *
     * @param string $xmlRequestConsulta
     *
     * @return TCCLog
     */
    public function setXmlRequestConsulta($xmlRequestConsulta)
    {
        $this->xmlRequestConsulta = $xmlRequestConsulta;

        return $this;
    }

    /**
     * Get xmlRequest
     *
     * @return string
     */
    public function getXmlRequestConsulta()
    {
        return $this->xmlRequestConsulta;
    }

    /**
     * Set xmlResponse
     *
     * @param string $xmlResponseConsulta
     *
     * @return TCCLog
     */
    public function setXmlResponseConsulta($xmlResponseConsulta)
    {
        $this->xmlResponseConsulta = $xmlResponseConsulta;

        return $this;
    }

    /**
     * Get xmlResponse
     *
     * @return string
     */
    public function getXmlResponseConsulta()
    {
        return $this->xmlResponseConsulta;
    }

    /**
     * Set remesa
     *
     * @param string $remesa
     *
     * @return TCCLog
     */
    public function setRemesa($remesa)
    {
        $this->remesa = $remesa;

        return $this;
    }

    /**
     * Get remesa
     *
     * @return string
     */
    public function getRemesa()
    {
        return $this->remesa;
    }

    /**
     * Set numeroRecogida
     *
     * @param string $numeroRecogida
     *
     * @return TCCLog
     */
    public function setNumeroRecogida($numeroRecogida)
    {
        $this->numeroRecogida = $numeroRecogida;

        return $this;
    }

    /**
     * Get numeroRecogida
     *
     * @return string
     */
    public function getNumeroRecogida()
    {
        return $this->numeroRecogida;
    }

    /**
     * Set urlRelacionEnvio
     *
     * @param string $urlRelacionEnvio
     *
     * @return TCCLog
     */
    public function setUrlRelacionEnvio($urlRelacionEnvio)
    {
        $this->urlRelacionEnvio = $urlRelacionEnvio;

        return $this;
    }

    /**
     * Get urlRelacionEnvio
     *
     * @return string
     */
    public function getUrlRelacionEnvio()
    {
        return $this->urlRelacionEnvio;
    }

    /**
     * Set urlRemesa
     *
     * @param string $urlRemesa
     *
     * @return TCCLog
     */
    public function setUrlRemesa($urlRemesa)
    {
        $this->urlRemesa = $urlRemesa;

        return $this;
    }

    /**
     * Get urlRemesa
     *
     * @return string
     */
    public function getUrlRemesa()
    {
        return $this->urlRemesa;
    }

    /**
     * Set urlRotulos
     *
     * @param string $urlRotulos
     *
     * @return TCCLog
     */
    public function setUrlRotulos($urlRotulos)
    {
        $this->urlRotulos = $urlRotulos;

        return $this;
    }

    /**
     * Get urlRotulos
     *
     * @return string
     */
    public function getUrlRotulos()
    {
        return $this->urlRotulos;
    }

    /**
     * Set respuesta
     *
     * @param string $respuesta
     *
     * @return TCCLog
     */
    public function setRespuesta($respuesta)
    {
        $this->respuesta = $respuesta;

        return $this;
    }

    /**
     * Get respuesta
     *
     * @return string
     */
    public function getRespuesta()
    {
        return $this->respuesta;
    }

    /**
     * Set mensaje
     *
     * @param string $mensaje
     *
     * @return TCCLog
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
     * Set estadoEnvio
     *
     * @param \CarroiridianBundle\Entity\Compra $compra
     *
     * @return TCCLog
     */
    public function setEstadoEnvio(\CarroiridianBundle\Entity\EstadoEnvio $estadoEnvio = null)
    {
        $this->estadoEnvio = $estadoEnvio;
        return $this;
    }

    /**
     * Get estadoEnvio
     *
     * @return \CarroiridianBundle\Entity\EstadoEnvio
     */
    public function getEstadoEnvio()
    {
        return $this->estadoEnvio;
    }
}

