<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Zend\Session\Container;
class AdminController extends AbstractActionController
{

    /** @var \Zend\Form\Form */
    protected $brandForm = null;

    /** @var \Zend\Form\Form */
    protected $supplierForm = null;

    /** @var \Application\Service\AdminService */
    protected $serviceAdmin = null;
	
    /** @var \Application\Service\UserService */
    protected $serviceUser = null;
	
    /** @var \Application\Service\VehicleService */
    protected $serviceVehicle = null;
	
    protected $entityManager = null;
	protected $user_session = null;
	
    function __construct($serviceAdmin, $entityManager, $serviceUser, $serviceVehicle)
    {
        $this->serviceAdmin = $serviceAdmin;
		$this->entityManager = $entityManager;
		$this->user_session = new Container('user');
        $this->serviceUser = $serviceUser;
        $this->serviceVehicle = $serviceVehicle;
    }

    public function indexAction()
    {
		
		if( is_null($this->user_session->id) || $this->serviceUser->getIsUserAdmin($this->user_session->id) != 1){
			return $this->redirect()->toRoute('index',
				array(
					'action' => 'index')
				);
		}
		
		$newImages = $this->serviceAdmin->getAllNewImages();
		
        return new ViewModel(
            array(
				'newImages' => $newImages, 
            )
        );
    }

    public function editbrandAction()
    {
		if( is_null($this->user_session->id) || $this->serviceUser->getIsUserAdmin($this->user_session->id) != 1){
			return $this->redirect()->toRoute('index',
				array(
					'action' => 'index')
				);
		}
		
        return new ViewModel(
            array(
            )
        );
    }

    public function editsupplierAction()
    {
		
		if( is_null($this->user_session->id) || $this->serviceUser->getIsUserAdmin($this->user_session->id) != 1){
			return $this->redirect()->toRoute('index',
				array(
					'action' => 'index')
				);
		}
		
        return new ViewModel(
            array(
            )
        );
    }

    public function addbrandAction()
    {		
	
		if( is_null($this->user_session->id) || $this->serviceUser->getIsUserAdmin($this->user_session->id) != 1){
			return $this->redirect()->toRoute('index',
				array(
					'action' => 'index')
				);
		}
		
        $form = $this->getBrandForm();
        $brands = $this->serviceAdmin->getAllBrands();
		
        return new ViewModel(
            array(
                'form' => $form,
                'brands' => $brands,
            )
        );
    }

    public function addsupplierAction()
    {
		
		if( is_null($this->user_session->id) || $this->serviceUser->getIsUserAdmin($this->user_session->id) != 1){
			return $this->redirect()->toRoute('index',
				array(
					'action' => 'index')
				);
		}
		
        $form = $this->getSupplierForm();
        $suppliers = $this->serviceAdmin->getAllSuppliers();

        return new ViewModel(
            array(
                'form' => $form,
                'suppliers' => $suppliers,
            )
        );
    }

    public function filemanagerAction()
    {
		
		if( is_null($this->user_session->id) || $this->serviceUser->getIsUserAdmin($this->user_session->id) != 1){
			return $this->redirect()->toRoute('index',
				array(
					'action' => 'index')
				);
		}
		
		$vehicleId = $this->params()->fromRoute('page');
		$vehicle = $this->serviceVehicle->getVehicleById($vehicleId);
		$images = $this->serviceVehicle->getImages2($vehicleId);
		
		if(is_null($vehicleId)){
			$vehicleId = -1;
		}
		
		
		
        return new ViewModel(
            array(
				'vehicle' => $vehicle,
				'images' => $images,
            )
        );
    }	
	
    public function listusersAction()
    {
		
		if( is_null($this->user_session->id) || $this->serviceUser->getIsUserAdmin($this->user_session->id) != 1){
			return $this->redirect()->toRoute('index',
				array(
					'action' => 'index')
				);
		}
		
        $page = $this->params()->fromRoute('page');
		if(is_null($page)){
			$page = 0;
		}
        $paginator = $this->serviceAdmin->getUsers($page, 20);

        return new ViewModel(
            array(
				'paginator' => $paginator,
            )
        );
    }
	
	public function editvehiclesAction(){
		if( is_null($this->user_session->id) || $this->serviceUser->getIsUserAdmin($this->user_session->id) != 1){
			return $this->redirect()->toRoute('index',
				array(
					'action' => 'index')
				);
		}
		
		$page = $this->params()->fromRoute('page');
		if(is_null($page)){
			$page = 0;
		}
        //$imagesTop = $this->serviceVehicle->getTopImages(6);
		$paginator = $this->serviceAdmin->getVehicles($page, 20);
        
		
		return new ViewModel(
            array(
				'paginator' => $paginator,
            )
        );
	}
	
    /**
     * @param \Zend\Form\Form $brandForm
     */
    public function setBrandForm($brandForm)
    {
        $this->brandForm = $brandForm;
    }

    /**
     * @return \Zend\Form\Form
     */
    public function getBrandForm()
    {
        $formManager = $this->serviceLocator->get('FormElementManager');
        $this->brandForm = $formManager->get('Application\Form\Brand');

        return $this->brandForm;
    }

    /**
     * @param \Zend\Form\Form $supplierForm
     */
    public function setSupplierForm($supplierForm)
    {
        $this->supplierForm = $supplierForm;
    }

    /**
     * @return \Zend\Form\Form
     */
    public function getSupplierForm()
    {
        $formManager = $this->serviceLocator->get('FormElementManager');
        $this->supplierForm = $formManager->get('Application\Form\Supplier');

        return $this->supplierForm;
    }
}