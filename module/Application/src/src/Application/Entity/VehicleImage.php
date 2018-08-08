<?php
/**
 * Created by PhpStorm.
 * User: Ярослав
 * Date: 23.02.2016
 * Time: 1:35
 */


namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="vehicleImage")
 */
class VehicleImage
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
    protected $vehicle_id;

    /**
     * @ORM\Column(type="integer")
     */
    protected $image_id;

    /**
     * @ORM\Column(type="integer")
     */
    protected $status;

}