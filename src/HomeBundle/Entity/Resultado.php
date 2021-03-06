<?php

namespace HomeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\PropertyAccess\PropertyAccess;

/**
 * Resultado
 *
 * @ORM\Table(name="resultado")
 * @ORM\Entity(repositoryClass="HomeBundle\Repository\ResultadoRepository")
 */
class Resultado
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
     * @ORM\ManyToOne(targetEntity="HomeBundle\Entity\Encuesta", inversedBy="resultados")
     * @ORM\JoinColumn(name="encuesta_id", referencedColumnName="id")
     */
    private $encuesta;

    /**
     * @ORM\ManyToOne(targetEntity="HomeBundle\Entity\Opcion")
     * @ORM\JoinColumn(name="opcion_id", referencedColumnName="id")
     */
    private $opcion;

    /**
     * @var int
     *
     * @ORM\Column(name="valor", type="integer")
     */
    private $valor;


    public function __toString()
    {
        return $this->opcion.' - '.$this->valor.' ';
    }

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
     * Set valor
     *
     * @param integer $valor
     *
     * @return Resultado
     */
    public function setValor($valor)
    {
        $this->valor = $valor;
    
        return $this;
    }

    /**
     * Get valor
     *
     * @return integer
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * Set encuesta
     *
     * @param \HomeBundle\Entity\Encuesta $encuesta
     *
     * @return Resultado
     */
    public function setEncuesta(\HomeBundle\Entity\Encuesta $encuesta = null)
    {
        $this->encuesta = $encuesta;
    
        return $this;
    }

    /**
     * Get encuesta
     *
     * @return \HomeBundle\Entity\Encuesta
     */
    public function getEncuesta()
    {
        return $this->encuesta;
    }

    /**
     * Set opcion
     *
     * @param \HomeBundle\Entity\Opcion $opcion
     *
     * @return Resultado
     */
    public function setOpcion(\HomeBundle\Entity\Opcion $opcion = null)
    {
        $this->opcion = $opcion;
    
        return $this;
    }

    /**
     * Get opcion
     *
     * @return \HomeBundle\Entity\Opcion
     */
    public function getOpcion()
    {
        return $this->opcion;
    }
}
