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
 * @ORM\Table(name="userAction")
 */
class UserAction
{
    const ACTION_ACCEPT_SECPOLICY = 0;
    const ACTION_ACCEPT_USERSTATEMENT = 1;
    const ACCEPTED = 1;
    const NOT_ACCEPTED = 0;
    
    /**
     * Primary Identifier
     * @var int
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @var integer
     * @ORM\Column(type="integer")
     */
    protected $userId;
    
    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     */
    protected $date;
    
    /**
     * @var integer
     * @ORM\Column(type="integer")
     */
    protected $actionType;
    
    /**
     * @var integer
     * @ORM\Column(type="integer")
     */
    protected $intValue;
    
    /**
     * @var string
     * @ORM\Column(type="string")
     */    
    protected $stringValue;
    
    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     */
    protected $dateValue;
    
    
    public function __construct()
    {
        $this->setDate(new \DateTime("now"));
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
     * @var int
     * @param integer $userId
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
        return $this;
    }

    /**
     * @return integer
     */
    public function getUserId()
    {
        return $this->userId;
    }
    
    
    /**
     * @return \Datetime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param \Datetime $date
     */
    public function setDate($date)
    {
        $this->date = $date;
        return $this;
    }
    
    /**
     * @var int
     * @param integer $actionType
     */
    public function setActionType($actionType)
    {
        $this->actionType = $actionType;
        return $this;
    }

    /**
     * @return integer
     */
    public function getActionType()
    {
        return $this->actionType;
    }
    
    /**
     * @var int
     * @param integer $intValue
     */
    public function setIntValue($intValue)
    {
        $this->intValue = $intValue;
        return $this;
    }

    /**
     * @return integer
     */
    public function getIntValue()
    {
        return $this->intValue;
    }
    
    /**
     * @param string $stringValue
     */
    public function setStringValue($stringValue)
    {
        $this->stringValue = $stringValue;
        return $this;
    }

    /**
     * @return string
     */
    public function getStringValue()
    {
        return $this->stringValue;
    }	
    
    
	
    /**
     * @param \Datetime $dateValue
     */
    public function setDateValue($dateValue)
    {
        $this->dateValue = $dateValue;
        return $this;
    }

    /**
     * @return \Datetime
     */
    public function getDateValue()
    {
        return $this->dateValue;
    }
    
    
}

