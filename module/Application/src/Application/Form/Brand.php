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

class Brand extends Form
{

    protected $em;

    public function init()
    {
        $this->setAttributes(
            array('id' => 'user')
        );
        $this->setHydrator(new ClassMethods())
            ->setObject(new \Application\Entity\User())
            ->setInputFilter(new InputFilter());

        $this->add(array(
            'name' => 'brandname',
            'type' => 'text',
            'options' => array(
                'label' => '',
            ),
            'attributes' => array(
                'class' => 'form-control',
                'placeholder' => 'Марка',
                'required' => 'required',
            ),
        ));

        $this->add(array(
            'name' => 'suppliernames',
            'type' => 'Zend\Form\Element\Select',
            'options' => array(
                'label' => '',
                'value_options' => $this->getAllSuppliers(),
            ),
            'attributes' => array(
                'class' => 'form-control',
                'placeholder' => 'Производитель',
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

    public function getAllSuppliers()
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery('SELECT s.name AS sname, s.id AS sid FROM Application\Entity\Supplier s WHERE s.status = 0');
        $resultSet = $query->getScalarResult();

        foreach($resultSet as $row){
            $result[$row['sid']] = $row['sname'];
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