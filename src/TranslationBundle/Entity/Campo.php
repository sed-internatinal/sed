<?php

namespace TranslationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Campo
 *
 * @ORM\Table(name="campo")
 * @ORM\Entity(repositoryClass="TranslationBundle\Repository\CampoRepository")
 */
class Campo
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
     * @ORM\Column(name="hash", type="string", length=255)
     */
    private $hash;

    /**
     * @ORM\OneToMany(targetEntity="TranslationBundle\Entity\Traduccion", mappedBy="campo",cascade={"remove"})
     */
    private $traducciones;


    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    public function __toString(){
       return $this->id."";
    }
    /**
     * Constructor
     */
    public function __construct()
    {
    }

    /**
     * Set hash.
     *
     * @param string $hash
     *
     * @return Campo
     */
    public function setHash($hash)
    {
        $this->hash = $hash;
    
        return $this;
    }

    /**
     * Get hash.
     *
     * @return string
     */
    public function getHash()
    {
        return $this->hash;
    }

    public function get1(){}
    public function get2(){}
    public function get3(){}
    public function get4(){}
    public function get5(){}
    public function get6(){}
    public function get7(){}
    public function get8(){}
    public function get9(){}
    public function get10(){}
    public function get11(){}
    public function get12(){}
    public function get13(){}
    public function get14(){}
    public function get15(){}
    public function get16(){}
    public function get17(){}
    public function get18(){}
    public function get19(){}
    public function get20(){}


    /**
     * Add product.
     *
     * @param \TranslationBundle\Entity\Traduccion $product
     *
     * @return Campo
     */
    public function addProduct(\TranslationBundle\Entity\Traduccion $product)
    {
        $this->products[] = $product;
    
        return $this;
    }

    /**
     * Remove product.
     *
     * @param \TranslationBundle\Entity\Traduccion $product
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeProduct(\TranslationBundle\Entity\Traduccion $product)
    {
        return $this->products->removeElement($product);
    }

    /**
     * Get products.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * Add traduccione.
     *
     * @param \TranslationBundle\Entity\Traduccion $traduccione
     *
     * @return Campo
     */
    public function addTraduccione(\TranslationBundle\Entity\Traduccion $traduccione)
    {
        $this->traducciones[] = $traduccione;
    
        return $this;
    }

    /**
     * Remove traduccione.
     *
     * @param \TranslationBundle\Entity\Traduccion $traduccione
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeTraduccione(\TranslationBundle\Entity\Traduccion $traduccione)
    {
        return $this->traducciones->removeElement($traduccione);
    }

    /**
     * Get traducciones.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTraducciones()
    {
        return $this->traducciones;
    }
}
