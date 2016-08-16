<?php
/**
 * Created by PhpStorm.
 * User: Ярослав
 * Date: 06.03.2016
 * Time: 13:44
 */

namespace Application\Service;


class AdminService {



    public function setEntityManager($em){
        $this->entityManager = $em;
        return $this;
    }

    public function getEntityManager()
    {
        return $this->entityManager;
    }
}