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

class ApiController extends AbstractActionController
{
    /** @var \Application\Service\ApiService */
    protected $serviceApi = null;
	
	/** @var \Application\Service\UserService */
    protected $serviceUser = null;
	
	/** @var \Application\Service\VehicleService */
    protected $serviceVehicle = null;
	
	protected $user_session = null;
	
    const FOLDER_IMG_TMP = "images/vehicles_tmp";
    const FOLDER_IMG = "images/vehicles";

    function __construct($serviceApi, $serviceUser, $entityManager, $serviceVehicle)
    {
        $this->serviceApi = $serviceApi;
		$this->serviceUser = $serviceUser;
		$this->entityManager = $entityManager;
		$this->serviceVehicle = $serviceVehicle;
		$this->user_session = new Container('user');
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
		$state;
		$request = $this->getRequest();
        if ($request->isPost()) {
			$post = $request->getPost()->toArray();	
			$vehicle_existing = $this->entityManager->getRepository('\Application\Entity\Vehicle')->findOneBy(array('regnum' => $post['regNum']));
			if(is_null($vehicle_existing)){
				// new regnum
				$vehicle = new \Application\Entity\Vehicle();
				$vehicle->setStatus(0);
				
				// change cyrillic chars
				$latRegnum = $this->serviceVehicle->correctRegnum($post['regNum']);
				
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
				
				if(rename($post['thumbnailPath'],$thmbPathNew)
				&& rename($post['primaryPath'],$primaryPathNew)){
					$qb = $this->entityManager->createQueryBuilder();
					$q = $qb->update('\Application\Entity\Image', 'i')
							->set('i.vehicle', '?1')
							->set('i.status', '?2')
							->set('i.path', '?3')
							->where('i.uniqueId = ?4')
							->setParameter(1, $vehicleId)
							->setParameter(2, 0)
							->setParameter(3, $img_pathWithoutPublic)
							->setParameter(4, $post['picsId'])
							->getQuery();
					$p = $q->execute();
				}	

				// create regnum image
                $this->serviceVehicle->createRegnumImage($latRegnum,$img_path);
				
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
				
				if(rename('public/' . $post['thumbnailPath'],$thmbPathNew)
				&& rename('public/' . $post['primaryPath'],$primaryPathNew)){
					$qb = $this->entityManager->createQueryBuilder();
					$q = $qb->update('\Application\Entity\Image', 'i')
							->set('i.vehicle', '?1')
							->set('i.status', '?2')
							->set('i.path', '?3')
							->where('i.uniqueId = ?4')
							->setParameter(1, $vehicle_existing->getId())
							->setParameter(2, 0)
							->setParameter(3, $img_pathWithoutPublic)
							->setParameter(4, $post['picsId'])
							->getQuery();
					$p = $q->execute();
				}				
				
				$state = 'success';
			}
		}
		
		return new JsonModel(
            array(
                'state' => $state,
            )
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
					->setParameter(1, 3)
					->setParameter(2, $post['picsId'])
					->getQuery();
			$p = $q->execute();
			
				
			unlink(/*'public/' . */$post['thumbnailPath']);
			unlink(/*'public/' . */$post['primaryPath']);
			
			$server_url = $this->getRequest()->getUri()->getScheme() . '://' . $this->getRequest()->getUri()->getHost();
			
			$reason = $post['textDeletion'];
			$userId = $post['userId']; 
			$this->serviceUser->sendInfoDeletionImageEmail($userId, $reason, $server_url);
			
			$state = 'success';
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
	
}