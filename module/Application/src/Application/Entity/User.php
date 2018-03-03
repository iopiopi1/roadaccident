<?php
/**
 * Created by PhpStorm.
 * User: Ярослав
 * Date: 28.02.2016
 * Time: 15:12
 */

namespace Application\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 */
class User
{
		
    const STATUS_ACTIVE = 0;
    const STATUS_BLOCKED = 1;
    const STATUS_DELETED = 2;
    const STATUS_NONACTIVE = 3;
	
    /**
     * Primary Identifier
     * @var int
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $username;

    /**
     * @var integer
     * @ORM\Column(type="integer")
     */
    protected $status;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     */
    protected $dateCreated;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     */
    protected $dateEdited;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $email;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $password;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $phone;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $firstname;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $lastname;

    /**
     * @var \DateTime
     * @ORM\Column(type="date")
     */
    protected $birthdate;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    protected $vkId;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    protected $okId;
	
	 /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     */
    protected $passwordChangeddate;

    public function __construct()
    {
        $this->setDateCreated(new \DateTime("now", new \DateTimeZone("UTC")));
        $this->setPasswordChangeddate(new \DateTime("now", new \DateTimeZone("UTC")));
    }

    /**
     * @var int
     * @param integer $id
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param integer $id
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param \Datetime $id
     */
    public function setDateCreated($dateCreated)
    {
        $this->dateCreated = $dateCreated;
        return $this;
    }

    /**
     * @return \Datetime
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }

    /**
     * @param \Datetime $dateEdited
     */
    public function setDateEdited($dateEdited)
    {
        $this->dateEdited = $dateEdited;
        return $this;
    }

    /**
     * @return \Datetime
     */
    public function getDateEdited()
    {
        return $this->dateEdited;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }	
	
	
    /**
     * @param \Datetime $passwordChangeddate
     */
    public function setPasswordChangeddate($passwordChangeddate)
    {
        $this->passwordChangeddate = $passwordChangeddate;
        return $this;
    }

    /**
     * @return \Datetime
     */
    public function getPasswordChangeddate()
    {
        return $this->passwordChangeddate;
    }
	
	
	
	

}