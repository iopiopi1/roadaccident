<?php
/**
 * Created by PhpStorm.
 * User: ???????
 * Date: 23.02.2016
 * Time: 1:43
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;

class VehicleController extends AbstractActionController
{

    /** @var \Zend\Form\Form */
    protected $vehicleForm = null;

    /** @var \Application\Service\VehicleService */
    protected $serviceVehicle = null;

    function __construct($serviceVehicle)
    {
        $this->serviceVehicle = $serviceVehicle;
    }

    public function indexAction()
    {
        $vehicle_id = $this->params()->fromRoute('id');
        if($vehicle_id > 0){
            $vehicle = $this->serviceVehicle->getVehicleById($vehicle_id);
            $images = $this->serviceVehicle->getImages($vehicle_id);
            $form = $this->getVehicleForm();
            $form->bind($vehicle);
        }

        return new ViewModel(
            array(
                'images' => $images,
                'form' => $form,
                'vehicle_uid' => null,
                'vehicle_id' => $vehicle_id,
            )
        );
    }

    public function addAction()
    {
		$form = $this->getVehicleForm();
        $vehicle = new \Application\Entity\Vehicle();

        $uid = $this->generateUID();

        return new ViewModel(
            array(
                'form' => $form,
                'vehicle_uid' => $uid,
                'vehicle_id' => null,
            )
        );
    }

    public function addvehicleimagesajaxAction(){
        $request = $this->getRequest();
        if ($request->isPost()) {
            $post = $request->getPost()->toArray();
            parse_str($post['vehicleData'], $vehicleData);
            parse_str($post['photoData'], $photoData);
            $vehicle_uid = $photoData['vehicle_uid'];
            $vehicle_id = $photoData['vehicle_id'];
            if(!is_null($vehicle_uid)) {
                // new vehicle
                $vehicle = new \Application\Entity\Vehicle();
                $vehicle->setDateEdited(new \DateTime("now"));
                $vehicle->setStatus(\Application\Entity\Vehicle::STATUS_ACTIVE);
                $vehicle->setBrand($vehicleData['brand']);
                $vehicle->setRegnum($vehicleData['regnum']);
                $vehicle_new = $this->serviceVehicle->save($vehicle);
                $vehicle_id = $vehicle_new->getId();
                $img_path_tmp = 'public/' . \Application\Controller\ApiController::FOLDER_IMG_TMP . '/' . $vehicle_uid;
                $img_path = 'public/' . \Application\Controller\ApiController::FOLDER_IMG . '/' . $vehicle_id;
                if (!is_dir($img_path)) {
                    mkdir($img_path, 0777, true);
                    mkdir($img_path . '/' . 'thumbnail', 0777, true);
                }
                $recdir = new \RecursiveDirectoryIterator($img_path_tmp);
                $recdir->setFlags(\RecursiveDirectoryIterator::SKIP_DOTS);
                $iterator = new \RecursiveIteratorIterator($recdir);
                foreach ($iterator as $fileInfo) {
                    if(strpos($fileInfo->getPathname(),'thumbnail')){
                        if(rename(str_replace('\\','/',$fileInfo->getPathname()),$img_path . '/thumbnail/' . $fileInfo->getFilename())){
                            rmdir($fileInfo->getPath());
                            $image = new \Application\Entity\Image();
                            $image->setPath($img_path . '/thumbnail/' . $fileInfo->getFilename());
                            $image->setStatus(0);
                            $image->setType(1);
                            $image->setName($fileInfo->getFilename());
                            $image->setVehicle($vehicle_id);
                            $this->serviceVehicle->save($image);
                        }
                    }
                    else {
                        if(rename(str_replace('\\', '/', $fileInfo->getPathname()), $img_path . '/' . $fileInfo->getFilename())){
                            rmdir($fileInfo->getPath());
                            $image = new \Application\Entity\Image();
                            $image->setPath($img_path . '/' . $fileInfo->getFilename());
                            $image->setStatus(0);
                            $image->setType(0);
                            $image->setName($fileInfo->getFilename());
                            $image->setVehicle($vehicle_id);
                            $this->serviceVehicle->save($image);
                        }
                    }
                }
            }
            else{
                // existing vehicle
                /*$vehicle = new \Application\Entity\Vehicle();
                $vehicle->setDateEdited(new \DateTime("now"));
                $vehicle->setStatus(\Application\Entity\Vehicle::STATUS_ACTIVE);
                $vehicle->setBrand($vehicleData['brand']);
                $vehicle->setRegnum($vehicleData['regnum']);
                $vehicle_new = $this->serviceVehicle->save($vehicle);
                $vehicle_id = $vehicle_new->getId();*/
                $img_path = 'public/' . \Application\Controller\ApiController::FOLDER_IMG . '/' . $vehicle_id;
                /*if (!is_dir($img_path)) {
                    mkdir($img_path, 0777, true);
                    mkdir($img_path . '/' . 'thumbnail', 0777, true);
                }*/
                $recdir = new \RecursiveDirectoryIterator($img_path_tmp);
                $recdir->setFlags(\RecursiveDirectoryIterator::SKIP_DOTS);
                $iterator = new \RecursiveIteratorIterator($recdir);
                foreach ($iterator as $fileInfo) {
                    if(strpos($fileInfo->getPathname(),'thumbnail')){
                        if(rename(str_replace('\\','/',$fileInfo->getPathname()),$img_path . '/thumbnail/' . $fileInfo->getFilename())){
                            rmdir($fileInfo->getPath());
                            $image = new \Application\Entity\Image();
                            $image->setPath($img_path . '/thumbnail/' . $fileInfo->getFilename());
                            $image->setStatus(0);
                            $image->setType(1);
                            $image->setName($fileInfo->getFilename());
                            $image->setVehicle($vehicle_id);
                            $this->serviceVehicle->save($image);
                        }
                    }
                    else {
                        if(rename(str_replace('\\', '/', $fileInfo->getPathname()), $img_path . '/' . $fileInfo->getFilename())){
                            rmdir($fileInfo->getPath());
                            $image = new \Application\Entity\Image();
                            $image->setPath($img_path . '/' . $fileInfo->getFilename());
                            $image->setStatus(0);
                            $image->setType(0);
                            $image->setName($fileInfo->getFilename());
                            $image->setVehicle($vehicle_id);
                            $this->serviceVehicle->save($image);
                        }
                    }
                }
            }
        }

        return new ViewModel(
            array(
                'id' => $vehicle->getId(),
            )
        );

    }

    public function generateUID(){
        return uniqid();
    }

    /**
     * @param \Zend\Form\Form $vehicleForm
     */
    public function setVehicleForm($vehicleForm)
    {
        $this->vehicleForm = $vehicleForm;
    }

    /**
     * @return \Zend\Form\Form
     */
    public function getVehicleForm()
    {
        $formManager = $this->serviceLocator->get('FormElementManager');
        $this->vehicleForm = $formManager->get('Application\Form\Vehicle');

        return $this->vehicleForm;
    }
}