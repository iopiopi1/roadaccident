<?php
/**
 * Created by PhpStorm.
 * User: Ярослав
 * Date: 28.02.2016
 * Time: 20:00
 */

namespace Application\Service;
use Application\Service\EntityServiceAbstract;
use Doctrine\ORM\Tools\Pagination\Paginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;
use Doctrine\ORM\EntityRepository;

class UserService extends EntityServiceAbstract {

    protected $entityManager = null;

    public function save($user)
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
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