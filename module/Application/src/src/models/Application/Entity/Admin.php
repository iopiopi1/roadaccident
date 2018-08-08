<?php

namespace Application\Entity;

/**
 * Admin
 */
class Admin
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $password;


    /**
     * Set id
     *
     * @param integer $id
     *
     * @return Admin
     */
    public function setId($id)
    {
        $this->id = $id;
    
        return $this;
    }

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
     * Set password
     *
     * @param string $password
     *
     * @return Admin
     */
    public function setPassword($password)
    {
        $this->password = $password;
    
        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }
}

