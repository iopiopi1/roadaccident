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
        return new ViewModel(
            array(
            )
        );
    }

    public function editsupplierAction()
    {
        return new ViewModel(
            array(
            )
        );
    }

    public function addbrandAction()
    {		
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
        $form = $this->getSupplierForm();
        $suppliers = $this->serviceAdmin->getAllSuppliers();

        return new ViewModel(
            array(
                'form' => $form,
                'suppliers' => $suppliers,
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