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

class SearchService extends EntityServiceAbstract {
	
    protected $entityManager = null;
	
	public function getRegnumMatches($regnum){
		$regnum = str_replace(' ','',$regnum);
		if ($regnum == ''){
			$regnum = '(_o_)';
		}
		$qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('v','i')
            ->from('\Application\Entity\Vehicle', 'v')
			->leftJoin('\Application\Entity\Image', 'i', 'WITH', 'v.id = i.vehicle')
            ->andWhere('LOWER(v.regnum) LIKE LOWER(:regnum)')
            ->setParameter('regnum', '%' . $regnum . '%')
			->andWhere('i.type = :type')
			->setParameter('type', \Application\Entity\Image::TYPE_THUMBNAIL);

        $query = $qb->getQuery();
        $vehicles = $query->getScalarResult();
		//print_r($vehicles);
		$vehiclesUnique = [];
		$vehiclesUniqueId = [];
		foreach($vehicles as $vehicle){
			if(!in_array($vehicle['v_id'], $vehiclesUniqueId)){
				$vehiclesUnique[] = $vehicle;
				$vehiclesUniqueId[] = $vehicle['v_id'];
			}
		}
		//print_r($vehiclesUnique);
		return 	$vehiclesUnique;	
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
