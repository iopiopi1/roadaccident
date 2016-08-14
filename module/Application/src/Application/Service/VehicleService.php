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

    public function getUserById($user_id)
    {
        $user = $this->entityManager->find('\Application\Entity\User',$user_id);
        return $user;
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
			->innerJoin('\Application\Entity\Vehicle', 'v', 'WITH', 'i.vehicle = v.id')
            ->andWhere('v.status = :status')
            ->setParameter('status', 0)
			->andWhere('i.type = :type')
			->setParameter('type', \Application\Entity\Image::TYPE_IMAGE)
            ->setFirstResult(0)
			->setMaxResults($top);

        $query = $qb->getQuery();
        $images_array = $query->getScalarResult();
		
		$images = [];
        foreach ($images_array as $key => $image) {
            $images[] = array(
				'path' => $image['i_path'], 
				'name' => $image['i_name'], 
				'thumbnail' => 'public/images/vehicles/' . $image['i_vehicle'] . '/thumbnail/' . $image['i_name'], 
				'vehicle' => $image['i_vehicle'],
			);	
        }
        return $images;
    }
	
	public function getVehiclesByMatching($vehicleData)
    {
		$qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('b,s')
            ->from('\Application\Entity\Brand', 'b')
			->innerJoin('\Application\Entity\Supplier', 's', 'WITH', 'b.supplier = s.id')
            ->andWhere('s.status = :status')
			->andWhere('b.status = :status')
			->andWhere("s.name LIKE :param1 OR b.name LIKE :param1 OR :param2 LIKE CONCAT(CONCAT(CONCAT('%',CONCAT(s.name,'%')),b.name),'%')")
            ->setParameter('param1', $vehicleData.'%')
            ->setParameter('param2', $vehicleData)
            ->setParameter('status', 0)
            ->setFirstResult(0)
			->setMaxResults(6);
        $query = $qb->getQuery();
		$vehicles = [];
        //$vehicles_array = $dql->getResult();
        //print_r($vehicles_array);
        $vehicles_array = $query->getScalarResult();

		foreach ($vehicles_array as $key => $vehicle) {
			$vehicles[] = array('name' => $vehicle['s_name'] . ' ' . $vehicle['b_name'],
                                'brand_id' => $vehicle['b_id']);
		}
		
		return $vehicles;
	}

    public function createRegnumImage($regnum, $img_path)
    {
        $regnumBase = substr($regnum,0,6);
        $regnumRegionLength = iconv_strlen($regnum) - iconv_strlen($regnumBase);
        $regnumRegion = substr($regnum,-$regnumRegionLength);

        $im = imagecreatefrompng("public/img/regnum_template.png");
        $black = imagecolorallocate($im, 43, 42, 40);
        $font = 'public/fonts/arial.ttf';
        imagettftext($im, 60, 0, 40, 80, $black, $font, $regnumBase);
        imagettftext($im, 40, 0, 350, 60, $black, $font, $regnumRegion);
        imagepng($im,$img_path . '/regnum.png');
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

    public function getBrandSupplierNameById($brand_id)
    {
        $brand = $this->entityManager->find('\Application\Entity\Brand',$brand_id);
        $supplier = $this->entityManager->find('\Application\Entity\Supplier',$brand->getSupplier());
        $brandName = $supplier->getName() . " " . $brand->getName();
        return $brandName;
    }

    public function correctRegnum($oldRegnum){
        $upperCase = mb_strtoupper($oldRegnum, 'UTF-8');
        $newRegnum = '';
        $letters = array(ord('А') => ord('A'), ord('В') => ord('B'), ord('Е') => ord('E'), ord('К') => ord('K'), ord('М') => ord('M'), ord('Н') => ord('H'), ord('О') => ord('O'),
                        ord('Р') => ord('P'), ord('С') => ord('C'), ord('Т') => ord('T'), ord('У') => ord('Y'),ord('Х') => ord('X'));
        print_r($letters);
        for($i = 0; $i < strlen($upperCase); $i++){
            $char = substr( $upperCase, $i, 1 );
            //echo $char;
            print_r($letters);
            if(in_array(ord($char), array_keys(letters))){
                $char = $letters[$char];
                echo $char.'<br/>';
            }
            $newRegnum .= chr($char);
        }
        echo $newRegnum;
        return $newRegnum;
    }
} 