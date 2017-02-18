<?php
/**
 * Created by PhpStorm.
 * User: Ярослав
 * Date: 06.03.2016
 * Time: 13:44
 */
 
namespace Application\Service;


class AdminService {
	
	public function getAllNewImages(){

        $em = $this->getEntityManager();
        $query = $em->createQuery('SELECT i, u FROM Application\Entity\Image i JOIN Application\Entity\User u WITH i.user = u.id WHERE i.status = 1 ORDER BY i.uniqueId ASC, i.type DESC');
		$query->setMaxResults(100);
        $resultSet = $query->getScalarResult();
/*
        
		print_r($resultSet);*/
		
		$images = [];
	
		foreach($resultSet as $row){
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

    public function getEntityManager()
    {
        return $this->entityManager;
    }
}