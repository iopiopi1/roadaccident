<?php
/**
 * Created by PhpStorm.
 * User: Ярослав
 * Date: 06.03.2016
 * Time: 13:44
 */
 
namespace Application\Service;

use Zend\Paginator\Paginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;

class AdminService {
	
	public function getAllNewImages(){

        $em = $this->getEntityManager();
        $query = $em->createQuery('SELECT i, u FROM Application\Entity\Image i JOIN Application\Entity\User u WITH i.user = u.id WHERE i.status = 1 ORDER BY i.uniqueId ASC, i.type DESC');
		$query->setMaxResults(200);
        $resultSet = $query->getScalarResult();
/*
        
		*/
		//print_r($resultSet);
		$images = [];
	
		foreach($resultSet as $row){
			//if($row['i_uniqueId'] == 'image58adeb298f1440.69218429'){print_r($row);}
			if($row['i_type'] == 0){
				$images[$row['i_uniqueId']]['primaryPicPath'] = $row['i_path'];
				$images[$row['i_uniqueId']]['dateCreated'] = $row['i_dateCreated'];
				$images[$row['i_uniqueId']]['username'] = $row['u_username'];
				$images[$row['i_uniqueId']]['id'] = $row['i_id'];
				$images[$row['i_uniqueId']]['userId'] = $row['u_id'];
				$images[$row['i_uniqueId']]['name'] = $row['i_name'];
			}else{
				$images[$row['i_uniqueId']]['thumbnailPicPath'] = $row['i_path'];
			}

        }
		//print_r($images['image58adeb298f1440.69218429']);
        return $images;
    }
	
    public function getAllBrands(){

        $em = $this->getEntityManager();
        $query = $em->createQuery('SELECT b.name AS bname, b.id AS bid, s.name AS sname FROM Application\Entity\Brand b JOIN Application\Entity\Supplier s WITH s.id=b.supplier  WHERE b.status = 0');
        $resultSet = $query->getScalarResult();
/*
        foreach($resultSet as $row){
            $result[$row['sname']] = $row['bname'];
        }
		print_r($resultSet);*/
        return $resultSet;
    }

    public function getAllSuppliers(){

        $em = $this->getEntityManager();
        $query = $em->createQuery('SELECT s.name AS sname, s.id AS sid FROM Application\Entity\Supplier s WHERE s.status = 0');
        $resultSet = $query->getScalarResult();

        foreach($resultSet as $row){
            $result[] = $row['sname'];
        }
        return $result;
    }

    public function setEntityManager($em){
        $this->entityManager = $em;
        return $this;
    }

	public function getVehicles($page, $itemsPerPage)
    {
        $em = $this->getEntityManager()->createQueryBuilder();
        $em->select('i','v')
            ->from('\Application\Entity\Image', 'i')
			->innerJoin('\Application\Entity\Vehicle', 'v', 'WITH', 'i.vehicle = v.id')
            ->andWhere('v.status = :status')
            ->setParameter('status', \Application\Entity\Vehicle::STATUS_ACTIVE)
			->andWhere('i.type = :type')
			->setParameter('type', \Application\Entity\Image::TYPE_THUMBNAIL);
			
        $query = $em->getQuery();
		$resultSet = $query->getScalarResult();
		
		$paginator = new Paginator(new \Zend\Paginator\Adapter\ArrayAdapter($resultSet));

		$paginator
			->setCurrentPageNumber($page)
			->setItemCountPerPage($itemsPerPage);
		/*
		foreach($paginator as $row){
			if(get_class($row) == 'Application\Entity\Vehicle'){
				$paginator2['vehicle'][] = $row;
			}
			elseif(get_class($row) == 'Application\Entity\Image'){
				$paginator2['image'][] = $row;
			}
		}*/
		
		return $paginator;
	}


	public function getUsers($page, $itemsPerPage)
    {
        $em = $this->getEntityManager()->createQueryBuilder();
        $em->select('u')
            ->from('\Application\Entity\User', 'u');
			
        $query = $em->getQuery();
		$resultSet = $query->getScalarResult();
		$paginator = new Paginator(new \Zend\Paginator\Adapter\ArrayAdapter($resultSet));

		$paginator
			->setCurrentPageNumber($page)
			->setItemCountPerPage($itemsPerPage);
		/*
		foreach($paginator as $row){
			if(get_class($row) == 'Application\Entity\Vehicle'){
				$paginator2['vehicle'][] = $row;
			}
			elseif(get_class($row) == 'Application\Entity\Image'){
				$paginator2['image'][] = $row;
			}
		}*/
		
		return $paginator;
	}
	
    public function getEntityManager()
    {
        return $this->entityManager;
    }
}