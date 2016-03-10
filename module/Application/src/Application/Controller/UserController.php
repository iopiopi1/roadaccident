<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;

class UserController extends AbstractActionController
{

    /** @var \Zend\Form\Form */
    protected $userForm = null;

    /** @var \Application\Service\UserService */
    protected $serviceUser = null;

    function __construct($serviceUser)
    {
        $this->serviceUser = $serviceUser;
    }

    public function loginAction()
    {

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

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $user = $form->getData();
                $user->setDateEdited(new \dateTime("now"));

                $this->serviceUser->save($user);
            }else {
                $messages = $form->getMessages();
            }
        }

        return new JsonModel(array(
            'success' => true,
            'id' => $user->getId(),
        ));
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

}