<?php
/**
 * Created by PhpStorm.
 * User: ???????
 * Date: 28.02.2016
 * Time: 15:12
 */

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 */
class Admin
{	
	/**
     * Primary Identifier
     * @var int
     * @ORM\Id
     * @ORM\Column(type="integer")
	 * @ORM\OneToOne(targetEntity="\Application\Entity\Brand")
     * @ORM\JoinColumn(name="id", referencedColumnName="id")
     */
    protected $id;
	
	
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $password;
	
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
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->username = $password;
        return $this;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }
	
}