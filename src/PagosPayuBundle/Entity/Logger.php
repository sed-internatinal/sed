<?php
/**
 * Created by PhpStorm.
 * User: iridian_dev5
 * Date: 12/05/2017
 * Time: 3:56 PM
 */

namespace PagosPayuBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
/**
 * PagoLogger
 *
 * @ORM\Table(name="logger_pagos")
 * @ORM\Entity(repositoryClass="CarritoBundle\Repository\CategoriaRepository")
 */
class Logger
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
     * @ORM\Column(name="respuesta", type="text")
     */
    private $respuesta;

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
     * Set respuesta.
     *
     * @param string $respuesta
     *
     * @return Logger
     */
    public function setRespuesta($respuesta)
    {
        $this->respuesta = $respuesta;
    
        return $this;
    }

    /**
     * Get respuesta.
     *
     * @return string
     */
    public function getRespuesta()
    {
        return $this->respuesta;
    }
}
