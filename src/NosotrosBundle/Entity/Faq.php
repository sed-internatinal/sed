<?php

namespace NosotrosBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\PropertyAccess\PropertyAccess;

/**
 * Faq
 *
 * @ORM\Table(name="faq")
 * @ORM\Entity(repositoryClass="NosotrosBundle\Repository\FaqRepository")
 */
class Faq
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
     * @ORM\Column(name="pregunta_es", type="string", length=255)
     */
    private $preguntaEs;

    /**
     * @var string
     *
     * @ORM\Column(name="pregunta_en", type="string", length=255, nullable=true)
     */
    private $preguntaEn;

    /**
     * @var string
     *
     * @ORM\Column(name="respuesta_es", type="text")
     */
    private $respuestaEs;

    /**
     * @var string
     *
     * @ORM\Column(name="respuesta_en", type="text", nullable=true)
     */
    private $respuestaEn;

    /**
     * @var int
     *
     * @ORM\Column(name="orden", type="integer")
     */
    private $orden = 1;

    /**
     * @var bool
     *
     * @ORM\Column(name="visible", type="boolean")
     */
    private $visible = true;

    public function __toString()
    {
        return $this->preguntaEs.' ';
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
     * Set preguntaEs
     *
     * @param string $preguntaEs
     *
     * @return Faq
     */
    public function setPreguntaEs($preguntaEs)
    {
        $this->preguntaEs = $preguntaEs;
    
        return $this;
    }

    /**
     * Get preguntaEs
     *
     * @return string
     */
    public function getPreguntaEs()
    {
        return $this->preguntaEs;
    }

    /**
     * Set preguntaEn
     *
     * @param string $preguntaEn
     *
     * @return Faq
     */
    public function setPreguntaEn($preguntaEn)
    {
        $this->preguntaEn = $preguntaEn;
    
        return $this;
    }

    /**
     * Get preguntaEn
     *
     * @return string
     */
    public function getPreguntaEn()
    {
        return $this->preguntaEn;
    }

    /**
     * Set respuestaEs
     *
     * @param string $respuestaEs
     *
     * @return Faq
     */
    public function setRespuestaEs($respuestaEs)
    {
        $this->respuestaEs = $respuestaEs;
    
        return $this;
    }

    /**
     * Get respuestaEs
     *
     * @return string
     */
    public function getRespuestaEs()
    {
        return $this->respuestaEs;
    }

    /**
     * Set respuestaEn
     *
     * @param string $respuestaEn
     *
     * @return Faq
     */
    public function setRespuestaEn($respuestaEn)
    {
        $this->respuestaEn = $respuestaEn;
    
        return $this;
    }

    /**
     * Get respuestaEn
     *
     * @return string
     */
    public function getRespuestaEn()
    {
        return $this->respuestaEn;
    }

    /**
     * Set orden
     *
     * @param integer $orden
     *
     * @return Faq
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
     * @return Faq
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
}
