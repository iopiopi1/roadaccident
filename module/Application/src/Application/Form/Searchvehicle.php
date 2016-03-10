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
use Zend\InputFilter\InputFilterProviderInterface;
class Searchvehicle extends Form  implements InputFilterProviderInterface{
	public function __construct()
    {

        parent::__construct();
    /*public function init()
    {*/
        /*$this->setAttributes(
            array('id' => 'searchvehicle')
        );*/
        /*$this->setHydrator(new ClassMethods())
            ->setObject(new \Application\Entity\Vehicle())
            ->setInputFilter(new InputFilter());*/
		$this->setAttributes(
            array('id' => 'searchvehicle')
        );
		
        $this->add(array(
            'name' => 'regnum',
            'type' => 'text',
            'options' => array(
                'label' => '',
            ),
			'attributes' => array(
				'class' => 'form-control',
				'placeholder' => 'Рег.номер'
			),
        ));

        $this->add(array(
            'name' => 'submit',
            'type' => 'Zend\Form\Element\Button',
            'attributes' => array(
				'value' => 'Ok',
				'class' => 'btn btn-default',
			),
			'options' => array(
				'label' => 'Найти'
			)
        ));
    }
	
	public function getInputFilterSpecification()
    {
        return array(
            'regnum' => array(
                'required' => true,
                'filters'  => array(
                    array('name' => 'Zend\Filter\StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name' => 'Zend\Validator\StringLength',
                        'options' => array(
                            'min' => 3,
                            'max' => 8
                        ),
                    ),
                ),
            ),
        );
    }
	
} 