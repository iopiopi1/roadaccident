<?php
/**
 * Created by PhpStorm.
 * User: ???????
 * Date: 28.02.2016
 * Time: 20:00
 */

namespace Application\Service;
use Application\Service\EntityServiceAbstract;
use Doctrine\ORM\Tools\Pagination\Paginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;
use Doctrine\ORM\EntityRepository;

class VehicleService extends EntityServiceAbstract {

    protected $entityManager = null;

    public function getVehicleById($vehicle_id)
    {
        $vehicle = $this->entityManager->find('\Application\Entity\Vehicle',$vehicle_id);
        return $vehicle;
    }

    public function getImages($vehicle_id)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('i')
            ->from('\Application\Entity\Image', 'i')
            ->andWhere('i.vehicle = :vehicle_id')
            ->setParameter('vehicle_id', $vehicle_id)
            ->andWhere('i.status = 0');

        $query = $qb->getQuery();
        $images_array = $query->getScalarResult();

        $images = [];
        foreach ($images_array as $image) {
            if($image['i_type'] == \Application\Entity\Image::TYPE_IMAGE){
                $images[] = array('path' => $image['i_path'], 'name' => $image['i_name'], 'thumbnail' => null);
            }
        }
        foreach ($images_array as $image) {
            if($image['i_type'] = \Application\Entity\Image::TYPE_THUMBNAIL){
                foreach($images as $key => $img){
                    if($image['i_name'] == $img['name']){
                        $images[$key]['thumbnail'] = $image['i_path'];
                    }
                }
            }
        }

        return $images;
    }
	
	public function getTopImages($top)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('i')
            ->from('\Application\Entity\Image', 'i')
			->innerJoin('\Application\Entity\Vehicle', 'v', 'i.vehicle = v.id')
            ->andWhere('v.status = :status')
            ->setParameter('status', 0)
			->setMaxResults($top);

        $query = $qb->getQuery();
        $images_array = $query->getScalarResult();
		
		$images = [];
        foreach ($images_array as $image) {
            if($image['i_type'] == \Application\Entity\Image::TYPE_IMAGE){
                $images[] = array('path' => $image['i_path'], 'name' => $image['i_name'], 'thumbnail' => null, 'vehicle' => $image['i_vehicle']);
            }
        }
        foreach ($images_array as $image) {
            if($image['i_type'] = \Application\Entity\Image::TYPE_THUMBNAIL){
                foreach($images as $key => $img){
                    if($image['i_name'] == $img['name']){
                        $images[$key]['thumbnail'] = $image['i_path'];
                    }
                }
            }
        }
        return $images;
    }

    public function save($entity)
    {
        $this->entityManager->persist($entity);
        $this->entityManager->flush();

        return $entity;
    }

    public function setEntityManager($em){
        $this->entityManager = $em;
        return $this;
    }

    public function getEntityManager()
    {
        return $this->entityManager;
    }

} 