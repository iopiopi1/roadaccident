<?php
/**
 * Created by PhpStorm.
 * User: ???????
 * Date: 22.02.2016
 * Time: 1:38
 */


namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="brand")
 */
class Brand
{
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
    protected $dateModified;
	
    /**
     * @ORM\Column(type="integer")
     * @ORM\ManyToOne(targetEntity="\Application\Entity\Supplier")
     * @ORM\JoinColumn(name="supplier", referencedColumnName="id")
     */
    protected $supplier;

    /**
     * @ORM\Column(type="string")
     */
    protected $name;
	
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
     * @param integer $status
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
     * @param \Datetime $dateCreated
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
     * @param integer $supplier
     */
    public function setSupplier($supplier)
    {
        $this->supplier = $supplier;
        return $this;
    }

    /**
     * @return integer
     */
    public function getSupplier()
    {
        return $this->supplier;
    }
		
    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }
	
}