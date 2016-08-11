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
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class Vehicle extends Form
{	
	protected $em;

    public function init()
    {
        $this->setAttributes(
            array('id' => 'vehicle')
        );
        $this->setHydrator(new ClassMethods())
            ->setObject(new \Application\Entity\User())
            ->setInputFilter(new InputFilter());

        $this->add(array(
            'name' => 'brand',
            'type' => 'Zend\Form\Element\Select',
            'options' => array(
                'label' => '',
				//'value_options' => $this->getAllBrands(),
            ),
			'attributes' => array(
				'class' => 'form-control',
				'placeholder' => 'Марка ТС',
				'id' => 'vehicle_add_id'
			),
        ));

		/*
		$this->add(array(
            'name' => 'brand',
            'type' => 'text',
            'options' => array(
                'label' => '',
				//'value_options' => $this->getAllBrands(),
            ),
			'attributes' => array(
				'class' => 'form-control',
				'placeholder' => 'Марка ТС',
				'id' => 'vehicle_add_id'
			),
        ));*/
		
        $this->add(array(
            'name' => 'regnum',
            'type' => 'text',
            'options' => array(
                'label' => ''
            ),
			'attributes' => array(
				'class' => 'form-control',
				'placeholder' => 'Автономер'
			),
        ));

        $this->add(array(
            'name' => 'submit',
            'type' => 'submit',
            'attributes' => array(
                'value' => 'Сохранить',
                'id' => 'submitbutton',
				'class' => 'form-control btn-primary',
            ),
        ));

    }
	
	public function getAllBrands()
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery('SELECT b.name AS sname, s.name AS bname, b.id AS bid, s.id AS sid FROM Application\Entity\Brand b JOIN Application\Entity\Supplier s WITH b.supplier = s.id');
        $resultSet = $query->getScalarResult();

        foreach($resultSet as $row){
            $result[$row['bid']] = $row['bname'] . " " . $row['sname'];
        }

        return $result;
    }


    public function setEntityManager($em){
        $this->em = $em;
    }

    public function getEntityManager(){
        return $this->em;
    }
	
} 