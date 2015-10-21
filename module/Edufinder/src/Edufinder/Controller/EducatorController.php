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
		
		$markers = array(
        'Mozzat Web Team' => '17.516684,79.961589',
        'Home Town' => '16.916684,80.683594'
    );  //markers location with latitude and longitude

    $config = array(
        'sensor' => 'true',         //true or false
        'div_id' => 'map',          //div id of the google map
        'div_class' => 'grid_6',    //div class of the google map
        'zoom' => 5,                //zoom level
        'width' => "600px",         //width of the div
        'height' => "300px",        //height of the div
        'lat' => 16.916684,         //lattitude
        'lon' => 80.683594,         //longitude 
        'animation' => 'none',      //animation of the marker
        'markers' => $markers       //loading the array of markers
    );

    $map = $this->getServiceLocator()->get('GMaps\Service\GoogleMap'); //getting the google map object using service manager
    $map->initialize($config);                                         //loading the config   
    $html = $map->generate();                                          //genrating the html map content     
		
		return new ViewModel(array('rowset' => $this->getEducatorTable()->select(array('email' => $this->params()->fromRoute('id'))),'map_html' => $html));
		}
	
}