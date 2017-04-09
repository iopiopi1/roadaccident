<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

return array(
    'router' => array(
        'routes' => array(
            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
            ),
            // The following is a route to simplify getting started creating
            // new controllers and actions without needing to create a new
            // module. Simply drop new controllers in, and you can access them
            // using the path /application/:controller/:action
            'application' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/application',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller\Index',
                        'controller'    => 'Index',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:controller[/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),
                ),
            ),
			'index' => array(
                'type' => 'Segment',
                'options' => array(
                    'route'    => '/index[/:action]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
            ),
            'user' => array(
                'type' => 'Segment',
                'options' => array(
                    'route'    => '/user[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Application\Controller\User',
                        'action'     => 'register',
                    ),
                ),
            ),
			'vehicle' => array(
                'type' => 'Segment',
                'options' => array(
                    'route'    => '/vehicle[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Application\Controller\Vehicle',
                        'action'     => 'add',
                    ),
                ),
            ),
            'admin' => array(
                'type' => 'Segment',
                'options' => array(
                    'route'    => '/admin[/:action][/:page]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Application\Controller\Admin',
                        'action'     => 'index',
                    ),
                ),
            ),
            'image' => array(
                'type' => 'Segment',
                'options' => array(
                    'route'    => '/image[/:action]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Application\Controller\Image',
                        'action'     => 'index',
                    ),
                ),
            ),
            'api' => array(
                'type' => 'Segment',
                'options' => array(
                    'route'    => '/api',
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'addvehicleajax' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route'    => '/addvehicleajax[/:addvehicleajax]',
                            'defaults' => array(
                                'controller' => 'Application\Controller\Api',
                                'action'     => 'addvehicleajax',
                            ),
                        ),
                    ),
					'addimageajax' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route'    => '/addimageajax[/:addimageajax]',
                            'defaults' => array(
                                'controller' => 'Application\Controller\Api',
                                'action'     => 'addimageajax',
                            ),
                        ),
                    ),
					'registerconfirm' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route'    => '/registerconfirm[/:url]',
                            'defaults' => array(
                                'controller' => 'Application\Controller\Api',
                                'action'     => 'registerconfirm',
                            ),
                        ),
                    ),
					'addbrandajax' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route'    => '/addbrandajax[/:url]',
                            'defaults' => array(
                                'controller' => 'Application\Controller\Api',
                                'action'     => 'addbrandajax',
                            ),
                        ),
                    ),
					'addsupplierajax' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route'    => '/addsupplierajax[/:url]',
                            'defaults' => array(
                                'controller' => 'Application\Controller\Api',
                                'action'     => 'addsupplierajax',
                            ),
                        ),
                    ),
					'adminupdateimage' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route'    => '/adminupdateimage[/:url]',
                            'defaults' => array(
                                'controller' => 'Application\Controller\Api',
                                'action'     => 'adminupdateimage',
                            ),
                        ),
                    ),
					'admindeleteimage' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route'    => '/admindeleteimage[/:url]',
                            'defaults' => array(
                                'controller' => 'Application\Controller\Api',
                                'action'     => 'admindeleteimage',
                            ),
                        ),
                    ),
					'restorepassconfirm' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route'    => '/restorepassconfirm[/:url]',
                            'defaults' => array(
                                'controller' => 'Application\Controller\Api',
                                'action'     => 'restorepassconfirm',
                            ),
                        ),
                    ),
					'sendemailcron' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route'    => '/sendemailcron[/:url]',
                            'defaults' => array(
                                'controller' => 'Application\Controller\Api',
                                'action'     => 'sendemailcron',
                            ),
                        ),
                    ),
					'unziploadimages' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route'    => '/unziploadimages[/:url]',
                            'defaults' => array(
                                'controller' => 'Application\Controller\Api',
                                'action'     => 'unziploadimages',
                            ),
                        ),
                    ),
					'editvehicleregnum' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route'    => '/editvehicleregnum[/:url]',
                            'defaults' => array(
                                'controller' => 'Application\Controller\Api',
                                'action'     => 'editvehicleregnum',
                            ),
                        ),
                    ),
					'adminupdateuser' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route'    => '/adminupdateuser[/:url]',
                            'defaults' => array(
                                'controller' => 'Application\Controller\Api',
                                'action'     => 'adminupdateuser',
                            ),
                        ),
                    ),
					'editvehicles' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route'    => '/editvehicles[/:url]',
                            'defaults' => array(
                                'controller' => 'Application\Controller\Api',
                                'action'     => 'editvehicles',
                            ),
                        ),
                    ),
					'imgdeleteadmin' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route'    => '/imgdeleteadmin[/:url]',
                            'defaults' => array(
                                'controller' => 'Application\Controller\Api',
                                'action'     => 'imgdeleteadmin',
                            ),
                        ),
                    ),
                ),
            ),
			'search' => array(
                'type' => 'Segment',
                'options' => array(
                    'route'    => '/search[/:action]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Application\Controller\Search',
                        'action'     => 'index',
                    ),
                ),
            ),
        ),
		
    ),

    'service_manager' => array(
        'invokables' => array(
            //'Application\Form\User' => 'Application\Form\User',
        ),
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
        'factories' => array(
            'translator' => 'Zend\Mvc\Service\TranslatorServiceFactory',
            'Application\Service\UserService' => function($sm) {
                $service = new \Application\Service\UserService();
                $service->setEntityManager($sm->get('Doctrine\ORM\EntityManager'));
				$service->setViewHelper($sm->get('ViewHelperManager'));
                return $service;
            },
			'Application\Service\VehicleService' => function($sm) {
                $service = new \Application\Service\VehicleService();
                $service->setEntityManager($sm->get('Doctrine\ORM\EntityManager'));
                return $service;
            },
            'Application\Service\ApiService' => function($sm) {
                $service = new \Application\Service\ApiService();
                $service->setEntityManager($sm->get('Doctrine\ORM\EntityManager'));
                return $service;
            },
			'Application\Service\SearchService' => function($sm) {
                $service = new \Application\Service\SearchService();
                $service->setEntityManager($sm->get('Doctrine\ORM\EntityManager'));
                return $service;
            },
            'Application\Service\AdminService' => function($sm) {
                $service = new \Application\Service\AdminService();
                $service->setEntityManager($sm->get('Doctrine\ORM\EntityManager'));
                return $service;
            },
			'Application\Service\ImageService' => function($sm) {
                $service = new \Application\Service\AdminService();
                $service->setEntityManager($sm->get('Doctrine\ORM\EntityManager'));
                return $service;
            },
        ),
    ),
    'translator' => array(
        'locale' => 'en_US',
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
        ),
        'factories' => array(
            'Application\Controller\User' => function ($sm) {
                return new \Application\Controller\UserController($sm->getServiceLocator()->get('Application\Service\UserService'));
            },
			'Application\Controller\Vehicle' => function ($sm) {
                return new \Application\Controller\VehicleController(
					$sm->getServiceLocator()->get('Application\Service\VehicleService'),
					$sm->getServiceLocator()->get('Doctrine\ORM\EntityManager')
					//$sm->getServiceLocator()->get('Application\Entity\Repository\VehicleRepository')
				);
            },
            'Application\Controller\Api' => function ($sm) {
                return new \Application\Controller\ApiController(
					$sm->getServiceLocator()->get('Application\Service\ApiService'),
					$sm->getServiceLocator()->get('Application\Service\UserService'),
					$sm->getServiceLocator()->get('Doctrine\ORM\EntityManager'),
					$sm->getServiceLocator()->get('Application\Service\VehicleService'),
					$sm->getServiceLocator()->get('ViewHelperManager')
				);
            },
            'Application\Controller\Index' => function ($sm) {
                return new \Application\Controller\IndexController($sm->getServiceLocator()->get('Application\Service\VehicleService'));
            },
			'Application\Controller\Search' => function ($sm) {
                return new \Application\Controller\SearchController(
					$sm->getServiceLocator()->get('Application\Service\SearchService'),
					$sm->getServiceLocator()->get('Application\Service\VehicleService')
				);
            },
            'Application\Controller\Admin' => function ($sm) {
                return new \Application\Controller\AdminController(
					$sm->getServiceLocator()->get('Application\Service\AdminService'),
					$sm->getServiceLocator()->get('Doctrine\ORM\EntityManager'),
					$sm->getServiceLocator()->get('Application\Service\UserService'),
					$sm->getServiceLocator()->get('Application\Service\VehicleService')
				);
            },
            'Application\Controller\Image' => function ($sm) {
                return new \Application\Controller\ImageController(
					$sm->getServiceLocator()->get('Application\Service\ImageService'),
					$sm->getServiceLocator()->get('Application\Service\UserService')
				);
            },
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
        'strategies' => array(
            'ViewJsonStrategy',
        ),
    ),
    // Placeholder for console routes
    'console' => array(
        'router' => array(
            'routes' => array(
				'cronroute' => array(
					'options' => array(
						'route'    => 'sendemailcron',
						'defaults' => array(
							'controller' => 'Application\Controller\Api',
							'action' => 'sendemailcron'
						)
					)
				),
				'uploadzip' => array(
					'options' => array(
						'route'    => 'unziploadimages [<filePath>]',
						'defaults' => array(
							'controller' => 'Application\Controller\Api',
							'action' => 'unziploadimages'
						)
					)
				)
            ),
        ),
    ),
    'form_elements' => array(
        'invokables' => array(
            'Application\Form\User' => 'Application\Form\User',
			//'Application\Form\Vehicle'=>'Application\Form\Vehicle',
			'Application\Form\Searchvehicle' => 'Application\Form\Searchvehicle',
			'Application\Form\Login' => 'Application\Form\Login',
        ),
        'factories' => array(
            'Application\Form\Vehicle' => function($sm) {
                $form = new \Application\Form\Vehicle();
                $form->setEntityManager($sm->getServiceLocator()->get('Doctrine\ORM\EntityManager'));
                return $form;
            },
            'Application\Form\Brand' => function($sm) {
                $form = new \Application\Form\Brand();
                $form->setEntityManager($sm->getServiceLocator()->get('Doctrine\ORM\EntityManager'));
                return $form;
            },
            'Application\Form\Supplier' => function($sm) {
                $form = new \Application\Form\Supplier();
                $form->setEntityManager($sm->getServiceLocator()->get('Doctrine\ORM\EntityManager'));
                return $form;
            },
        ),
    ),
    // Doctrine config
    'doctrine' => array(
        'driver' => array(
            'application_entities' => array(
                'class' =>'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/../src/Application/Entity'),
            ),
            'orm_default' => array(
                'drivers' => array(
                    'Application\Entity' => 'application_entities'
                )
            ),
			/*'application_repositories' => array(
				'class' => 'Application\Entity\Repository\VehicleRepository',
				'cache' => 'array',
				'paths' => array(__DIR__ . '/../src/Application/Repository')
			),*/
        )
    )
);
