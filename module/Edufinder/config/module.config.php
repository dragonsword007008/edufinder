<?php
return array(
'static_salt' => 'aFGQ475SDsdfsaf2342',
    'controllers' => array(
        'invokables' => array(
            'Edufinder\Controller\Index' => 'Edufinder\Controller\IndexController',   
            'Edufinder\Controller\Registration' => 'Edufinder\Controller\RegistrationController', 
            'Edufinder\Controller\Admin' => 'Edufinder\Controller\AdminController',   
            'Edufinder\Controller\Educator' => 'Edufinder\Controller\EducatorController',
				'Edufinder\Controller\Parent' => 'Edufinder\Controller\ParentController',   
        ),
    ),
    'router' => array(
        'routes' => array(
            'edufinder' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Edufinder\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => 'edufinder/[:controller[/:action[/:id]]]',
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
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'edufinder' => __DIR__ . '/../view'
        ),
        
        'display_exceptions' => true,
    ),
    'service_manager' => array(
       'aliases' => array( // !!! aliases not alias
            'Zend\Authentication\AuthenticationService' => 'my_edufinder_service',
        ),
        'invokables' => array(
            'my_edufinder_service' => 'Zend\Authentication\AuthenticationService',
        ),
    ),
);