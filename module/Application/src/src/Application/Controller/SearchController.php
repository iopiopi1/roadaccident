<?php
/**
 * Created by PhpStorm.
 * User: Ярослав
 * Date: 23.02.2016
 * Time: 1:44
 */ 
 
 
namespace Application\Controller;
 
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Zend\Session\Container;

class SearchController extends AbstractActionController
{
	
	/** @var \Application\Service\SearchService */
    protected $serviceSearch = null;
	
	/** @var \Application\Service\VehicleService */
    protected $serviceVehicle = null;
	
	function __construct($serviceSearch, $serviceVehicle)
    {
        $this->serviceSearch = $serviceSearch;
		$this->serviceVehicle = $serviceVehicle;
    }
	
	public function indexAction()
    {
		$request = $this->getRequest();
		$vehicles = [];
		$regnum_search = '';
		if ($request->isPost()) {
			$regnum_search = $this->getRequest()->getPost('regnum');
			$latRegnum = $this->serviceVehicle->correctRegnum($regnum_search);
			$vehicles = $this->serviceSearch->getRegnumMatches($latRegnum);
		}
		
		return new ViewModel(
            array(
                'vehicles' => $vehicles,
				'regnum_search' => $regnum_search,
            )
        );
    }
}