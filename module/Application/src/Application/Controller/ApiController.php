<?php
/**
 * Created by PhpStorm.
 * User: Ярослав
 * Date: 05.03.2016
 * Time: 16:55
 */

namespace Application\Controller;


use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Zend\Session\Container;
use Zend\Paginator\Paginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;

class ApiController extends AbstractActionController
{
    /** @var \Application\Service\ApiService */
    protected $serviceApi = null;
	
	/** @var \Application\Service\UserService */
    protected $serviceUser = null;
	
	/** @var \Application\Service\VehicleService */
    protected $serviceVehicle = null;
	
	protected $user_session = null;
	protected $viewHelperManager = null;
	
    const FOLDER_IMG_TMP = "images/vehicles_tmp";
    const FOLDER_IMG = "images/vehicles";

    function __construct($serviceApi, $serviceUser, $entityManager, $serviceVehicle, $viewHelperManager, $serviceSearch)
    {
        $this->serviceApi = $serviceApi;
		$this->serviceUser = $serviceUser;
		$this->entityManager = $entityManager;
		$this->serviceVehicle = $serviceVehicle;
		$this->serviceSearch = $serviceSearch;
		$this->user_session = new Container('user');
		$this->viewHelperManager = $viewHelperManager;
    }
			
	public function registerconfirmAction()
    {	
        $hashcode = $this->params()->fromRoute('url');
		$user_id = $this->serviceUser->setUserActive($hashcode);
		
		if($user_id > 0){
			
			$this->serviceUser->updateById($user_id);
			
			$result = 'success';
		}
		else{
			$result = 'failed';
		}
		
        return new ViewModel(
            array(
                'success' => $result,
            )
        );
    }
	
	public function addimageajaxAction()
    {

        $response = array(
            'files' => array()
        );

        $request = $this->getRequest();
        if ($request->isPost()) {
            $post = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );

            $vehicle_uid = $post['vehicle_uid'];
			$vehicle_id = $post['vehicle_id'];

            try {
				if(!$vehicle_uid > 0){
					$vehicle_uid = $vehicle_id;
				}
				if(!is_dir('public/' . self::FOLDER_IMG_TMP . DIRECTORY_SEPARATOR  . $vehicle_uid))
						mkdir('public/' . self::FOLDER_IMG_TMP . DIRECTORY_SEPARATOR  . $vehicle_uid);
            }
            catch(Exception $e)
            {
                echo $e->getMessage();
            }

            $uploaded = 0;
            $uniqid = uniqid();
            foreach($post['files'] as $counter => $image)
            {	//echo 'public/' . self::FOLDER_IMG_TMP . DIRECTORY_SEPARATOR  . $vehicle_uid . DIRECTORY_SEPARATOR . $uniqid . '.' . $extension;
				preg_match('/.(gif|jpg|jpeg|tiff|png)$/', strtolower($image['name']), $emageInfo, PREG_OFFSET_CAPTURE);
				$extension = $emageInfo[1][0];
				//resizing pictures
				$image['tmp_name'] = $this->serviceApi->reduceImageSize($image['tmp_name'], 'public/' . self::FOLDER_IMG_TMP . '/' . uniqid() . '.' . $extension, 800);
				if(rename($image['tmp_name'], 'public/' . self::FOLDER_IMG_TMP . DIRECTORY_SEPARATOR  . $vehicle_uid . DIRECTORY_SEPARATOR . $uniqid . '.' . $extension))
                {
                    $filePath = self::FOLDER_IMG_TMP . DIRECTORY_SEPARATOR  . $vehicle_uid . DIRECTORY_SEPARATOR . $uniqid . '.' . $extension;
                    if($this->serviceApi->createThumbnail($uniqid . '.' . $extension, 'public/' . self::FOLDER_IMG_TMP . DIRECTORY_SEPARATOR . $vehicle_uid))
                    {
                        $thumbnail = self::FOLDER_IMG_TMP . DIRECTORY_SEPARATOR  . $vehicle_uid . DIRECTORY_SEPARATOR . \Application\Service\ApiService::FOLDER_THUMBNAIL . DIRECTORY_SEPARATOR . $uniqid . '.' . $extension;
                    }
                    //else $thumbnail = str_replace("public/", "", $post['target']) . DIRECTORY_SEPARATOR . $image['name'];
					$this->serviceVehicle->createWatermark('public/' . self::FOLDER_IMG_TMP . DIRECTORY_SEPARATOR  . $vehicle_uid . DIRECTORY_SEPARATOR . $uniqid . '.' . $extension);
                    $response['files'][] = array(
                        'name' => $uniqid . '.' . $extension,
                        'size' => filesize('public/'.$filePath),
                        'url' => str_replace("\\", "/", str_replace("public/", "", $filePath)),
                        'thumbnailUrl' => str_replace("\\", "/", $thumbnail),
                        "deleteUrl" => str_replace("\\", "/", str_replace("public/", "", $filePath)),
                        "deleteType" => "DELETE",
						"error" => "noError",
                    );
                    $uploaded++;
					
					// Saving pics in Image table
					foreach($response['files'] as $file){
						$uniqueId = uniqid('image', true);
						// common pic
						$imageDB = new \Application\Entity\Image();
						$imageDB->setPath($file['url']);
						$imageDB->setStatus(1);
						$imageDB->setType(0);
						$imageDB->setName($file['name']);
						$imageDB->setUser($this->user_session->id);	
						$imageDB->setUniqueId($uniqueId);	
						$this->serviceApi->save($imageDB);
						
						// thumbnail pic
						$imageDB = new \Application\Entity\Image();
						$imageDB->setStatus(1);
						$imageDB->setName($file['name']);
						$imageDB->setUser($this->user_session->id);
						$imageDB->setPath($file['thumbnailUrl']);
						$imageDB->setType(1);
						$imageDB->setUniqueId($uniqueId);
						$this->serviceApi->save($imageDB);		
						
					}
						
                }
                else
                {
				
                    $response['files'][] = array(
                        'name' => $image['tmp_name'],
                        "error" => "Upload failed"
                    );
                }
            }

            // if upload failed, delete the directory
            if($uploaded == 0)
            {
                rmdir('public/'.self::FOLDER_IMG_TMP . DIRECTORY_SEPARATOR  . $vehicle_uid);
            }
			//else send notification
			else{
				$recipient = 'admin@cardam.ru';
				$subject="Господин, добавлены новые фотки на cardam.ru";
				$headers = "MIME-Version: 1.0\r\n";
				$headers .= "Content-Type: text/html; charset=UTF-8\r\n";
				$headers .= 'From: info@cardam.ru ';
				$extraHeader = "-f info@cardam.ru";
				$mail_body = 'Челом бью и докладываю, были добавлены новые фотки';
				
				$email = new \Application\Entity\Email();
				$email->setRecipient($recipient);
				$email->setSubject($subject);
				$email->setHeaders($headers);
				$email->setMailBody($mail_body);
				$email->setStatus(\Application\Entity\Email::STATUS_CREATED);
				$email->setExtraHeader($extraHeader);
				$this->entityManager->persist($email);
				$this->entityManager->flush();	
				
			}
			
			
			
        }
		
		$response['uploaded'] = $uploaded;
		
        return new JsonModel(
            $response
        );
    }
	
    public function addvehicleajaxAction()
    {

        $response = array(
            'files' => array()
        );

        $request = $this->getRequest();
        if ($request->isPost()) {
            $post = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );

            $vehicle_uid = $post['vehicle_uid'];
			$vehicle_id = $post['vehicle_id'];

            try {
				if(!$vehicle_uid > 0){
					$vehicle_uid = $vehicle_id;
				}
				if(!is_dir('public/' . self::FOLDER_IMG_TMP . DIRECTORY_SEPARATOR  . $vehicle_uid))
						mkdir('public/' . self::FOLDER_IMG_TMP . DIRECTORY_SEPARATOR  . $vehicle_uid);
            }
            catch(Exception $e)
            {
                echo $e->getMessage();
            }

            $uploaded = 0;
            $uniqid = uniqid();
            foreach($post['files'] as $counter => $image)
            {
                if(move_uploaded_file($image['tmp_name'], 'public/' . self::FOLDER_IMG_TMP . DIRECTORY_SEPARATOR  . $vehicle_uid . DIRECTORY_SEPARATOR . $uniqid . $image['name']))
                {
                    $filePath = self::FOLDER_IMG_TMP . DIRECTORY_SEPARATOR  . $vehicle_uid . DIRECTORY_SEPARATOR . $uniqid . $image['name'];
                    if($this->serviceApi->createThumbnail($uniqid . $image['name'], 'public/' . self::FOLDER_IMG_TMP . DIRECTORY_SEPARATOR . $vehicle_uid))
                    {
                        $thumbnail = self::FOLDER_IMG_TMP . DIRECTORY_SEPARATOR  . $vehicle_uid . DIRECTORY_SEPARATOR . \Application\Service\ApiService::FOLDER_THUMBNAIL . DIRECTORY_SEPARATOR . $uniqid . $image['name'];
                    }
                    //else $thumbnail = str_replace("public/", "", $post['target']) . DIRECTORY_SEPARATOR . $image['name'];

                    $response['files'][] = array(
                        'name' => $uniqid . $image['name'],
                        'size' => filesize('public/'.$filePath),
                        'url' => str_replace("\\", "/", str_replace("public/", "", $filePath)),
                        'thumbnailUrl' => str_replace("\\", "/", $thumbnail),
                        "deleteUrl" => str_replace("\\", "/", str_replace("public/", "", $filePath)),
                        "deleteType" => "DELETE"
                    );
                    $uploaded++;
                }
                else
                {
                    $response['files'][] = array(
                        'name' => $image['name'],
                        "error" => "Upload failed"
                    );
                }
            }

            // if upload failed, delete the directory
            if($uploaded == 0)
            {
                rmdir('public/'.self::FOLDER_IMG_TMP . DIRECTORY_SEPARATOR  . $vehicle_uid);
            }
        }//print_r($response);
        return new JsonModel(
            $response
        );
    }
		
    public function addbrandajaxAction()
    {
		$request = $this->getRequest();
        if ($request->isPost()) {
			$post = $request->getPost()->toArray();	
			$brand_existing = $this->entityManager->getRepository('\Application\Entity\Brand')->findOneBy(array('name' => $post['brandname']));
			if(is_null($brand_existing)){
				$brand = new \Application\Entity\Brand();
				$brand->setName($post['brandname']);
				$brand->setSupplier($post['suppliernames']);
				$brand->setStatus(0);
				$dt = new \DateTime();
				$brand->setDateCreated($dt);
				$brand->setDateModified($dt);
				$this->serviceApi->save($brand);
				
				$success = 'success';
			}
			else{
				$success = 'duplicated';
			}
		}
		
		return new JsonModel(
            array(
                'state' => $success,
            )
        );
		
	}
		
    public function addsupplierajaxAction()
    {
		$request = $this->getRequest();
        if ($request->isPost()) {
			$post = $request->getPost()->toArray();	
			$supplier_existing = $this->entityManager->getRepository('\Application\Entity\Supplier')->findOneBy(array('name' => $post['suppliername']));
			if(is_null($supplier_existing)){
				$supplier = new \Application\Entity\Supplier();
				$supplier->setName($post['suppliername']);
				$supplier->setStatus(0);
				$dt = new \DateTime();
				$supplier->setDateCreated($dt);
				$supplier->setDateModified($dt);
				$this->serviceApi->save($supplier);
				
				$success = 'success';
			}
			else{
				$success = 'duplicated';
			}
		}
		
		return new JsonModel(
            array(
                'state' => $success,
            )
        );
	}
	
	public function adminupdateimageAction()
	{	
		$state = 'success';
		$request = $this->getRequest();
        if ($request->isPost()) {
			$post = $request->getPost()->toArray();	
			$vehicle_existing = $this->entityManager->getRepository('\Application\Entity\Vehicle')->findOneBy(array('regnum' => $post['regNum']));
			$updateResults = $this->adminUpdateImage($vehicle_existing, $post, $post['regNum'], Array('typeAction' => 'FirstVehicle'));
			if(isset($post['regNum2']) && !is_null($post['regNum2']) && $post['regNum2'] != ''){
				$vehicle2_existing = $this->entityManager->getRepository('\Application\Entity\Vehicle')->findOneBy(array('regnum' => $post['regNum2']));
				$updateResults2 = $this->adminUpdateImage($vehicle2_existing, $post, $post['regNum2'], 
					Array(
						'typeAction' => 'SecondVehicle',
						'thumbnailPath' => $updateResults['thumbnailPath'],
						'primaryPath' => $updateResults['primaryPath']
					)
				);
			}
		}
		
		return new JsonModel(
            array(
                'state' => $state,
            )
        );
	}
	
	public function adminUpdateImage($vehicle_existing, $post, $regnum, $extraParameters){
		if(is_null($vehicle_existing)){
			// new regnum
			$vehicle = new \Application\Entity\Vehicle();
			$vehicle->setStatus(0);
			
			// change cyrillic chars
			$latRegnum = $this->serviceVehicle->correctRegnum($regnum);
			
			$vehicle->setRegnum($latRegnum);
			$vehicle->setDateEdited(new \DateTime("now"));
			$vehicle->setUser($post['userId']);	
			$this->serviceApi->save($vehicle);
			
			$vehicleId = $vehicle->getId();
			$img_path = 'public/' . $this::FOLDER_IMG . '/' . $vehicleId;
			$img_pathWithoutPublic = $this::FOLDER_IMG . '/' . $vehicleId;
			$thmbPathNew = $img_path . '/thumbnail/' . $post['dataPrimarypicName'];
			$primaryPathNew = $img_path . '/' . $post['dataPrimarypicName'];
			
			if (!is_dir($img_path)) {
				mkdir($img_path, 0777, true);
				mkdir($img_path . '/thumbnail/', 0777, true);
			}
			
			$thumbnailPath = $post['thumbnailPath'];
			$primaryPath = $post['primaryPath'];
			
			// if first pic then nothin else copy path from 1st vehicle
			if($extraParameters['typeAction'] == 'SecondVehicle'){
				$thumbnailPath = $extraParameters['thumbnailPath'];
				$primaryPath = $extraParameters['primaryPath'];
				/*echo $thumbnailPath . PHP_EOL;
				echo $primaryPath . PHP_EOL;
				echo $thmbPathNew . PHP_EOL;
				echo $primaryPathNew . PHP_EOL;*/
				if(copy($thumbnailPath,$thmbPathNew)
				&& copy($primaryPath,$primaryPathNew)){					
					$uniqueId2 = uniqid('image', true);
					// primary pic 
					$imageDB = new \Application\Entity\Image();
					$imageDB->setPath($img_pathWithoutPublic);
					$imageDB->setStatus(\Application\Entity\Image::STATUS_APPROVED);
					$imageDB->setType(\Application\Entity\Image::TYPE_IMAGE);
					$imageDB->setName($post['dataPrimarypicName']);
					$imageDB->setVehicle($vehicleId);
					$imageDB->setUser(1);	
					$imageDB->setUniqueId($uniqueId2);	
					$this->serviceApi->save($imageDB);
					// thumb pic 
					$imageDB = new \Application\Entity\Image();
					$imageDB->setPath($img_pathWithoutPublic);
					$imageDB->setStatus(\Application\Entity\Image::STATUS_APPROVED);
					$imageDB->setType(\Application\Entity\Image::TYPE_THUMBNAIL);
					$imageDB->setName($post['dataPrimarypicName']);
					$imageDB->setVehicle($vehicleId);
					$imageDB->setUser(1);	
					$imageDB->setUniqueId($uniqueId2);	
					$this->serviceApi->save($imageDB);
					
				}
			}
			else{
				if(rename($thumbnailPath,$thmbPathNew)
				&& rename($primaryPath,$primaryPathNew)){
					$qb = $this->entityManager->createQueryBuilder();
					$q = $qb->update('\Application\Entity\Image', 'i')
							->set('i.vehicle', '?1')
							->set('i.status', '?2')
							->set('i.path', '?3')
							->where('i.uniqueId = ?4')
							->setParameter(1, $vehicleId)
							->setParameter(2, \Application\Entity\Image::STATUS_APPROVED)
							->setParameter(3, $img_pathWithoutPublic)
							->setParameter(4, $post['picsId'])
							->getQuery();
					$p = $q->execute();
				}	
			}
			
			// create regnum image
			$this->serviceVehicle->createRegnumImage($latRegnum,$img_path);
			$server_url = $this->getRequest()->getUri()->getScheme() . '://' . $this->getRequest()->getUri()->getHost();
			$vehiclePath = 'vehicle/index/' . $vehicleId;
			$this->serviceUser->sendInfoApprovedImageEmail($post['userId'], $server_url, $thmbPathNew, $vehiclePath);
			$state = 'success';
			
		}
		else{
			// existing vehicle
			
			$img_path = 'public/' . $this::FOLDER_IMG . '/' . $vehicle_existing->getId();
			$img_pathWithoutPublic = $this::FOLDER_IMG . '/' . $vehicle_existing->getId();
			$thmbPathNew = $img_path . '/thumbnail/' . $post['dataPrimarypicName'];
			$primaryPathNew = $img_path . '/' . $post['dataPrimarypicName'];
			
			if (!is_dir($img_path)) {
				mkdir($img_path, 0777, true);
				mkdir($img_path . '/thumbnail/', 0777, true);
			}
			
			$thumbnailPath = $post['thumbnailPath'];
			$primaryPath = $post['primaryPath'];
			
			// if first pic then nothin else copy path from 1st vehicle
			if($extraParameters['typeAction'] == 'SecondVehicle'){
				$thumbnailPath = $extraParameters['thumbnailPath'];
				$primaryPath = $extraParameters['primaryPath'];
				
				if(copy($thumbnailPath,$thmbPathNew)
				&& copy($primaryPath,$primaryPathNew)){
					$uniqueId2 = uniqid('image', true);
					// primary pic 
					$imageDB = new \Application\Entity\Image();
					$imageDB->setPath($img_pathWithoutPublic);
					$imageDB->setStatus(\Application\Entity\Image::STATUS_APPROVED);
					$imageDB->setType(\Application\Entity\Image::TYPE_IMAGE);
					$imageDB->setName($post['dataPrimarypicName']);
					$imageDB->setVehicle($vehicle_existing->getId());
					$imageDB->setUser(1);	
					$imageDB->setUniqueId($uniqueId2);	
					$this->serviceApi->save($imageDB);
					// thumb pic 
					$imageDB = new \Application\Entity\Image();
					$imageDB->setPath($img_pathWithoutPublic);
					$imageDB->setStatus(\Application\Entity\Image::STATUS_APPROVED);
					$imageDB->setType(\Application\Entity\Image::TYPE_THUMBNAIL);
					$imageDB->setName($post['dataPrimarypicName']);
					$imageDB->setVehicle($vehicle_existing->getId());
					$imageDB->setUser(1);	
					$imageDB->setUniqueId($uniqueId2);	
					$this->serviceApi->save($imageDB);
				}
			}
			else{
				if(rename($thumbnailPath,$thmbPathNew)
				&& rename($primaryPath,$primaryPathNew)){
					$qb = $this->entityManager->createQueryBuilder();
					$q = $qb->update('\Application\Entity\Image', 'i')
							->set('i.vehicle', '?1')
							->set('i.status', '?2')
							->set('i.path', '?3')
							->where('i.uniqueId = ?4')
							->setParameter(1, $vehicle_existing->getId())
							->setParameter(2, \Application\Entity\Image::STATUS_APPROVED)
							->setParameter(3, $img_pathWithoutPublic)
							->setParameter(4, $post['picsId'])
							->getQuery();
					$p = $q->execute();
				}				
			
			}
			
			$server_url = $this->getRequest()->getUri()->getScheme() . '://' . $this->getRequest()->getUri()->getHost();
			$vehiclePath = 'vehicle/index/' . $vehicle_existing->getId();
			$this->serviceUser->sendInfoApprovedImageEmail($post['userId'], $server_url, $thmbPathNew, $vehiclePath);
			$state = 'success';
			
		}
		return Array(
			'thumbnailPath' => 'public/' . $img_pathWithoutPublic . '/thumbnail/' . $post['dataPrimarypicName'],
			'primaryPath' => 'public/' . $img_pathWithoutPublic . '/' . $post['dataPrimarypicName'],
		);
	}
	
	public function admindeleteimageAction(){
		
		$state;
		$request = $this->getRequest();
        if ($request->isPost()) {
			$post = $request->getPost()->toArray();
			$qb = $this->entityManager->createQueryBuilder();
			$q = $qb->update('\Application\Entity\Image', 'i')
					->set('i.status', '?1')
					->where('i.uniqueId = ?2')
					->setParameter(1, \Application\Entity\Image::STATUS_DELETED)
					->setParameter(2, $post['picsId'])
					->getQuery();
			$p = $q->execute();
			
			//Delete pics from FS
			//unlink(/*'public/' . */$post['thumbnailPath']);
			//unlink(/*'public/' . */$post['primaryPath']);
			
			$server_url = $this->getRequest()->getUri()->getScheme() . '://' . $this->getRequest()->getUri()->getHost();
			
			$reason = $post['textDeletion'];
			$userId = $post['userId']; 
			$this->serviceUser->sendInfoDeletionImageEmail($userId, $reason, $server_url, $post['thumbnailPath']);
			
			$state = 'success';
		}
		
		
		return new JsonModel(
            array(
                'state' => $state,
            )
        );
	}
	
	public function adminupdateuserAction(){
		
		$state;
		$request = $this->getRequest();
        if ($request->isPost()) {
			$post = $request->getPost()->toArray();
			$qb = $this->entityManager->createQueryBuilder();
			$q = $qb->update('\Application\Entity\User', 'u')
					->set('u.status', '?1')
					->where('u.id = ?2')
					->setParameter(1, \Application\Entity\User::STATUS_BLOCKED)
					->setParameter(2, $post['userId'])
					->getQuery();
			$p = $q->execute();
			
			$server_url = $this->getRequest()->getUri()->getScheme() . '://' . $this->getRequest()->getUri()->getHost();

			$this->serviceUser->sendInfoUserUpdateEmail($post['userId'], $post['textReason'], $server_url);
			
			$state = 'success';
		}
		else{
			$state = 'failed';
		}
		
		
		return new JsonModel(
            array(
                'state' => $state,
            )
        );
	}
	
	public function restorepassconfirmAction(){
		
		$hashcode = $this->params()->fromRoute('url');
		$user_id = $this->serviceUser->setUserActive($hashcode);
		
		if($user_id > 0){
			
			$this->serviceUser->updateById($user_id);
			
			$result = 'success';
		}
		else{
			$result = 'failed';
		}
		
        return new ViewModel(
            array(
                'success' => $result,
            )
        );
	}
	
	public function sendemailcronAction(){
		$request = $this->getRequest();
		$qb = $this->entityManager->createQueryBuilder();
        $qb->select('e')
            ->from('\Application\Entity\Email', 'e')
            ->where('e.status = ?1')
            ->setParameter(1, \Application\Entity\Email::STATUS_CREATED)
			->setFirstResult(0)
			->setMaxResults(30);
			
        $query = $qb->getQuery();
        $emails_array = $query->getScalarResult();

		foreach($emails_array as $email){
			if(mail($email['e_recipient'], $email['e_subject'], $email['e_mailBody'], $email['e_headers'], $email['e_extraHeader'])){
				$qb = $this->entityManager->createQueryBuilder();
				$q = $qb->update('\Application\Entity\Email', 'e')
					->set('e.status', '?1')
					->setParameter(1, \Application\Entity\Email::STATUS_SENT)
					->where('e.id = ?2')
					->setParameter(2, $email['e_id'])
					->getQuery();
				$p = $q->execute();
				
				echo "\r\n". 'Письмо '.$email['e_id'].' отправлено';
			}
			else{echo "\r\n". 'Письмо '.$email['e_id'].' НЕ ОТПРАВЛЕНО ' . 'Ошибка: ' . error_get_last()['message'];
			}
		}
		
		$result = 'success';
		
        return new ViewModel(
            array(
                'success' => $result,
            )
        );
	}
	
	public function unziploadimagesAction(){
		error_reporting(E_ALL & ~E_NOTICE);
		$filePath = $this->getRequest()->getParam('filePath');
		$newFilePath = 'public/files/unzipped/';
		$this->serviceApi->unzip($filePath, $newFilePath);
		/*foreach (new DirectoryIterator($newFilePath) as $fileInfo) {
			if($fileInfo->isDot()) continue;
			if(is_dir($fileInfo->getPathname())){
				
			}
			$file =  $path.$fileInfo->getFilename();
		}*/
		
		$iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($newFilePath), 
            \RecursiveIteratorIterator::SELF_FIRST);

		foreach($iterator as $file) {
			if(!$file->isDir()) {
				
				$uniqid = uniqid();
				$vehicle_uid = null;
				//foreach($post['files'] as $counter => $image){
				preg_match('/.(gif|jpg|jpeg|tiff|png)$/', strtolower($file->getFilename()), $emageInfo, PREG_OFFSET_CAPTURE);
				$extension = $emageInfo[1][0];
				echo $file->getPathname(). '.....' . 'public/' . self::FOLDER_IMG_TMP . DIRECTORY_SEPARATOR  . $uniqid . '.' . $extension . PHP_EOL;
				//print_r($extension);echo '>>>>>>>>';
				if(rename($file->getPathname(), 'public/' . self::FOLDER_IMG_TMP . DIRECTORY_SEPARATOR   . $uniqid . '.' . $extension))
				{ 
					$filePath = self::FOLDER_IMG_TMP . DIRECTORY_SEPARATOR  . $uniqid . '.' . $extension;
					if($this->serviceApi->createThumbnail($uniqid . '.' . $extension, 'public/' . self::FOLDER_IMG_TMP ))
					{
						$thumbnail = self::FOLDER_IMG_TMP . DIRECTORY_SEPARATOR   . \Application\Service\ApiService::FOLDER_THUMBNAIL . DIRECTORY_SEPARATOR . $uniqid . '.' . $extension;
					}
					
					// Saving pics in Image table
					$uniqueId = uniqid('image', true);
					// common pic
					$imageDB = new \Application\Entity\Image();
					$imageDB->setPath(self::FOLDER_IMG_TMP . DIRECTORY_SEPARATOR   . $uniqid . '.' . $extension);
					$imageDB->setStatus(1);
					$imageDB->setType(0);
					$imageDB->setName($uniqid . '.' . $extension);
					$imageDB->setUser(1);	
					$imageDB->setUniqueId($uniqueId);	
					$this->serviceApi->save($imageDB);
					
					// thumbnail pic
					$imageDB = new \Application\Entity\Image();
					$imageDB->setStatus(1);
					$imageDB->setName($uniqid . '.' . $extension);
					$imageDB->setUser(1);
					$imageDB->setPath($thumbnail);
					$imageDB->setType(1);
					$imageDB->setUniqueId($uniqueId);
					$this->serviceApi->save($imageDB);		
						
				}
				else
				{
					echo "Upload failed".PHP_EOL;
				}
			}
		}
	}
	
	public function updateoldimgsAction(){
		error_reporting(E_ALL & ~E_NOTICE);
		$filePath = $this->getRequest()->getParam('filePath');
		
		$iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($filePath, \RecursiveDirectoryIterator::SKIP_DOTS), 
            \RecursiveIteratorIterator::SELF_FIRST);

		foreach($iterator as $file) {
			if(!$file->isDir() && $file->getFilename() != 'regnum.png' && !strpos($file->getPathname(), 'thumbnail')  && !strpos($file->getPathname(), '.tmb')) {	
				preg_match('/.(gif|jpg|jpeg|tiff|png)$/', strtolower($file), $imageInfo, PREG_OFFSET_CAPTURE);
				$extension = $imageInfo[1][0];
				$x = @getimagesize($file->getPathname());
				if(max(array($x[0],$x[1])) != 800){
					if(in_array($extension, array('gif', 'jpg', 'jpeg', 'png'))){
						echo $file->getPathname() . PHP_EOL;
						$this->serviceApi->reduceImageSize($file->getPathname(), $file->getPathname(), 800);
						$this->serviceVehicle->createWatermark($file->getPathname());
					}
				}
				
				//resizing pictures
				//
				/*if()
				{ 
	
						
				}
				else
				{
					echo "Update failed".PHP_EOL;
				}*/
			}
		}
	}
	
	public function imgdeleteadminAction(){
		$request = $this->getRequest();
		$state = 'failed';
        if ($request->isPost()) {
			$post = $request->getPost()->toArray();	
			$image = $this->entityManager->getRepository('\Application\Entity\Image')->findOneBy(array('id' => $post['imgId']));
			$name = $image->getName();
			$vehicleId = $image->getVehicle();
			$uniqueId = $image->getUniqueId();
			$path = 'public' . DIRECTORY_SEPARATOR . $image->getPath();
			$thmbPath = $path . DIRECTORY_SEPARATOR . 'thumbnail' . DIRECTORY_SEPARATOR . $name;
			$imgPath = $path . DIRECTORY_SEPARATOR . $name;
			if(!is_null($image)){
				$qb = $this->entityManager->createQueryBuilder();
				$qb->delete('\Application\Entity\Image', 'i')
				->where('i.uniqueId = :uniqueId')
				->setParameter('uniqueId', $uniqueId);
				$query = $qb->getQuery();
				$result = $query->getResult();
				unlink($thmbPath);
				unlink($imgPath);
				
				$state = 'success';	
			}
		}
		
		
		return new JsonModel(
            array(
                'state' => $state,
            )
        );
		
	}
	
	public function editvehicleregnumAction(){
		$state = 'failed';
		$request = $this->getRequest();
        if ($request->isPost()) {
			$post = $request->getPost()->toArray();
			// change cyrillic chars
			$latRegnum = $this->serviceVehicle->correctRegnum($post['regNum']);
			$qb = $this->entityManager->createQueryBuilder();
			$q = $qb->update('\Application\Entity\Vehicle', 'v')
					->set('v.regnum', '?1')
					->where('v.id = ?2')
					->setParameter(1, $latRegnum)
					->setParameter(2, $post['vehicleId'])
					->getQuery();
			$p = $q->execute();			
			$state = 'success';
			
			$img_path = 'public/' . $this::FOLDER_IMG . '/' . $post['vehicleId'];
			
			if (!is_dir($img_path)) {
				mkdir($img_path, 0777, true);
				mkdir($img_path . '/thumbnail/', 0777, true);
			}
			else{
				if(!unlink($img_path . '/regnum.png')){
					$state = 'failed';
				}
			}
			
			$this->serviceVehicle->createRegnumImage($latRegnum,$img_path);
		}
		return new JsonModel(
            array(
                'state' => $state,
            )
        );
	}
		
	public function editvehiclesAction(){
		/*$page = $this->params()->fromRoute('page');
		if(is_null($page)){
			$page = 0;
		}*/
		$regnum = 'v.regnum';
		$state = 'failed';
		$request = $this->getRequest();
		$currentPage = 1;
        if ($request->isPost()) {
			$post = $request->getPost()->toArray();
			$currentPage = $post['page'];
			$latRegnum = $this->serviceVehicle->correctRegnum($post['regNum']);
		}
		$em = $this->entityManager->createQueryBuilder();
        $em->select('v','i')
            ->from('\Application\Entity\Vehicle', 'v')
			->leftJoin('\Application\Entity\Image', 'i', 'WITH', 'v.id = i.vehicle')
			->andWhere('LOWER(v.regnum) LIKE LOWER(:regnum)')
            ->setParameter('regnum', '%' . $latRegnum . '%')
			->andWhere('i.type = :type')
			->setParameter('type', \Application\Entity\Image::TYPE_THUMBNAIL)
			->orderBy('v.id', 'ASC');
			
        $query = $em->getQuery();
		$resultSet = $query->getScalarResult();
		//print_r($resultSet);
		$rsKey = [];
		$rsSet = [];
		foreach($resultSet as $row){
			if(!in_array($row['i_vehicle'], $rsKey)){
				$rsSet[] = $row;
				$rsKey[$row['i_vehicle']] = $row['i_vehicle'];
			}
		}
		
		$itemsPerPage = 20;
		$pages = count($rsSet);
		$totPages = ceil($pages/$itemsPerPage);
		$paginator = new Paginator(new \Zend\Paginator\Adapter\ArrayAdapter($rsSet));

		$paginator
			->setCurrentPageNumber($currentPage)
			->setItemCountPerPage($itemsPerPage);
			
		//$this->viewHelperManager;
		/*$paginationHelper = $this->viewHelperManager->get('paginationControl');
		print_r($paginationHelper);*/
		/*$paginatorView = $paginationHelper->paginationControl(
			$paginator,
			'sliding',
			'partial/paginator.phtml',
			array(
				'route' => 'admin'
			)
		);*///echo $paginatorView;
        $html = '';
		foreach($paginator as $vehicle){
			$html = $html .
			'<tr class="vehicleRow">'
				.'<th class="inpRegnumDbId">'
					.'<span>'.$vehicle['v_id'].'</span>'
				.'.</th>'
				.'<th class="inpRegnumImg">'
					.'<a href="/vehicle/index/'.$vehicle['v_id'].'">'
						.'<img class="imgImages" src="/public/'.$vehicle['i_path']. '/thumbnail/' . $vehicle['i_name'].'"/>'
					.'</a>'
				.'</th>'
				.'<th class="inpDateCreate">'
					.$vehicle['v_dateCreated']->format('d-m-Y')
				.'</th>'
				.'<th class="inpRegnum">'
					.'<input class="form-control"  type="text" value="'.$vehicle['v_regnum'].'" />'
				.'</th>'
				.'<th class="inpRegnumSave">'
					.'<input class="form-control"  type="button" value="Сохранить"/>'
				.'</th>'
				.'<th class="inpRegnumFile">'
					.'<a href="/admin/filemanager/'.$vehicle['v_id'].'">Файл Менеджер</a>'
				.'</th>'
			.'</tr>';
		}
		return new JsonModel(
            array(
				'html' => $html,
				'totPages' => $totPages,
				'currentPage' => $currentPage,
            )
        );
	}
	
	public function searchvehicleAction(){
		$regnum = $this->params()->fromRoute('regnum');
		$latRegnum = $this->serviceVehicle->correctRegnum($regnum);
		$vehicles = $this->serviceSearch->getRegnumMatches($regnum);
        $v = [];   
        //print_r($vehicles);     
        foreach($vehicles as $vehicle){ 
            $v[] = array(
                'vehicleId' => $vehicle['v_id'],
                'regnum' => $vehicle['v_regnum'],
                'imagePath' => 'public/' . $vehicle['i_path'] . '/' . $vehicle['i_name'],
                'regnum' => $vehicle['v_regnum'],
            );
        }

		return new JsonModel(
            array(
				'vehicles' => $v,
                //'vehicle_id' => 
            )
        );
		
	}
	
	public function getvehicleAction(){
		$vehicle_id = $this->params()->fromRoute('id');
		$vehicle = $this->serviceVehicle->getVehicleById($vehicle_id);
		$images = $this->serviceVehicle->getImages($vehicle_id);
        $i = [];
        foreach($images as $image){

            $i[] = array('imagePath' => 'public/' . $image['path'] . '/' . $image['name']);
        }
		return new JsonModel(
            array(
                'vehicles' => array(
				                'id' => $vehicle->getId(),
                                'regnum' => $vehicle->getRegnum(),
				                
                            ),
                'images' => $i,
            )
        );
		
	}
}
