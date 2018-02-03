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
				'id' => 'regnum_form_inp',
				'class' => 'regnum_form',
				'placeholder' => 'Рег.номер (напр. А777АА64)',
				'pattern' => '[ABEHKMOPCTYXabehkmopctyx]\\d{3}[ABEHKMOPCTYXabehkmopctyx]{2}\\d{2,3}|[АВЕКМНОРСТУХавекмнорстух]\\d{3}[АВЕКМНОРСТУХавекмнорстух]{2}\\d{2,3}',
				'title' => 'Формат X111XX11'
			),
        ));

        $this->add(array(
            'name' => 'submit',
            'type' => 'Zend\Form\Element\Button',
            'attributes' => array(
				'id' => 'regnum_form_but',
				'value' => 'Ok',
				'class' => 'regnum_form',
				'type' => 'submit',
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