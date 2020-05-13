<?php

namespace ContactoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Tipoconsulta
 *
 * @ORM\Table(name="tipoconsulta")
 * @ORM\Entity(repositoryClass="ContactoBundle\Repository\TipoconsultaRepository")
 */
class Tipoconsulta
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
     * @return Tipoconsulta
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
     * @return Tipoconsulta
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
