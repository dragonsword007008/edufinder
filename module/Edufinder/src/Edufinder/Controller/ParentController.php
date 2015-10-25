<?php
namespace Edufinder\Controller;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Db\TableGateway\TableGateway;

class ParentController extends AbstractActionController
{
	protected $parentTable = null;
	
	// R - retrieve = Index
    public function indexAction()
    { 
		return new ViewModel();
		}
	
	public function getParentTable()
	{
		// I have a Table data Gateway ready to go right out of the box
		if (!$this->parentTable) {
			$this->parentTable = new TableGateway(
				'parent', 
				$this->getServiceLocator()->get('Zend\Db\Adapter\Adapter')
			);
		}
		return $this->parentTable;
	}
	
	public function profileAction(){		

		return new ViewModel(array('rowset' => $this->getParentTable()->select(array('email' => $this->params()->fromRoute('id')))));
		}
	
}