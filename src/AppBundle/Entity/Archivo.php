<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * Archivo
 *
 * @ORM\Table(name="archivo")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ArchivoRepository")
 * @Vich\Uploadable
 */
class Archivo
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
     * @ORM\Column(name="llave", type="string", length=255, unique=true)
     */
    private $llave;

    /**
 * @var string
 *
 * @ORM\Column(name="archivoEn", type="string", length=255)
 */
    private $archivoEn;

    /**
     * @Vich\UploadableField(mapping="images", fileNameProperty="archivoEn")
     * @var File
     */
    private $archivoEnFile;


    /**
     * @var string
     *
     * @ORM\Column(name="archivoEs", type="string", length=255)
     */
    private $archivoEs;

    /**
     * @Vich\UploadableField(mapping="images", fileNameProperty="archivoEs")
     * @var File
     */
    private $archivoEsFile;

    /**
     * @ORM\Column(type="datetime")
     * @var \DateTime
     */
    private $updatedAt;

    public function __construct()
    {
        $this->updatedAt = new \DateTime('now');
    }


    public function gen($campo,$locale){
        $accessor = PropertyAccess::createPropertyAccessor();
        return $accessor->getValue($this,$campo.'_'.$locale);
    }

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
     * Set llave
     *
     * @param string $llave
     *
     * @return Archivo
     */
    public function setLlave($llave)
    {
        $this->llave = $llave;

        return $this;
    }

    /**
     * Get llave
     *
     * @return string
     */
    public function getLlave()
    {
        return $this->llave;
    }

    /**
     * @return string
     */
    public function getArchivoEn()
    {
        return $this->archivoEn;
    }

    /**
     * @param string $archivoEn
     */
    public function setArchivoEn($archivoEn)
    {
        $this->archivoEn = $archivoEn;
    }

    /**
     * @return File
     */
    public function getArchivoEnFile()
    {
        return $this->archivoEnFile;
    }

    /**
     * @param File $archivoEnFile
     */
    public function setArchivoEnFile($archivoEnFile)
    {
        $this->archivoEnFile = $archivoEnFile;
        if ($archivoEnFile) {
            $this->updatedAt = new \DateTime('now');
        }
    }

    /**
     * @return string
     */
    public function getArchivoEs()
    {
        return $this->archivoEs;
    }

    /**
     * @param string $archivoEs
     */
    public function setArchivoEs($archivoEs)
    {
        $this->archivoEs = $archivoEs;
    }

    /**
     * @return File
     */
    public function getArchivoEsFile()
    {
        return $this->archivoEsFile;
    }

    /**
     * @param File $archivoEsFile
     */
    public function setArchivoEsFile($archivoEsFile)
    {
        $this->archivoEsFile = $archivoEsFile;

        if ($archivoEsFile) {
            $this->updatedAt = new \DateTime('now');
        }
    }




    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return Archivo
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
}
