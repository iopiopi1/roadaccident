<?php

namespace Application\Entity;

/**
 * Vehicle
 */
class Vehicle
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $status;

    /**
     * @var \DateTime
     */
    private $dateCreated;

    /**
     * @var \DateTime
     */
    private $dateEdited;

    /**
     * @var integer
     */
    private $brand;

    /**
     * @var string
     */
    private $regnum;

    /**
     * @var integer
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

