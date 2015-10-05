<?php
return array(
'static_salt' => 'aFGQ475SDsdfsaf2342', // I am going to move it to global.php. It should be accessable everywhere
    'controllers' => array(
        'invokables' => array(
            'Edufinder\Controller\Index' => 'Edufinder\Controller\IndexController',   
            'Edufinder\Controller\Registration' => 'Edufinder\Controller\RegistrationController', 
            'Edufinder\Controller\Admin' => 'Edufinder\Controller\AdminController',   
        ),
    ),
    'router' => array(
        'routes' => array(
            'edufinder' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/edufinder',
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
                            'route'    => '/[:controller[/:action[/:id]]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id'         => '[a-zA-Z0-9_-]*',
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
//        'template_map' => array(
//            'layout/Edufinder'           => __DIR__ . '/../view/layout/Edufinder.phtml',
//        ),
        'template_path_stack' => array(
            'edufinder' => __DIR__ . '/../view'
        ),
        
        'display_exceptions' => true,
    ),
    'service_manager' => array(
        // added for Authentication and Authorization. Without this each time we have to create a new instance.
        // This code should be moved to a module to allow Doctrine to overwrite it
       'aliases' => array( // !!! aliases not alias
            'Zend\Authentication\AuthenticationService' => 'my_edufinder_service',
        ),
        'invokables' => array(
            'my_edufinder_service' => 'Zend\Authentication\AuthenticationService',
        ),
    ),
);