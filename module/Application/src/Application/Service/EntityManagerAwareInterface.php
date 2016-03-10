<?php
/**
 * Created by PhpStorm.
 * User: Ярослав
 * Date: 28.02.2016
 * Time: 19:59
 */

namespace Application\Service;


interface EntityManagerAwareInterface
{
    public function setEntityManager($entityManager);
    public function getEntityManager();
}