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
	
    const FOLDER_IMG_TMP = "images/vehicles_tmp";
    const FOLDER_IMG = "images/vehicles";

    function __construct($serviceApi,$serviceUser)
    {
        $this->serviceApi = $serviceApi;
		$this->serviceUser = $serviceUser;
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
                rmdir(self::FOLDER_IMG_TMP . DIRECTORY_SEPARATOR  . $vehicle_uid);
            }
        }//print_r($response);
        return new JsonModel(
            $response
        );
    }
}