<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Pixel
 *
 * @ORM\Table(name="pixel")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PixelRepository")
 */
class Pixel
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
     * @ORM\Column(name="identificador", type="string", length=255)
     */
    private $identificador;


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
     * Set identificador.
     *
     * @param string $identificador
     *
     * @return Pixel
     */
    public function setIdentificador($identificador)
    {
        $this->identificador = $identificador;
    
        return $this;
    }

    /**
     * Get identificador.
     *
     * @return string
     */
    public function getIdentificador()
    {
        return $this->identificador;
    }
}
