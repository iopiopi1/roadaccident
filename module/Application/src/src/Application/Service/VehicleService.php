<?php
/**
 * Created by PhpStorm.
 * User: ???????
 * Date: 28.02.2016
 * Time: 20:00
 */

namespace Application\Service;
use Application\Service\EntityServiceAbstract;
use Doctrine\ORM\EntityRepository;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as PageAdapter;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\Paginator\Paginator as ZendPaginator;

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

    public function getImages2($vehicle_id)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('i')
            ->from('\Application\Entity\Image', 'i')
            ->andWhere('i.vehicle = :vehicle_id')
            ->setParameter('vehicle_id', $vehicle_id)
			->andWhere('i.type = :type')
            ->setParameter('type', \Application\Entity\Image::TYPE_IMAGE)
            ->andWhere('i.status = 0')
			;

        $query = $qb->getQuery();
        $images_array = $query->getScalarResult();

        return $images_array;
    }
	
	public function getTopImages($top)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('i','v')
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
		
		$vehiclesUnique = [];
		$vehiclesUniqueId = [];
		foreach($images_array as $vehicle){
			if(!in_array($vehicle['v_id'], $vehiclesUniqueId)){
				$vehiclesUnique[] = $vehicle;
				$vehiclesUniqueId[] = $vehicle['v_id'];
			}
		}
		
		$images_array = $vehiclesUnique;
		
		$images = [];
        foreach ($images_array as $key => $image) {
            $images[] = array(
				'path' => $image['i_path'], 
				'name' => $image['i_name'], 
				'thumbnail' => 'public/images/vehicles/' . $image['i_vehicle'] . '/thumbnail/' . $image['i_name'], 
				'vehicle' => $image['i_vehicle'],
                'regnum' => $image['v_regnum'],
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
        $regnumBase = mb_substr($regnum,0,6);
        $regnumRegionLength = iconv_strlen($regnum) - iconv_strlen($regnumBase);
        $regnumRegion = mb_substr($regnum,-$regnumRegionLength);

        $im = imagecreatefrompng("public/img/regnum_template.png");
        $black = imagecolorallocate($im, 43, 42, 40);
        $font = 'public/fonts/arial.ttf';
        imagettftext($im, 60, 0, 10, 80, $black, $font, $regnumBase);
        imagettftext($im, 40, 0, 350, 60, $black, $font, $regnumRegion);
        imagepng($im,$img_path . '/regnum.png');
    }

    public function createWatermark($img_path)
    {
        //$im = imagecreatefrompng("public/img/regnum_template.png");
		$im = @ImageCreateFromJPEG ($img_path) or // Read JPEG Image
            $im = @ImageCreateFromPNG ($img_path) or // or PNG Image
                $im = @ImageCreateFromGIF ($img_path) or // or GIF Image
                    $im = false; // If image is not JPEG, PNG, or GIF
        //$black = imagecolorallocate($im, 43, 42, 40);
		$black = imagecolorallocatealpha($im, 0, 0, 0, 90);
        $font = 'public/fonts/DaysOne-Regular.ttf';
        imagettftext($im, 60, 0, 10, 80, $black, $font, 'CARDAM.RU');
			@imagejpeg($im,$img_path) or
				@imagepng($im,$img_path);
				
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
        mb_internal_encoding("UTF-8");
        $upperCase = mb_strtoupper($oldRegnum, 'UTF-8');
        $newRegnum = '';
        $letters = array('А' => 'A', 'В' => 'B', 'Е' => 'E', 'К' => 'K', 'М' => 'M', 'Н' => 'H', 'О' => 'O',
            'Р' => 'P', 'С' => 'C', 'Т' => 'T', 'У' => 'Y','Х' => 'X');
        for($i = 0; $i < mb_strlen($upperCase); $i++){
            $char = mb_substr( $upperCase, $i, 1 );
            if(in_array($char, array_keys($letters))){
                $char = $letters[$char];
            }
            $newRegnum .= $char;
        }
        return $newRegnum;
    }
/*
	public function changeCyrToLat($regnum){

        $cyr = [
            'a','b','e','h','k','m','o','p','c','t','y','x',
            'а','в','е','н','к','м','о','р','с','т','у','х',
            'А','В','Е','Н','К','М','О','Р','С','Т','У','Х',
        ];
        $lat = [
			'A','B','E','H','K','M','O','P','C','T','Y','X',
			'A','B','E','H','K','M','O','P','C','T','Y','X',
            'А','В','Е','Н','К','М','О','Р','С','Т','У','Х'
        ];
		
        $textlat = str_replace($lat, $cyr, $regnum);
        
		return $textlat;
	}*/
	
	public function getAllVehicles($offset, $limit)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();

        $qb->select('v')
            ->from('\Application\Entity\Vehicle', 'v')
			->where('v.status = 0')
			//->setFirstResult($offset)
			//->setMaxResults($limit)
			;
        $query = $qb->getQuery();
		
		$result = ($query->getScalarResult());
		$paginator = new ZendPaginator(new \Zend\Paginator\Adapter\ArrayAdapter($result));
		$paginator->setCurrentPageNumber($offset);
		$paginator->setItemCountPerPage($limit);
        return $paginator;
    }
	
	public function getPagedVehicles($offset = 0, $limit = 10)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();

        $qb->select('v,s,b,i')
            ->from('\Application\Entity\Vehicle', 'v')
			->innerJoin('\Application\Entity\Brand', 'b', 'WITH', 'v.brand = b.id')
			->innerJoin('\Application\Entity\Supplier', 's', 'WITH', 'b.supplier = s.id')
			->innerJoin('\Application\Entity\Image', 'i', 'WITH', 'i.vehicle = v.id')
			->where('v.status = 0')
			->andWhere('i.type = 1');
        $query = $qb->getQuery();
	
		//print_r($query->getScalarResult());
		$result = ($query->getScalarResult());
		//$paginator = new ZendPaginator(new PageAdapter(new ORMPaginator($query)));
		$paginator = new ZendPaginator(new \Zend\Paginator\Adapter\ArrayAdapter($result));
        return $paginator;
    }
	
} 