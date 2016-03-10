<?php
/**
 * Created by PhpStorm.
 * User: Ярослав
 * Date: 22.02.2016
 * Time: 1:33
 */

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="image")
 */
class Image
{

    const TYPE_IMAGE = 0;
    const TYPE_THUMBNAIL = 1;

    /**
     * Primary Identifier
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     */
    protected $path;

    /**
     * @ORM\Column(type="integer")
     */
    protected $status;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $dateCreated;

    /**
     * @ORM\Column(type="integer")
     */
    protected $vehicle;

    /**
     * @ORM\Column(type="integer")
     */
    protected $type;

    /**
     * @ORM\Column(type="string")
     */
    protected $name;

    public function __construct()
    {
        $this->setDateCreated(new \DateTime("now"));
    }

    /**
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
     * @param string $path
     */
    public function setPath($path)
    {
        $this->path = $path;
        return $this;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
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
     * @param integer $vehicle
     */
    public function setVehicle($vehicle)
    {
        $this->vehicle = $vehicle;
        return $this;
    }

    /**
     * @return integer
     */
    public function getVehicle()
    {
        return $this->vehicle;
    }

    /**
     * @param integer $type
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return integer
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

}