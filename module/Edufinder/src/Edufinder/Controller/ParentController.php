<?php
namespace Edufinder\Controller;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Db\TableGateway\TableGateway;

class ParentController extends AbstractActionController
{
	protected $email = null;
	
	// R - retrieve = Index
    public function indexAction()
    { 
		return new ViewModel();
	 }
}