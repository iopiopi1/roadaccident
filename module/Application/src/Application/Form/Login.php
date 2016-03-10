<?php
/**
 * Created by PhpStorm.
 * User: ???????
 * Date: 28.02.2016
 * Time: 15:35
 */

namespace Application\Form;

use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Stdlib\Hydrator\ClassMethods;
use Zend\InputFilter\InputFilter;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;

class Login extends Form {
	
	public function init()
    {
		$this->setAttributes(
				array('id' => 'login')
		);
		
		$this->add(array(
			'name' => 'username',
			'type' => 'text',
			'options' => array(
				'label' => '',
			),
			'attributes' => array(
				'class' => 'form-control',
				'placeholder' => 'Логин'
			),		
		));
		
		$this->add(array(
			'name' => 'password',
			'type' => 'Zend\Form\Element\Password',
			'options' => array(
				'label' => ''
			),
			'attributes' => array(
				'class' => 'form-control',
				'placeholder' => 'Пароль'
			),
		));	
		
		$this->add(array(
			'name' => 'login',
			'type' => 'submit',
			'attributes' => array(
				'value' => 'Войти',
				'class' => 'form-control btn-primary',
			),
		));
	}
	
}