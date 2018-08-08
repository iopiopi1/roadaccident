<?php
/**
 * Created by PhpStorm.
 * User: Ярослав
 * Date: 28.02.2016
 * Time: 19:58
 */

namespace Application\Service;


abstract class EntityServiceAbstract implements EntityManagerAwareInterface
{
    /** @var \Doctrine\ORM\EntityManager */
    protected $entityManager;

    /**
     * @param $entityManager
     */
    public function setEntityManager($entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @return \Doctrine\ORM\EntityManager
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }
}