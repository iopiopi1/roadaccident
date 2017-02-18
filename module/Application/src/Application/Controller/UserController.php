<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Zend\Session\Container;
class UserController extends AbstractActionController
{

    /** @var \Zend\Form\Form */
    protected $userForm = null;

    /** @var \Zend\Form\Form */
    protected $loginForm = null;
	
    /** @var \Application\Service\UserService */
    protected $serviceUser = null;

    function __construct($serviceUser)
    {
        $this->serviceUser = $serviceUser;
    }

    public function loginAction()
    {
		$form = $this->getLoginForm();
        $user = new \Application\Entity\User();
		
        return new ViewModel(
            array(
                'form' => $form
            )
        );
    }
	
	public function logoutAction()
    {
		$user_session = new Container('user');
		$user_session->getManager()->getStorage()->clear('user');
				
        return $this->redirect()->toRoute('index', array('action' => 'index'));

    }

    public function registerAction()
    {
        $form = $this->getUserForm();
        $user = new \Application\Entity\User();

        return new ViewModel(
            array(
                'form' => $form
            )
        );
    }
	
    public function registerajaxAction()
    {
        $form = $this->getUserForm();
        $user = new \Application\Entity\User();
        $form->bind($user);
		$server_url = $this->getRequest()->getUri()->getScheme() . '://' . $this->getRequest()->getUri()->getHost();
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $user = $form->getData();
				// if username not unique return error
				if(!$this->serviceUser->checkIsUsernameUnique($user->getUsername())){
					return new JsonModel(array(
						'state' => 'error',
						'id' => null,
						'errNumber' => 2,
					));
				}
				if(!$this->serviceUser->checkIsEmailUnique($user->getEmail())){
					return new JsonModel(array(
						'state' => 'error',
						'id' => null,
						'errNumber' => 3,
					));
				}
                $user->setDateEdited(new \dateTime("now"));
				$user->setStatus(\Application\Entity\User::STATUS_NONACTIVE);
                $user = $this->serviceUser->save($user);
				$this->serviceUser->sendConfirmationEmail($user->getEmail(),$user->getUsername(),$user->getId(),$user->getPassword(),$server_url);
            }else {
                $messages = $form->getMessages();
            }
        }

        return new JsonModel(array(
            'state' => 'success',
            'id' => $user->getId(),
        ));
    }
	
	public function checkloginAction()
    {
		$request = $this->getRequest();
        if ($request->isPost()) {
			$username = $request->getPost('username');
			$password = $request->getPost('password');
		}
				
        $user = $this->serviceUser->checkLogin($username,$password);
		
		if(!is_null($user)){
			$user_session = new Container('user');
			$user_session->username = $user->getUsername();
			$user_session->id = $user->getId();
			$user_session->isAdmin = $this->serviceUser->getIsUserAdmin($user->getId());
			return new JsonModel(array(
				'state' => 'success', 
				'errorMsg' => '',
				'isAdmin' => $user_session->isAdmin,
			));
		}
		else{	//print_r($user);
			return new JsonModel(array(
				'state' => 'failed', 
				'errorMsg' => 'Неверный логин или пароль!',
			));
		}
    }
	
	public function restorepasswordAction()
    {
		$request = $this->getRequest();
        if ($request->isPost()) {
			$email = $request->getPost('email');
		}
				
        $user = $this->serviceUser->findUserByEmail($email);
		
		if(!is_null($user)){
			$userId = $user->getId();
			$userName = $user->getUsername();
			$server_url = $this->getRequest()->getUri()->getScheme() . '://' . $this->getRequest()->getUri()->getHost();
			$user->setStatus(\Application\Entity\User::STATUS_NONACTIVE);
			$newPass = substr(uniqid(), 0, 10);
			$user->setPassword($newPass);
			$user = $this->serviceUser->save($user);
			
			$this->serviceUser->sendRestoredDetailsEmail($email,$userName,$userId,$newPass,$server_url);
			
			return new JsonModel(array(
				'state' => 'success', 
				'msg' => 'На указанный почтовый ящик отправлено письмо с инструкциями для восстановления пароля', 
				'errorMsg' => null,
			));
		}
		else{	//print_r($user);
			return new JsonModel(array(
				'state' => 'failed', 
				'msg' => null,
				'errorMsg' => 'Данный email адрес не зарегистрирован!',
			));
		}
	}
	
    /**
     * @param \Zend\Form\Form $userForm
     */
    public function setUserForm($userForm)
    {
        $this->userForm = $userForm;
    }

    /**
     * @return \Zend\Form\Form
     */
    public function getUserForm()
    {
        $formManager = $this->serviceLocator->get('FormElementManager');
        $this->userForm = $formManager->get('Application\Form\User');

        return $this->userForm;
    }

    /**
     * @param \Zend\Form\Form $loginForm
     */
    public function setLoginForm($loginForm)
    {
        $this->loginForm = $loginForm;
    }

    /**
     * @return \Zend\Form\Form
     */
    public function getLoginForm()
    {
        $formManager = $this->serviceLocator->get('FormElementManager');
        $this->loginForm = $formManager->get('Application\Form\Login');

        return $this->loginForm;
    }
	
}