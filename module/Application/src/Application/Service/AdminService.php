<?php
/**
 * Created by PhpStorm.
 * User: Ярослав
 * Date: 06.03.2016
 * Time: 13:44
 */

namespace Application\Service;


class AdminService {
    public function getAllBrands(){

        $em = $this->getEntityManager();
        $query = $em->createQuery('SELECT b.name AS bname, b.id AS bid, s.name AS sname FROM Application\Entity\Brand b JOIN Application\Entity\Supplier s WITH s.id=b.supplier  WHERE b.status = 0');
        $resultSet = $query->getScalarResult();

        foreach($resultSet as $row){
            $result[$row['sname']] = $row['bname'];
        }
        return $result;
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