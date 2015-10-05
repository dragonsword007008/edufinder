<?php
namespace Edufinder; 
// Add this for Table Date Gateway
use Edufinder\Model\Edufinder;
use Edufinder\Model\UsersTable;
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
                'Edufinder\Model\UsersTable' =>  function($sm) {
                    $tableGateway = $sm->get('UsersTableGateway');
                    $table = new UsersTable($tableGateway);
                    return $table;
                },
                'UsersTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Edufinder()); // Notice what is set here
                    return new TableGateway('users', $dbAdapter, null, $resultSetPrototype);
                },
                // Add this for SMTP transport
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