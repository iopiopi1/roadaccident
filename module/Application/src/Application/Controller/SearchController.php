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
	public function indexAction()
    {
		return new ViewModel(
            array(
                
            )
        );
    }
}