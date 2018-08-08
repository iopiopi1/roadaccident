<?php
/**
 * Created by PhpStorm.
 * User: Ярослав
 * Date: 22.02.2016
 * Time: 1:38
 */


namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="vehicle")
 */
class Vehicle
{

    const STATUS_ACTIVE = 0;
    const STATUS_BLOCKED = 1;
    const STATUS_DELETED = 2;

    /**
     * Primary Identifier
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="integer")
     */
    protected $status;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $dateCreated;
	
	/**
     * @ORM\Column(type="datetime")
     */
    protected $dateEdited;
	
    /**
     * @ORM\Column(type="integer", nullable=true)
     * @ORM\ManyToOne(targetEntity="\Application\Entity\Brand")
     * @ORM\JoinColumn(name="brand", referencedColumnName="id")
     */
    protected $brand;

    /**
     * @ORM\Column(type="string")
     */
    protected $regnum;
	
	    public function __construct()
    {
        $this->setDateCreated(new \DateTime("now"));
    }
	
	/**
     * @ORM\Column(type="integer")
     */
    protected $user;
	
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
     * @var int
     * @param integer $brand
     */
    public function setBrand($brand)
    {
        $this->brand = $brand;
        return $this;
    }

    /**
     * @return integer
     */
    public function getBrand()
    {
        return $this->brand;
    }
	
	
    /**
     * @return string
     */
    public function getRegnum()
    {
        return $this->regnum;
    }

    /**
     * @param string $regnum
     */
    public function setRegnum($regnum)
    {
        $this->regnum = $regnum;
        return $this;
    }
	
	/**
     * @var int
     * @param integer $user
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return integer
     */
    public function getUser()
    {
        return $this->user;
    }
	
}