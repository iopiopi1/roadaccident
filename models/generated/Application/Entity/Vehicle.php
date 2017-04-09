<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Vehicle
 *
 * @ORM\Table(name="vehicle")
 * @ORM\Entity
 */
class Vehicle
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", precision=0, scale=0, nullable=false, unique=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="integer", precision=0, scale=0, nullable=false, unique=false)
     */
    private $status;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateCreated", type="datetime", precision=0, scale=0, nullable=false, unique=false)
     */
    private $dateCreated;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateEdited", type="datetime", precision=0, scale=0, nullable=false, unique=false)
     */
    private $dateEdited;

    /**
     * @var integer
     *
     * @ORM\Column(name="brand", type="integer", precision=0, scale=0, nullable=true, unique=false)
     */
    private $brand;

    /**
     * @var string
     *
     * @ORM\Column(name="regnum", type="string", precision=0, scale=0, nullable=false, unique=false)
     */
    private $regnum;

    /**
     * @var integer
     *
     * @ORM\Column(name="user", type="integer", precision=0, scale=0, nullable=false, unique=false)
     */
    private $user;


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
     * Set status
     *
     * @param integer $status
     *
     * @return Vehicle
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set dateCreated
     *
     * @param \DateTime $dateCreated
     *
     * @return Vehicle
     */
    public function setDateCreated($dateCreated)
    {
        $this->dateCreated = $dateCreated;

        return $this;
    }

    /**
     * Get dateCreated
     *
     * @return \DateTime
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }

    /**
     * Set dateEdited
     *
     * @param \DateTime $dateEdited
     *
     * @return Vehicle
     */
    public function setDateEdited($dateEdited)
    {
        $this->dateEdited = $dateEdited;

        return $this;
    }

    /**
     * Get dateEdited
     *
     * @return \DateTime
     */
    public function getDateEdited()
    {
        return $this->dateEdited;
    }

    /**
     * Set brand
     *
     * @param integer $brand
     *
     * @return Vehicle
     */
    public function setBrand($brand)
    {
        $this->brand = $brand;

        return $this;
    }

    /**
     * Get brand
     *
     * @return integer
     */
    public function getBrand()
    {
        return $this->brand;
    }

    /**
     * Set regnum
     *
     * @param string $regnum
     *
     * @return Vehicle
     */
    public function setRegnum($regnum)
    {
        $this->regnum = $regnum;

        return $this;
    }

    /**
     * Get regnum
     *
     * @return string
     */
    public function getRegnum()
    {
        return $this->regnum;
    }

    /**
     * Set user
     *
     * @param integer $user
     *
     * @return Vehicle
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return integer
     */
    public function getUser()
    {
        return $this->user;
    }
}

