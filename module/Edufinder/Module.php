<?php
namespace Edufinder; 
// Add this for Table Date Gateway
use Edufinder\Model\Users;
use Edufinder\Model\Marker;
use Edufinder\Model\EducatorTable;
use Edufinder\Model\ParentTable;
use Edufinder\Model\MarkerTable;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
// Add this for SMTP transport
use Zend\ServiceManager\ServiceManager;
use Zend\Mail\Transport\Smtp;
use Zend\Mail\Transport\SmtpOptions;

class Module
{
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
    
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
    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                // For Yable data Gateway
                'Edufinder\Model\EducatorTable' =>  function($sm) {
                    $tableGateway = $sm->get('EducatorTableGateway');
                    $table = new EducatorTable($tableGateway);
                    return $table;
                },
                'EducatorTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Users()); // Notice what is set here
                    return new TableGateway('educator', $dbAdapter, null, $resultSetPrototype);
                },
					 'Edufinder\Model\ParentTable' =>  function($sm) {
                    $tableGateway = $sm->get('ParentTableGateway');
                    $table = new EducatorTable($tableGateway);
                    return $table;
                },
                'ParentTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Users()); // Notice what is set here
                    return new TableGateway('parent', $dbAdapter, null, $resultSetPrototype);
                },
					 'Edufinder\Model\MarkerTable' =>  function($sm) {
                    $tableGateway = $sm->get('MarkerTableGateway');
                    $table = new MarkerTable($tableGateway);
                    return $table;
                },
					 'MarkerTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Marker()); // Notice what is set here
                    return new TableGateway('marker', $dbAdapter, null, $resultSetPrototype);
                },
                'mail.transport' => function (ServiceManager $serviceManager) {
                    $config = $serviceManager->get('Config'); 
                    $transport = new Smtp();                
                    $transport->setOptions(new SmtpOptions($config['mail']['transport']['options']));
                    return $transport;
                },
            ),
        );
    }       
}