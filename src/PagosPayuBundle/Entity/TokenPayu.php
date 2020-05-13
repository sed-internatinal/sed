<?php
/**
 * Created by PhpStorm.
 * User: iridian_dev5
 * Date: 12/05/2017
 * Time: 3:53 PM
 */

namespace PagosPayuBundle\Entity;

use AppBundle\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * DatosPayu
 *
 * @ORM\Table(name="token_payu")
 * @ORM\Entity(repositoryClass="PagosPayuBundle\Repository\DatosPayuRepository")
 */
class TokenPayu
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
     * @ORM\Column(name="code", type="string", nullable=true)
     */
    private $code;

    /**
     * @var string
     *
     * @ORM\Column(name="creditCardTokenId", type="string", nullable=true)
     */
    private $creditCardTokenId;

    /**
     * @var string
     * @Assert\NotBlank(message = "El nombre no puede ser vacio")
     * @ORM\Column(name="name", type="string")
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="payerId", type="string", nullable=true)
     */
    private $payerId;

    /**
     * @var string
     *
     * @ORM\Column(name="identificationNumber", type="string", nullable=true)
     */
    private $identificationNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="paymentMethod", type="string", nullable=true)
     */
    private $paymentMethod;

    /**
     * @var string
     *
     * @ORM\Column(name="maskedNumber", type="string", nullable=true)
     */
    private $maskedNumber;

    /**
     * @var string
     *  @Assert\NotBlank(message = "El apellido no puede ser vacio")
     * @ORM\Column(name="lastname", type="string")
     */
    private $lastname;

    /**
     * @var string
     *@Assert\NotBlank(message = "El email no puede ser vacio")
     * @Assert\Email(message = "'{{ value }}' no es un email válido")
     * @ORM\Column(name="email", type="string")
     */
    private $email;

    /**
     * @var string
     *@Assert\NotBlank(message = "El telefono no puede ser vacio")
     * @ORM\Column(name="phone", type="string")
     */
    private $phone;

    /**
     * @var string
     *  @Assert\NotBlank(message = "El documento de identidad no puede ser vacio")
     * @ORM\Column(name="dni", type="string")
     */
    private $dni;

    /**
     * @var string
     *  @Assert\NotBlank(message = "La direccion no puede ser vacio")
     * @ORM\Column(name="dir", type="string")
     */
    private $dir;

    /**
     * @var string
     *
     * @Assert\NotBlank(message = "La ciudad no puede ser vacio")
     *
     * @ORM\Column(name="city", type="string")
     */
    private $city;

    /**
     * @var string
     *
     * @Assert\NotBlank(message = "El estado no puede ser vacio")
     * @ORM\Column(name="state", type="string")
     */
    private $state;

    /**
     * @var string
     *
     * @Assert\NotBlank(message = "El pais no puede ser vacio")
     * @ORM\Column(name="country", type="string")
     */
    private $country;

    /**
     * @var string
     *
     * @Assert\NotBlank(message = "El codigo postal no puede ser vacio")
     * @ORM\Column(name="postalcode", type="string")
     */
    private $postalcode;

    /**
     * The user who made the purchase.
     *
     * @var User
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="tokens")
     */
    protected $usuario;

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
     * Set code.
     *
     * @param string|null $code
     *
     * @return TokenPayu
     */
    public function setCode($code = null)
    {
        $this->code = $code;
    
        return $this;
    }

    /**
     * Get code.
     *
     * @return string|null
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set creditCardTokenId.
     *
     * @param string|null $creditCardTokenId
     *
     * @return TokenPayu
     */
    public function setCreditCardTokenId($creditCardTokenId = null)
    {
        $this->creditCardTokenId = $creditCardTokenId;
    
        return $this;
    }

    /**
     * Get creditCardTokenId.
     *
     * @return string|null
     */
    public function getCreditCardTokenId()
    {
        return $this->creditCardTokenId;
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return TokenPayu
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set payerId.
     *
     * @param string|null $payerId
     *
     * @return TokenPayu
     */
    public function setPayerId($payerId = null)
    {
        $this->payerId = $payerId;
    
        return $this;
    }

    /**
     * Get payerId.
     *
     * @return string|null
     */
    public function getPayerId()
    {
        return $this->payerId;
    }

    /**
     * Set identificationNumber.
     *
     * @param string|null $identificationNumber
     *
     * @return TokenPayu
     */
    public function setIdentificationNumber($identificationNumber = null)
    {
        $this->identificationNumber = $identificationNumber;
    
        return $this;
    }

    /**
     * Get identificationNumber.
     *
     * @return string|null
     */
    public function getIdentificationNumber()
    {
        return $this->identificationNumber;
    }

    /**
     * Set paymentMethod.
     *
     * @param string|null $paymentMethod
     *
     * @return TokenPayu
     */
    public function setPaymentMethod($paymentMethod = null)
    {
        $this->paymentMethod = $paymentMethod;
    
        return $this;
    }

    /**
     * Get paymentMethod.
     *
     * @return string|null
     */
    public function getPaymentMethod()
    {
        return $this->paymentMethod;
    }

    /**
     * Set maskedNumber.
     *
     * @param string|null $maskedNumber
     *
     * @return TokenPayu
     */
    public function setMaskedNumber($maskedNumber = null)
    {
        $this->maskedNumber = $maskedNumber;
    
        return $this;
    }

    /**
     * Get maskedNumber.
     *
     * @return string|null
     */
    public function getMaskedNumber()
    {
        return $this->maskedNumber;
    }

    /**
     * Set lastname.
     *
     * @param string $lastname
     *
     * @return TokenPayu
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    
        return $this;
    }

    /**
     * Get lastname.
     *
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set email.
     *
     * @param string $email
     *
     * @return TokenPayu
     */
    public function setEmail($email)
    {
        $this->email = $email;
    
        return $this;
    }

    /**
     * Get email.
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set phone.
     *
     * @param string $phone
     *
     * @return TokenPayu
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    
        return $this;
    }

    /**
     * Get phone.
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set dni.
     *
     * @param string $dni
     *
     * @return TokenPayu
     */
    public function setDni($dni)
    {
        $this->dni = $dni;
    
        return $this;
    }

    /**
     * Get dni.
     *
     * @return string
     */
    public function getDni()
    {
        return $this->dni;
    }

    /**
     * Set dir.
     *
     * @param string $dir
     *
     * @return TokenPayu
     */
    public function setDir($dir)
    {
        $this->dir = $dir;
    
        return $this;
    }

    /**
     * Get dir.
     *
     * @return string
     */
    public function getDir()
    {
        return $this->dir;
    }

    /**
     * Set city.
     *
     * @param string $city
     *
     * @return TokenPayu
     */
    public function setCity($city)
    {
        $this->city = $city;
    
        return $this;
    }

    /**
     * Get city.
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set state.
     *
     * @param string $state
     *
     * @return TokenPayu
     */
    public function setState($state)
    {
        $this->state = $state;
    
        return $this;
    }

    /**
     * Get state.
     *
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set country.
     *
     * @param string $country
     *
     * @return TokenPayu
     */
    public function setCountry($country)
    {
        $this->country = $country;
    
        return $this;
    }

    /**
     * Get country.
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set postalcode.
     *
     * @param string $postalcode
     *
     * @return TokenPayu
     */
    public function setPostalcode($postalcode)
    {
        $this->postalcode = $postalcode;
    
        return $this;
    }

    /**
     * Get postalcode.
     *
     * @return string
     */
    public function getPostalcode()
    {
        return $this->postalcode;
    }

    /**
     * Set usuario.
     *
     * @param \AppBundle\Entity\User|null $usuario
     *
     * @return TokenPayu
     */
    public function setUsuario(\AppBundle\Entity\User $usuario = null)
    {
        $this->usuario = $usuario;
    
        return $this;
    }

    /**
     * Get usuario.
     *
     * @return \AppBundle\Entity\User|null
     */
    public function getUsuario()
    {
        return $this->usuario;
    }
}
