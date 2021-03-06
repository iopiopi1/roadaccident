<?php

namespace Application\Entity;

/**
 * VehicleImage
 */
class VehicleImage
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $vehicle_id;

    /**
     * @var integer
     */
    private $image_id;

    /**
     * @var integer
     */
    private $status;


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
     * Set vehicleId
     *
     * @param integer $vehicleId
     *
     * @return VehicleImage
     */
    public function setVehicleId($vehicleId)
    {
        $this->vehicle_id = $vehicleId;
    
        return $this;
    }

    /**
     * Get vehicleId
     *
     * @return integer
     */
    public function getVehicleId()
    {
        return $this->vehicle_id;
    }

    /**
     * Set imageId
     *
     * @param integer $imageId
     *
     * @return VehicleImage
     */
    public function setImageId($imageId)
    {
        $this->image_id = $imageId;
    
        return $this;
    }

    /**
     * Get imageId
     *
     * @return integer
     */
    public function getImageId()
    {
        return $this->image_id;
    }

    /**
     * Set status
     *
     * @param integer $status
     *
     * @return VehicleImage
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
}

