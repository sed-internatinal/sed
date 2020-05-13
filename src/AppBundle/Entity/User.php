<?php
namespace AppBundle\Entity;

use CarroiridianBundle\Entity\Envio;
use CarroiridianBundle\Entity\Producto;
use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use PagosPayuBundle\Entity\TokenPayu;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 * @Vich\Uploadable
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=255, nullable=true)
     * @Assert\NotBlank(message = "Este campo no puede ser vacio")
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="apellidos", type="string", length=255, nullable=true)
     */
    private $apellidos;

    /**
     * @var string
     *
     * @ORM\Column(name="telefono", type="string", length=255, nullable=true)
     *
     */
    private $telefono;

    /**
     * @var boolean
     *
     * @ORM\Column(name="es_empresa", type="boolean", nullable=true)
     *
     */
    private $esEmpresa;

    /**
     * @var string
     *
     * @ORM\Column(name="celular", type="string", length=255, nullable=true)
     * @Assert\NotBlank(message = "Este campo no puede ser vacio")
     */
    private $celular;

    /**
     * @var string
     *
     * @ORM\Column(name="nit", type="string", length=255, nullable=true)
     */
    private $nit;

    /**
     * @var string
     *
     * @ORM\Column(name="documento", type="string", length=255, nullable=true)
     */
    private $documento;

    /**
     * @var string
     *
     * @ORM\Column(name="d_verificacion", type="string", length=255, nullable=true)
     */
    private $dVerificacion;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\TipoDoc")
     * @ORM\JoinColumn(name="tipodoc_id", referencedColumnName="id")
     */
    private $tipodoc;

    /**
     * @ORM\ManyToOne(targetEntity="CarroiridianBundle\Entity\Convenio")
     * @ORM\JoinColumn(name="convenio_id", referencedColumnName="id")
     */
    private $convenio;

    /**
     * @var string
     *
     * @ORM\Column(name="genero", type="string", length=255, nullable=true)
     */
    private $genero;

    /**
     * @var string
     *
     * @ORM\Column(name="direccion", type="string", length=255, nullable=true)
     */
    private $direccion;

    /**
     * @ORM\ManyToOne(targetEntity="CarroiridianBundle\Entity\Departamento")
     * @ORM\JoinColumn(name="departamento_id", referencedColumnName="id")
     */
    private $departamento;

    /**
     * @var string
     *
     * @ORM\Column(name="ciudad", type="string", length=255, nullable=true)
     */
    private $ciudad;

    /**
     * @var string
     *
     * @ORM\Column(name="pais", type="string", length=255, nullable=true)
     */
    private $pais;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */
    private $fecha;

    /**
     * @var Compra[]
     *
     * @ORM\OneToMany(targetEntity="CarroiridianBundle\Entity\Compra", mappedBy="comprador", cascade={"remove"})
     */
    private $compras;

    /**
     * @var Envio[]
     *
     * @ORM\OneToMany(targetEntity="CarroiridianBundle\Entity\Envio", mappedBy="user", cascade={"remove"})
     */
    private $direcciones;

    /**
     * @var Producto[]
     *
     * @ORM\OneToMany(targetEntity="CarroiridianBundle\Entity\Producto", mappedBy="user", cascade={"remove"})
     */
    private $favoritos;

    /**
     * @var TokenPayu[]
     *
     * @ORM\OneToMany(targetEntity="PagosPayuBundle\Entity\TokenPayu", mappedBy="usuario", cascade={"remove"})
     */
    private $tokens;

    /**
     * @var string
     *
     * @ORM\Column(name="certificado_camara", type="string", length=255, nullable=true)
     */
    private $certificadoCamara;

    /**
     * @Vich\UploadableField(mapping="empresas", fileNameProperty="certificadoCamara")
     * @var File
     */
    private $certificadoCamaraFile;

    /**
     * @var string
     *
     * @ORM\Column(name="estados_financieros", type="string", length=255, nullable=true)
     */
    private $estadosFinancieros;

    /**
     * @Vich\UploadableField(mapping="empresas", fileNameProperty="estadosFinancieros")
     * @var File
     */
    private $estadosFinancierosFile;

    /**
     * @var string
     *
     * @ORM\Column(name="declaracion_1", type="string", length=255, nullable=true)
     */
    private $declaracion1;

    /**
     * @Vich\UploadableField(mapping="empresas", fileNameProperty="declaracion1")
     * @var File
     */
    private $declaracion1File;

    /**
     * @var string
     *
     * @ORM\Column(name="declaracion_2", type="string", length=255, nullable=true)
     */
    private $declaracion2;

    /**
     * @Vich\UploadableField(mapping="empresas", fileNameProperty="declaracion2")
     * @var File
     */
    private $declaracion2File;

    /**
     * @var string
     *
     * @ORM\Column(name="cedula_representante", type="string", length=255, nullable=true)
     */
    private $cedulaRepresentante;

    /**
     * @Vich\UploadableField(mapping="empresas", fileNameProperty="cedulaRepresentante")
     * @var File
     */
    private $cedulaRepresentanteFile;

    /**
     * @var string
     *
     * @ORM\Column(name="rut", type="string", length=255, nullable=true)
     */
    private $rut;

    /**
     * @Vich\UploadableField(mapping="empresas", fileNameProperty="rut")
     * @var File
     */
    private $rutFile;

    /**
     * @ORM\Column(type="datetime")
     * @var \DateTime
     */
    private $updatedAt;

    public function __construct()
    {
        parent::__construct();
        $this->direcciones = new ArrayCollection();
        $this->compras = new ArrayCollection();
        $this->favoritos = new ArrayCollection();
        $this->updatedAt= new \DateTime();
    }

    public function __toString()
    {

        if(is_numeric($this->getId()))
            return $this->getId().' '.$this->getNombre();
        return ' ';
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return User
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
     * Set apellidos
     *
     * @param string $apellidos
     *
     * @return User
     */
    public function setApellidos($apellidos)
    {
        $this->apellidos = $apellidos;
    
        return $this;
    }

    /**
     * Get apellidos
     *
     * @return string
     */
    public function getApellidos()
    {
        return $this->apellidos;
    }

    /**
     * Set telefono
     *
     * @param string $telefono
     *
     * @return User
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
     * Add compra
     *
     * @param \CarroiridianBundle\Entity\Compra $compra
     *
     * @return User
     */
    public function addCompra(\CarroiridianBundle\Entity\Compra $compra)
    {
        $this->compras[] = $compra;
    
        return $this;
    }

    /**
     * Remove compra
     *
     * @param \CarroiridianBundle\Entity\Compra $compra
     */
    public function removeCompra(\CarroiridianBundle\Entity\Compra $compra)
    {
        $this->compras->removeElement($compra);
    }

    /**
     * Get compras
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCompras()
    {
        return $this->compras;
    }

    /**
     * Add direccione
     *
     * @param \CarroiridianBundle\Entity\Envio $direccione
     *
     * @return User
     */
    public function addDireccione(\CarroiridianBundle\Entity\Envio $direccione)
    {
        $this->direcciones[] = $direccione;
    
        return $this;
    }

    /**
     * Remove direccione
     *
     * @param \CarroiridianBundle\Entity\Envio $direccione
     */
    public function removeDireccione(\CarroiridianBundle\Entity\Envio $direccione)
    {
        $this->direcciones->removeElement($direccione);
    }

    /**
     * Get direcciones
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDirecciones()
    {
        return $this->direcciones;
    }

    /**
     * Add favorito
     *
     * @param \CarroiridianBundle\Entity\Producto $favorito
     *
     * @return User
     */
    public function addFavorito(\CarroiridianBundle\Entity\Producto $favorito)
    {
        $this->favoritos[] = $favorito;
    
        return $this;
    }

    /**
     * Remove favorito
     *
     * @param \CarroiridianBundle\Entity\Producto $favorito
     */
    public function removeFavorito(\CarroiridianBundle\Entity\Producto $favorito)
    {
        $this->favoritos->removeElement($favorito);
    }

    /**
     * Get favoritos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFavoritos()
    {
        return $this->favoritos;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return User
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get fecha
     *
     * @return \DateTime
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Add token.
     *
     * @param \PagosPayuBundle\Entity\TokenPayu $token
     *
     * @return User
     */
    public function addToken(\PagosPayuBundle\Entity\TokenPayu $token)
    {
        $this->tokens[] = $token;
    
        return $this;
    }

    /**
     * Remove token.
     *
     * @param \PagosPayuBundle\Entity\TokenPayu $token
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeToken(\PagosPayuBundle\Entity\TokenPayu $token)
    {
        return $this->tokens->removeElement($token);
    }

    /**
     * Get tokens.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTokens()
    {
        return $this->tokens;
    }

    /**
     * Set direccion
     *
     * @param string $direccion
     *
     * @return User
     */
    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;
    
        return $this;
    }

    /**
     * Get direccion
     *
     * @return string
     */
    public function getDireccion()
    {
        return $this->direccion;
    }

    /**
     * Set ciudad
     *
     * @param string $ciudad
     *
     * @return User
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
     * Set pais
     *
     * @param string $pais
     *
     * @return User
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
     * Set tarjeta
     *
     * @param boolean $tarjeta
     *
     * @return User
     */
    public function setTarjeta($tarjeta)
    {
        $this->tarjeta = $tarjeta;
    
        return $this;
    }

    /**
     * Get tarjeta
     *
     * @return boolean
     */
    public function getTarjeta()
    {
        return $this->tarjeta;
    }

    /**
     * Set departamento
     *
     * @param \CarroiridianBundle\Entity\Departamento $departamento
     *
     * @return User
     */
    public function setDepartamento(\CarroiridianBundle\Entity\Departamento $departamento = null)
    {
        $this->departamento = $departamento;
    
        return $this;
    }

    /**
     * Get departamento
     *
     * @return \CarroiridianBundle\Entity\Departamento
     */
    public function getDepartamento()
    {
        return $this->departamento;
    }

    /**
     * Set celular
     *
     * @param string $celular
     *
     * @return User
     */
    public function setCelular($celular)
    {
        $this->celular = $celular;
    
        return $this;
    }

    /**
     * Get celular
     *
     * @return string
     */
    public function getCelular()
    {
        return $this->celular;
    }

    /**
     * Set documento
     *
     * @param string $documento
     *
     * @return User
     */
    public function setDocumento($documento)
    {
        $this->documento = $documento;
    
        return $this;
    }

    /**
     * Get documento
     *
     * @return string
     */
    public function getDocumento()
    {
        return $this->documento;
    }

    /**
     * Set genero
     *
     * @param string $genero
     *
     * @return User
     */
    public function setGenero($genero)
    {
        $this->genero = $genero;
    
        return $this;
    }

    /**
     * Get genero
     *
     * @return string
     */
    public function getGenero()
    {
        return $this->genero;
    }

    /**
     * Set tipodoc
     *
     * @param \AppBundle\Entity\TipoDoc $tipodoc
     *
     * @return User
     */
    public function setTipodoc(\AppBundle\Entity\TipoDoc $tipodoc = null)
    {
        $this->tipodoc = $tipodoc;
    
        return $this;
    }

    /**
     * Get tipodoc
     *
     * @return \AppBundle\Entity\TipoDoc
     */
    public function getTipodoc()
    {
        return $this->tipodoc;
    }

    /**
     * Set convenio
     *
     * @param \CarroiridianBundle\Entity\Convenio $convenio
     *
     * @return User
     */
    public function setConvenio(\CarroiridianBundle\Entity\Convenio $convenio = null)
    {
        $this->convenio = $convenio;
    
        return $this;
    }

    /**
     * Get convenio
     *
     * @return \CarroiridianBundle\Entity\Convenio
     */
    public function getConvenio()
    {
        return $this->convenio;
    }

    /**
     * Set esEmpresa
     *
     * @param boolean $esEmpresa
     *
     * @return User
     */
    public function setEsEmpresa($esEmpresa)
    {
        $this->esEmpresa = $esEmpresa;
    
        return $this;
    }

    /**
     * Get esEmpresa
     *
     * @return boolean
     */
    public function getEsEmpresa()
    {
        return $this->esEmpresa;
    }

    /**
     * Set nit
     *
     * @param string $nit
     *
     * @return User
     */
    public function setNit($nit)
    {
        $this->nit = $nit;
    
        return $this;
    }

    /**
     * Get nit
     *
     * @return string
     */
    public function getNit()
    {
        return $this->nit;
    }

    /**
     * Set dVerificacion
     *
     * @param string $dVerificacion
     *
     * @return User
     */
    public function setDVerificacion($dVerificacion)
    {
        $this->dVerificacion = $dVerificacion;
    
        return $this;
    }

    /**
     * Get dVerificacion
     *
     * @return string
     */
    public function getDVerificacion()
    {
        return $this->dVerificacion;
    }


    /**
     * @param File $image
     */
    public function setCertificadoCamaraFile(File $image )
    {
        $this->certificadoCamaraFile = $image;
        if ($image) {
            $this->updatedAt = new \DateTime('now');
        }
    }

    /**
     * @return File
     */
    public function getCertificadoCamaraFile()
    {
        return $this->certificadoCamaraFile;
    }

    /**
     * @param File $image
     */
    public function setDeclaracion1File(File $image )
    {
        $this->declaracion1File = $image;
        if ($image) {
            $this->updatedAt = new \DateTime('now');
        }
    }

    /**
     * @return File
     */
    public function getDeclaracion1File()
    {
        return $this->declaracion1File;
    }

    /**
     * @param File $image
     */
    public function setDeclaracion2File(File $image )
    {
        $this->declaracion2File = $image;
        if ($image) {
            $this->updatedAt = new \DateTime('now');
        }
    }

    /**
     * @return File
     */
    public function getDeclaracion2File()
    {
        return $this->declaracion2File;
    }

    /**
     * @param File $image
     */
    public function setEstadosFinancierosFile(File $image )
    {
        $this->estadosFinancierosFile = $image;
        if ($image) {
            $this->updatedAt = new \DateTime('now');
        }
    }

    /**
     * @return File
     */
    public function getEstadosFinancierosFile()
    {
        return $this->estadosFinancierosFile;
    }

    /**
     * @param File $image
     */
    public function setCedulaRepresentanteFile(File $image )
    {
        $this->cedulaRepresentanteFile = $image;
        if ($image) {
            $this->updatedAt = new \DateTime('now');
        }
    }

    /**
     * @return File
     */
    public function getCedulaRepresentanteFile()
    {
        return $this->cedulaRepresentanteFile;
    }

    /**
     * @param File $image
     */
    public function setRutFile(File $image )
    {
        $this->rutFile = $image;
        if ($image) {
            $this->updatedAt = new \DateTime('now');
        }
    }

    /**
     * @return File
     */
    public function getRutFile()
    {
        return $this->rutFile;
    }


    /**
     * Set certificadoCamara
     *
     * @param string $certificadoCamara
     *
     * @return User
     */
    public function setCertificadoCamara($certificadoCamara)
    {
        $this->certificadoCamara = $certificadoCamara;
    
        return $this;
    }

    /**
     * Get certificadoCamara
     *
     * @return string
     */
    public function getCertificadoCamara()
    {
        return $this->certificadoCamara;
    }

    /**
     * Set estadosFinancieros
     *
     * @param string $estadosFinancieros
     *
     * @return User
     */
    public function setEstadosFinancieros($estadosFinancieros)
    {
        $this->estadosFinancieros = $estadosFinancieros;
    
        return $this;
    }

    /**
     * Get estadosFinancieros
     *
     * @return string
     */
    public function getEstadosFinancieros()
    {
        return $this->estadosFinancieros;
    }

    /**
     * Set declaracion1
     *
     * @param string $declaracion1
     *
     * @return User
     */
    public function setDeclaracion1($declaracion1)
    {
        $this->declaracion1 = $declaracion1;
    
        return $this;
    }

    /**
     * Get declaracion1
     *
     * @return string
     */
    public function getDeclaracion1()
    {
        return $this->declaracion1;
    }

    /**
     * Set declaracion2
     *
     * @param string $declaracion2
     *
     * @return User
     */
    public function setDeclaracion2($declaracion2)
    {
        $this->declaracion2 = $declaracion2;
    
        return $this;
    }

    /**
     * Get declaracion2
     *
     * @return string
     */
    public function getDeclaracion2()
    {
        return $this->declaracion2;
    }

    /**
     * Set cedulaRepresentante
     *
     * @param string $cedulaRepresentante
     *
     * @return User
     */
    public function setCedulaRepresentante($cedulaRepresentante)
    {
        $this->cedulaRepresentante = $cedulaRepresentante;
    
        return $this;
    }

    /**
     * Get cedulaRepresentante
     *
     * @return string
     */
    public function getCedulaRepresentante()
    {
        return $this->cedulaRepresentante;
    }

    /**
     * Set rut
     *
     * @param string $rut
     *
     * @return User
     */
    public function setRut($rut)
    {
        $this->rut = $rut;
    
        return $this;
    }

    /**
     * Get rut
     *
     * @return string
     */
    public function getRut()
    {
        return $this->rut;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return User
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
