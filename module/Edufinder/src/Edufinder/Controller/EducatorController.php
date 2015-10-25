<?php
namespace Edufinder\Controller;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Db\TableGateway\TableGateway;

class EducatorController extends AbstractActionController
{
	protected $educatorTable = null;
	
	// R - retrieve = Index
    public function indexAction()
    { 
		return new ViewModel();
		}
	
	public function getEducatorTable()
	{
		// I have a Table data Gateway ready to go right out of the box
		if (!$this->educatorTable) {
			$this->educatorTable = new TableGateway(
				'educator', 
				$this->getServiceLocator()->get('Zend\Db\Adapter\Adapter')
			);
		}
		return $this->educatorTable;
	}
	
	public function profileAction(){		

		return new ViewModel(array('rowset' => $this->getEducatorTable()->select(array('email' => $this->params()->fromRoute('id')))));
		}
	
}