<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\Validator\AbstractValidator;
use Zend\Mvc\I18n\Translator;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\ModuleManager\Feature\FormElementProviderInterface;
use Zend\Session\SessionManager;
use Zend\Session\Config\SessionConfig;
use Zend\Session\Container;

class Module
{
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
	
	public function onBootstrap($e)
    {
        $app = $e->getParam('application');
        $app->getEventManager()->attach(MvcEvent::EVENT_RENDER, array($this, 'setFormToView'), 100);
		
		$viewModel = $e->getApplication()->getMvcEvent()->getViewModel();
		
		
		$this->initSession(array(
			'remember_me_seconds' => 180,
			'use_cookies' => true,
			'cookie_httponly' => true,
		));
		
		$viewModel->user_session = new Container('user');
		
    }

    public function setFormToView($event)
    {
		
		$searchForm = new \Application\Form\Searchvehicle();
		
        $viewModel = $event->getViewModel();
        $viewModel->setVariables(array(
            'searchForm' => $searchForm,
        ));
    }
	
	public function initSession($config)
	{
		$user_session = new Container('user');
		return $user_session;
	}
	
}
