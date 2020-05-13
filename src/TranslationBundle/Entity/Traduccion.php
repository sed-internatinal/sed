<?php

namespace TranslationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Traduccion
 *
 * @ORM\Table(name="traduccion")
 * @ORM\Entity(repositoryClass="TranslationBundle\Repository\TraduccionRepository")
 */
class Traduccion
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
     * @ORM\ManyToOne(targetEntity="TranslationBundle\Entity\Idioma",cascade={"persist"})
     * @ORM\JoinColumn(name="idioma_id", referencedColumnName="id")
     */
    private $idioma;

    /**
     * @ORM\ManyToOne(targetEntity="TranslationBundle\Entity\Campo",cascade={"persist" })
     * @ORM\JoinColumn(name="campo_id", referencedColumnName="id")
     */
    private $campo;

    /**
     * @var int
     *
     * @ORM\Column(name="valor", type="string")
     */
    private $valor;

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
     * Set idioma.
     *
     * @param \TranslationBundle\Entity\Idioma|null $idioma
     *
     * @return Traduccion
     */
    public function setIdioma(\TranslationBundle\Entity\Idioma $idioma = null)
    {
        $this->idioma = $idioma;
    
        return $this;
    }

    /**
     * Get idioma.
     *
     * @return \TranslationBundle\Entity\Idioma|null
     */
    public function getIdioma()
    {
        return $this->idioma;
    }

    /**
     * Set campo.
     *
     * @param \TranslationBundle\Entity\Campo|null $campo
     *
     * @return Traduccion
     */
    public function setCampo(\TranslationBundle\Entity\Campo $campo = null)
    {
        $this->campo = $campo;
    
        return $this;
    }

    /**
     * Get campo.
     *
     * @return \TranslationBundle\Entity\Campo|null
     */
    public function getCampo()
    {
        return $this->campo;
    }

    /**
     * Set valor.
     *
     * @param string $valor
     *
     * @return Traduccion
     */
    public function setValor($valor)
    {
        $this->valor = $valor;
    
        return $this;
    }

    /**
     * Get valor.
     *
     * @return string
     */
    public function getValor()
    {
        return $this->valor;
    }
}
