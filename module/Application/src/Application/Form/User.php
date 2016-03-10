<?php
/**
 * Created by PhpStorm.
 * User: Ярослав
 * Date: 28.02.2016
 * Time: 15:35
 */

namespace Application\Form;

use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Stdlib\Hydrator\ClassMethods;
use Zend\InputFilter\InputFilter;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
class User extends Form {
    public function init()
    {
        $this->setAttributes(
            array('id' => 'user')
        );
        $this->setHydrator(new ClassMethods())
            ->setObject(new \Application\Entity\User())
            ->setInputFilter(new InputFilter());

        $this->add(array(
            'name' => 'username',
            'type' => 'text',
            'options' => array(
                'label' => 'Логин*'
            )
        ));

        $this->add(array(
            'name' => 'email',
            'type' => 'Zend\Form\Element\Email',
            'options' => array(
                'label' => 'Email*'
            )
        ));

        $this->add(array(
            'name' => 'password',
            'type' => 'Zend\Form\Element\Password',
            'options' => array(
                'label' => 'Пароль'
            )
        ));

        $this->add(array(
            'name' => 'password_repeat',
            'type' => 'Zend\Form\Element\Password',
            'options' => array(
                'label' => 'Пароль еще раз'
            )
        ));

        $this->add(array(
            'name' => 'firstname',
            'type' => 'text',
            'options' => array(
                'label' => 'Имя'
            )
        ));

        $this->add(array(
            'name' => 'lastname',
            'type' => 'text',
            'options' => array(
                'label' => 'Фамилия'
            )
        ));

        $this->add(array(
            'name' => 'save',
            'type' => 'submit',
            'attributes' => array('value' => 'Зарегистрироваться'),
        ));
    }
} 