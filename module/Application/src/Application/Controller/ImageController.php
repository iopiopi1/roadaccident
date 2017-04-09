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
use Zend\Session\Container;

class ImageController extends AbstractActionController
{
	
	
    /** @var \Application\Service\ImageService */
    protected $serviceImage = null;
	
    /** @var \Application\Service\UserService */
    protected $serviceUser = null;
	
    function __construct($serviceImage, $serviceUser)
    {
        $this->serviceImage = $serviceImage;
		$this->user_session = new Container('user');
        $this->serviceUser = $serviceUser;
    }
	
	public function indexAction()
    {
		
		if( is_null($this->user_session->id) ){
			return $this->redirect()->toRoute('user',
				array(
					'action' => 'login')
				);
		}
		
		return new ViewModel(
            /*array(
                'images' => $images,
                'form' => $form,
                'vehicle_uid' => null,
                'vehicle_id' => $vehicle_id,
                'user' => $user,
                'vehicle' => $vehicle,
				'user_session' => $user_session,
                'brandSuppName' => $brandSuppName,
                'imagesTop' => $imagesTop
            )*/
        );
	}
	
	public function addAction()
    {		
		if( is_null($this->user_session->id)){
			return $this->redirect()->toRoute('user',
				array(
					'action' => 'login')
				);
		}
		
		return new ViewModel(
		);
	}
}